<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Models\Craftsman;
use App\Models\User;
use App\Services\ParticipantApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CraftsmanRegistrationController extends Controller
{
    protected $participantApiService;

    public function __construct(ParticipantApiService $participantApiService)
    {
        $this->participantApiService = $participantApiService;
    }

    /**
     * Show the craftsman registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.craftsman-register');
    }

    /**
     * Verify the ID number against the API.
     */
    public function verifyIdNumber(Request $request)
    {
        Log::info('Verifying ID number', [
            'id_number' => $request->id_number,
        ]);
        
        $request->validate([
            'id_number' => 'required|string|min:10|max:30',
        ]);

        $idNumber = $request->id_number;
        
        // Find participant by ID number using the API
        $participant = $this->participantApiService->findByIdNumber($idNumber);
        
        if (!$participant) {
            Log::warning('Participant not found for ID number', [
                'id_number' => $idNumber,
            ]);
            throw ValidationException::withMessages([
                'id_number' => ['لم يتم العثور على رقم الهوية في قاعدة البيانات.'],
            ]);
        }
        
        Log::info('Participant found', [
            'id_number' => $idNumber,
            'participant' => $participant,
        ]);
        
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Log the OTP for debugging
        Log::info('Generated OTP for participant:', [
            'id_number' => $idNumber,
            'email' => $participant['email'],
            'otp' => $otp
        ]);
        
        // Store OTP and participant data in session
        Session::put('craftsman_otp', $otp);
        Session::put('craftsman_otp_expires_at', now()->addMinutes(10));
        Session::put('craftsman_id_number', $idNumber);
        Session::put('craftsman_participant_data', $participant);
        
        Log::info('Session data stored', [
            'session_has_otp' => Session::has('craftsman_otp'),
            'session_has_expires_at' => Session::has('craftsman_otp_expires_at'),
            'session_has_id_number' => Session::has('craftsman_id_number'),
            'session_has_participant_data' => Session::has('craftsman_participant_data'),
        ]);
        
        // Send OTP via email
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($participant) {
                $message->to($participant['email'])
                        ->subject('رمز التحقق - تسجيل الحرفي');
            });
            
            Log::info('OTP email sent to:', [
                'email' => $participant['email'],
                'otp' => $otp
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage(), [
                'email' => $participant['email'],
                'otp' => $otp,
                'exception' => $e
            ]);
            
            // Continue with the process even if email fails
            // The OTP is still stored in the session
        }
        
        return redirect()->route('craftsman.verify-otp')
                        ->with('status', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    /**
     * Show the OTP verification form.
     */
    public function showVerifyOtpForm()
    {
        Log::info('Showing OTP verification form', [
            'session_has_otp' => Session::has('craftsman_otp'),
            'session_has_expires_at' => Session::has('craftsman_otp_expires_at'),
            'session_has_participant_data' => Session::has('craftsman_participant_data'),
            'session_has_id_number' => Session::has('craftsman_id_number'),
        ]);
        
        if (!Session::has('craftsman_otp')) {
            Log::warning('No OTP found in session, redirecting to registration');
            return redirect()->route('craftsman.register')
                ->with('error', 'يرجى إدخال رقم الهوية أولاً.');
        }
        
        // For development environment, pass the OTP to the view
        if (config('app.env') === 'local') {
            return view('auth.craftsman-verify-otp')->with('debug_otp', Session::get('craftsman_otp'));
        }
        
        return view('auth.craftsman-verify-otp');
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        Log::info('OTP verification started', [
            'request_otp' => $request->otp,
            'session_has_otp' => Session::has('craftsman_otp'),
            'session_has_expires_at' => Session::has('craftsman_otp_expires_at'),
            'session_has_participant_data' => Session::has('craftsman_participant_data'),
            'session_has_id_number' => Session::has('craftsman_id_number'),
        ]);
        
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);
        
        // Check if we have the OTP in the session
        if (!Session::has('craftsman_otp') || !Session::has('craftsman_otp_expires_at')) {
            Log::warning('Missing OTP data in session', [
                'session_has_otp' => Session::has('craftsman_otp'),
                'session_has_expires_at' => Session::has('craftsman_otp_expires_at'),
            ]);
            
            // If we're in development mode and have participant data, we can still proceed
            if (config('app.env') === 'local' && Session::has('craftsman_participant_data')) {
                Log::info('Development mode: Proceeding without OTP verification');
                return redirect()->route('craftsman.create-password');
            }
            
            return redirect()->route('craftsman.register')
                ->with('error', 'انتهت صلاحية رمز التحقق. يرجى المحاولة مرة أخرى.');
        }
        
        $otp = Session::get('craftsman_otp');
        $expiresAt = Session::get('craftsman_otp_expires_at');
        
        Log::info('OTP data retrieved from session', [
            'stored_otp' => $otp,
            'expires_at' => $expiresAt,
            'current_time' => now(),
            'is_expired' => now()->isAfter($expiresAt),
        ]);
        
        if (now()->isAfter($expiresAt)) {
            Session::forget(['craftsman_otp', 'craftsman_otp_expires_at']);
            Log::warning('OTP expired', [
                'expires_at' => $expiresAt,
                'current_time' => now(),
            ]);
            
            // If we're in development mode and have participant data, we can still proceed
            if (config('app.env') === 'local' && Session::has('craftsman_participant_data')) {
                Log::info('Development mode: Proceeding with expired OTP');
                return redirect()->route('craftsman.create-password');
            }
            
            throw ValidationException::withMessages([
                'otp' => ['انتهت صلاحية رمز التحقق. يرجى المحاولة مرة أخرى.'],
            ]);
        }
        
        if ($request->otp != $otp) {
            Log::warning('OTP mismatch', [
                'request_otp' => $request->otp,
                'stored_otp' => $otp,
            ]);
            
            // If we're in development mode and have participant data, we can still proceed
            if (config('app.env') === 'local' && Session::has('craftsman_participant_data')) {
                Log::info('Development mode: Proceeding despite OTP mismatch');
                return redirect()->route('craftsman.create-password');
            }
            
            throw ValidationException::withMessages([
                'otp' => ['رمز التحقق غير صحيح.'],
            ]);
        }
        
        Log::info('OTP verified successfully', [
            'request_otp' => $request->otp,
            'stored_otp' => $otp,
        ]);
        
        // Clear OTP data but keep participant data
        Session::forget(['craftsman_otp', 'craftsman_otp_expires_at']);
        
        // OTP is valid, proceed to password creation
        return redirect()->route('craftsman.create-password');
    }

    /**
     * Show the password creation form.
     */
    public function showCreatePasswordForm()
    {
        // Check if we have participant data in the session
        if (!Session::has('craftsman_participant_data')) {
            // If we have an ID number, try to fetch the participant data again
            if (Session::has('craftsman_id_number')) {
                $idNumber = Session::get('craftsman_id_number');
                $participant = $this->participantApiService->findByIdNumber($idNumber);
                
                if ($participant) {
                    Session::put('craftsman_participant_data', $participant);
                    return view('auth.craftsman-create-password');
                }
            }
            
            // If we can't recover the data, redirect to registration
            return redirect()->route('craftsman.register')
                ->with('error', 'يرجى إعادة إدخال رقم الهوية.');
        }
        
        return view('auth.craftsman-create-password');
    }

    /**
     * Create the craftsman account.
     */
    public function createPassword(Request $request)
    {
        Log::info('Creating craftsman password', [
            'session_has_participant_data' => Session::has('craftsman_participant_data'),
            'session_has_id_number' => Session::has('craftsman_id_number'),
        ]);
        
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Session::has('craftsman_participant_data')) {
            Log::warning('No participant data found in session when creating password');
            return redirect()->route('craftsman.register')
                ->with('error', 'يرجى إعادة إدخال رقم الهوية أولاً.');
        }
        
        $participantData = Session::get('craftsman_participant_data');
        
        try {
            // Check if user with this email already exists
            $existingUser = User::where('email', $participantData['email'])->first();
            if ($existingUser) {
                Log::warning('User with this email already exists', [
                    'email' => $participantData['email']
                ]);
                
                // If the existing user is a craftsman, log them in
                if ($existingUser->isCraftsman()) {
                    Auth::login($existingUser);
                    return redirect()->route('dashboard')
                        ->with('success', 'تم تسجيل الدخول بنجاح!');
                }
                
                // If the existing user is not a craftsman, show error
                throw ValidationException::withMessages([
                    'email' => ['هذا البريد الإلكتروني مسجل بالفعل لحساب آخر.'],
                ]);
            }
            
            Log::info('Creating user account', [
                'name' => $participantData['first_name'] . ' ' . $participantData['last_name'],
                'email' => $participantData['email'],
            ]);
            
            // Create user account
            $user = User::create([
                'name' => $participantData['first_name'],
                'surname' => $participantData['last_name'],
                'email' => $participantData['email'],
                'phone' => $participantData['phone'],
                'password' => Hash::make($request->password),
                'role' => 'craftsman',
            ]);
            
            Log::info('User account created', [
                'user_id' => $user->id,
                'role' => $user->role,
            ]);
            
            Log::info('Creating craftsman profile', [
                'user_id' => $user->id,
                'phone' => $participantData['phone'],
            ]);
            
            // Create craftsman profile
            $craftsman = Craftsman::create([
                'user_id' => $user->id,
                'phone' => $participantData['phone'],
                'phone_secondary' => null, // Will be updated in profile completion
                'api_id' => $participantData['id'], // Save the API ID
                'num_projects' => '0',
                'num_clients' => 0,
                'reviews' => '',
                'rating' => '0',
            ]);
            
            Log::info('Craftsman profile created', [
                'craftsman_id' => $craftsman->id,
                'phone_secondary' => $craftsman->phone_secondary,
                'api_id' => $craftsman->api_id,
            ]);
            
            // Store ID number in session for later use
            Session::put('craftsman_id_number', $participantData['id_number']);
            
            // Clear other session data
            Session::forget(['craftsman_otp', 'craftsman_otp_expires_at', 'craftsman_participant_data']);
            
            // إعداد جلسة التحقق من البريد الإلكتروني
            Session::put('awaiting_verification', true);
            Session::put('verification_user_id', $user->id);
            
            // إرسال رمز التحقق
            $verificationController = new \App\Http\Controllers\Auth\EmailVerificationController();
            $verificationController->sendCode($user);
            
            Log::info('Verification email sent, redirecting to verification page', [
                'user_id' => $user->id,
            ]);
            
            // توجيه المستخدم إلى صفحة التحقق من البريد الإلكتروني بطريقة مباشرة
            return redirect(route('verification.notice'))
                ->with('success', 'تم إنشاء حسابك بنجاح! يرجى التحقق من بريدك الإلكتروني لإكمال عملية التسجيل.');
        } catch (\Exception $e) {
            Log::error('Error during craftsman account creation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Show the profile completion form.
     */
    public function showCompleteProfileForm()
    {
        Log::info('Showing complete profile form', [
            'user_id' => Auth::id(),
        ]);
        
        if (!Auth::check()) {
            Log::warning('User not authenticated when trying to show complete profile form');
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $craftsman = $user->craftsman;
        
        if (!$craftsman) {
            Log::warning('Craftsman record not found for user', ['user_id' => $user->id]);
            return redirect()->route('login');
        }
        
        Log::info('Craftsman record found', [
            'craftsman_id' => $craftsman->id,
            'phone_secondary' => $craftsman->phone_secondary,
            'api_id' => $craftsman->api_id,
        ]);
        
        // Get participant data from API using the stored API ID
        $participant = null;
        
        if ($craftsman->api_id) {
            Log::info('Fetching participant data from API using stored API ID', [
                'api_id' => $craftsman->api_id
            ]);
            
            $participant = $this->participantApiService->findById($craftsman->api_id);
            
            if (!$participant) {
                Log::warning('Could not fetch participant data for API ID: ' . $craftsman->api_id);
                
                // Try to fetch by ID number as a fallback
                $idNumber = Session::get('craftsman_id_number');
                if ($idNumber) {
                    Log::info('Trying to fetch participant data by ID number as fallback', [
                        'id_number' => $idNumber
                    ]);
                    
                    $participant = $this->participantApiService->findByIdNumber($idNumber);
                    
                    if ($participant) {
                        Log::info('Successfully fetched participant data by ID number', [
                            'id_number' => $idNumber
                        ]);
                    } else {
                        Log::warning('Could not fetch participant data by ID number either', [
                            'id_number' => $idNumber
                        ]);
                    }
                }
            } else {
                Log::info('Participant data fetched successfully from API', [
                    'api_id' => $craftsman->api_id,
                    'name' => $participant['first_name'] . ' ' . $participant['last_name'],
                ]);
            }
        } else {
            Log::warning('No API ID found for craftsman', ['craftsman_id' => $craftsman->id]);
            
            // Try to fetch by ID number as a fallback
            $idNumber = Session::get('craftsman_id_number');
            if ($idNumber) {
                Log::info('Trying to fetch participant data by ID number as fallback', [
                    'id_number' => $idNumber
                ]);
                
                $participant = $this->participantApiService->findByIdNumber($idNumber);
                
                if ($participant) {
                    Log::info('Successfully fetched participant data by ID number', [
                        'id_number' => $idNumber
                    ]);
                    
                    // Update the craftsman with the API ID for future use
                    $craftsman->api_id = $participant['id'];
                    $craftsman->save();
                    
                    Log::info('Updated craftsman with API ID', [
                        'craftsman_id' => $craftsman->id,
                        'api_id' => $participant['id']
                    ]);
                } else {
                    Log::warning('Could not fetch participant data by ID number either', [
                        'id_number' => $idNumber
                    ]);
                }
            }
        }
        
        return view('auth.craftsman-complete-profile', [
            'participant' => $participant,
            'craftsman' => $craftsman
        ]);
    }

    /**
     * Complete the craftsman profile.
     */
    public function completeProfile(Request $request)
    {
        Log::info('Completing craftsman profile', [
            'phone_secondary' => $request->phone_secondary,
            'has_avatar' => $request->hasFile('avatar'),
            'user_id' => Auth::id(),
        ]);
        
        $request->validate([
            'phone_secondary' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:10240000', // max 100MB
        ]);
        
        if (!Auth::check()) {
            Log::warning('User not authenticated when trying to complete profile');
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        $craftsman = $user->craftsman;
        
        if (!$craftsman) {
            Log::warning('Craftsman record not found for user', ['user_id' => $user->id]);
            return redirect()->route('login');
        }
        
        $data = [
            'phone_secondary' => $request->phone_secondary,
        ];
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($craftsman->avatar) {
                Storage::delete($craftsman->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
            
            Log::info('Avatar uploaded', [
                'path' => $path,
                'craftsman_id' => $craftsman->id,
            ]);
        }
        
        Log::info('Updating craftsman profile', [
            'craftsman_id' => $craftsman->id,
            'data' => $data,
        ]);
        
        // Update craftsman profile
        $craftsman->update($data);
        
        // Get participant data and save specialty in session
        $participant = null;
        if ($craftsman->api_id) {
            $participant = $this->participantApiService->findById($craftsman->api_id);
            if ($participant && isset($participant['specialization_name'])) {
                // Map the API specialization to our system specialization
                $specialtyMapping = [
                    'الهندسة الكهربائية' => 'الهندسة الكهربائية',
                    'النجارة' => 'النجارة العامة',
                    'الحدادة' => 'الحدادة الفنية',
                    'الميكانيك' => 'الميكانيك العام',
                    'السباكة' => 'السباكة والتدفئة',
                    'الخياطة' => 'الخياطة وتصميم الأزياء',
                    'تكييف' => 'تكييف الهواء',
                    'البناء' => 'البناء'
                ];
                
                $specialty = null;
                foreach ($specialtyMapping as $apiKey => $systemValue) {
                    if (strpos($participant['specialization_name'], $apiKey) !== false) {
                        $specialty = $systemValue;
                        break;
                    }
                }
                
                // Default to the first available specialty if no match found
                if (!$specialty) {
                    $specialty = 'الهندسة الكهربائية';
                }
                
                Session::put('craftsman_specialty', $specialty);
                
                Log::info('Saved craftsman specialty to session', [
                    'craftsman_id' => $craftsman->id,
                    'api_specialization' => $participant['specialization_name'],
                    'mapped_specialty' => $specialty
                ]);
            }
        }
        
        Log::info('Craftsman profile updated successfully', [
            'craftsman_id' => $craftsman->id,
            'phone_secondary' => $craftsman->phone_secondary,
            'avatar' => $craftsman->avatar,
        ]);
        
        // توجيه المستخدم إلى لوحة التحكم
        return redirect()->route('dashboard')
            ->with('status', 'تم إكمال ملفك الشخصي بنجاح!');
    }
} 