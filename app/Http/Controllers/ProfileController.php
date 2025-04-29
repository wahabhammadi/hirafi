<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $participant = null;
        $craftsman = null;
        
        // إذا كان المستخدم حرفيًا، نقوم بجلب بيانات الحرفي والبيانات من API
        if ($user->role === 'craftsman' && $user->craftsman) {
            $craftsman = $user->craftsman;
            
            // جلب بيانات المشارك من API إذا كان معرف API موجودًا
            if ($craftsman->api_id) {
                $participantApiService = app(\App\Services\ParticipantApiService::class);
                $participant = $participantApiService->findById($craftsman->api_id);
                
                if (!$participant) {
                    \Illuminate\Support\Facades\Log::warning('Could not fetch participant data for API ID: ' . $craftsman->api_id);
                    
                    // محاولة البحث بواسطة رقم الهوية كخيار بديل
                    $idNumber = \Illuminate\Support\Facades\Session::get('craftsman_id_number');
                    if ($idNumber) {
                        $participant = $participantApiService->findByIdNumber($idNumber);
                    }
                }
            }
            
            return view('profile.craftsman-profile', [
                'user' => $user,
                'craftsman' => $craftsman,
                'participant' => $participant
            ]);
        } else {
            // للعملاء
            return view('profile.client-profile', [
                'user' => $user
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            \Log::info('Profile update request received', [
                'user_id' => $request->user()->id,
                'has_avatar' => $request->hasFile('avatar'),
                'data' => $request->all(),
                'user_role' => $request->user()->role
            ]);
            
            $user = $request->user();
            $data = $request->validated();
            
            \Log::info('Validated data', $data);

            // إذا كان المستخدم حرفي
            if ($user->role === 'craftsman') {
                try {
                    // التعامل مع الصورة الشخصية فقط إذا تم تحميل صورة جديدة
                    if ($request->hasFile('avatar')) {
                        \Log::info('Processing craftsman avatar upload', [
                            'original_name' => $request->file('avatar')->getClientOriginalName(),
                            'mime_type' => $request->file('avatar')->getClientMimeType(),
                            'size' => $request->file('avatar')->getSize(),
                            'storage_path' => storage_path('app/public'),
                            'app_public_exists' => file_exists(storage_path('app/public')),
                            'avatars_dir_exists' => file_exists(storage_path('app/public/avatars'))
                        ]);
                        
                        // التأكد من وجود المجلد
                        if (!file_exists(storage_path('app/public/avatars'))) {
                            mkdir(storage_path('app/public/avatars'), 0755, true);
                            \Log::info('Created avatars directory');
                        }
                        
                        // تخزين الصورة الجديدة في جدول الحرفيين
                        $avatarPath = $request->file('avatar')->store('avatars', 'public');
                        
                        if ($avatarPath) {
                            \Log::info('Successfully stored avatar', [
                                'path' => $avatarPath,
                                'full_path' => storage_path('app/public/' . $avatarPath),
                                'file_exists' => file_exists(storage_path('app/public/' . $avatarPath)),
                                'public_url' => asset('storage/' . $avatarPath)
                            ]);
                            
                            if ($user->craftsman) {
                                // حذف الصورة القديمة من جدول الحرفيين إذا كانت موجودة
                                if ($user->craftsman->avatar) {
                                    Storage::disk('public')->delete($user->craftsman->avatar);
                                    \Log::info('Deleted old craftsman avatar', ['old_path' => $user->craftsman->avatar]);
                                }
                                
                                $user->craftsman->avatar = $avatarPath;
                                $user->craftsman->save();
                                
                                \Log::info('Craftsman avatar updated', [
                                    'craftsman_id' => $user->craftsman->id,
                                    'path' => $avatarPath
                                ]);
                            }
                        } else {
                            \Log::error('Failed to store avatar file');
                            return Redirect::route('profile.edit')->with('error', 'فشل في تخزين الصورة الشخصية. حاول مرة أخرى.');
                        }
                    }
                    
                    // تحديث رقم الهاتف الثانوي
                    if ($user->craftsman && $request->has('phone_secondary')) {
                        $user->craftsman->phone_secondary = $request->input('phone_secondary');
                        $user->craftsman->save();
                        
                        \Log::info('Craftsman phone_secondary updated', [
                            'craftsman_id' => $user->craftsman->id,
                            'phone_secondary' => $user->craftsman->phone_secondary
                        ]);
                    }
                    
                    // تحديث كلمة المرور إذا تم توفيرها
                    if ($request->filled('password')) {
                        $user->password = bcrypt($request->password);
                        $user->save();
                        \Log::info('Craftsman password updated', [
                            'user_id' => $user->id
                        ]);
                    }
                    
                    return Redirect::route('profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
                } catch (\Exception $e) {
                    \Log::error('Error updating craftsman profile', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    return Redirect::route('profile.edit')->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . $e->getMessage());
                }
            } else {
                // مستخدم عادي (عميل) - نموذج التحديث المعتاد
                // التعامل مع الصورة الشخصية
                if ($request->hasFile('avatar')) {
                    // حذف الصورة القديمة إذا كانت موجودة
                    if ($user->avatar) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    
                    // تخزين الصورة الجديدة
                    $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
                    \Log::info('New avatar stored', ['path' => $data['avatar']]);
                } elseif ($request->has('remove_avatar') && $request->input('remove_avatar') == 1) {
                    // حذف الصورة إذا طلب المستخدم ذلك
                    if ($user->avatar) {
                        Storage::disk('public')->delete($user->avatar);
                        \Log::info('Avatar deleted', ['old_path' => $user->avatar]);
                    }
                    $data['avatar'] = null;
                }

                $user->fill($data);
                
                \Log::info('User data before save', [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'is_dirty' => $user->isDirty(),
                    'dirty_attributes' => $user->getDirty()
                ]);

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                // حفظ معلومات المستخدم
                if (!$user->save()) {
                    \Log::error('Failed to save user data', [
                        'user_id' => $user->id
                    ]);
                    return Redirect::route('profile.edit')->with('error', 'حدث خطأ أثناء حفظ البيانات');
                }
            }
            
            \Log::info('User profile updated successfully');

            return Redirect::route('profile.edit')->with('success', 'تم تحديث الملف الشخصي بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error updating profile', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Redirect::route('profile.edit')->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // حذف الصورة الشخصية إذا كانت موجودة
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
