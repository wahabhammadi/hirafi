<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class ClientRegistrationController extends Controller
{
    /**
     * Show the client registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        Log::info('Showing client registration form');
        return view('auth.client-register');
    }

    /**
     * Handle the client registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        Log::info('Processing client registration request', ['email' => $request->email]);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'role' => 'client',
            ]);

            Log::info('Client user created', ['user_id' => $user->id]);

            // Create client profile
            $client = Client::create([
                'user_id' => $user->id,
                'last_name' => $request->surname,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            Log::info('Client profile created', ['client_id' => $client->id]);

            // حفظ معرف المستخدم في الجلسة للتحقق
            Session::put('verification_user_id', $user->id);
            Session::put('awaiting_verification', true);
            
            // إرسال رمز التحقق إلى البريد الإلكتروني
            $verificationController = new EmailVerificationController();
            $verificationController->sendCode($user);
            
            Log::info('Verification code sent to client', ['user_id' => $user->id]);

            // توجيه المستخدم إلى صفحة إدخال رمز التحقق
            return redirect()->route('verification.notice')
                ->with('success', 'تم إنشاء حسابك بنجاح! يرجى إدخال رمز التحقق المرسل إلى بريدك الإلكتروني.');
        } catch (\Exception $e) {
            Log::error('Error during client registration', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.');
        }
    }
} 