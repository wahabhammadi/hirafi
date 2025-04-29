<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class VerificationCodeController extends Controller
{
    /**
     * عرض صفحة التحقق من البريد الإلكتروني
     */
    public function showVerificationForm()
    {
        // إذا لم تكن هناك معلومات مستخدم للتحقق في الجلسة، أعد توجيهه إلى الصفحة الرئيسية
        if (!Session::has('verification_user_id')) {
            return redirect('/');
        }

        return view('auth.code-verification');
    }

    /**
     * إرسال رمز التحقق إلى البريد الإلكتروني
     */
    public function sendVerificationCode($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // إنشاء رمز عشوائي من 6 أرقام
        $code = mt_rand(100000, 999999);
        
        // إلغاء الرموز السابقة للمستخدم
        EmailVerificationCode::where('user_id', $user->id)
            ->where('used', false)
            ->update(['used' => true]);
        
        // إنشاء سجل جديد للرمز
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
            'used' => false
        ]);

        // إرسال الرمز عبر البريد الإلكتروني
        $this->sendCodeEmail($user, $code);

        return true;
    }

    /**
     * إرسال بريد إلكتروني يحتوي على رمز التحقق
     */
    private function sendCodeEmail($user, $code)
    {
        $data = [
            'name' => $user->name,
            'code' => $code
        ];

        Mail::send('emails.verification-code', $data, function($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('رمز التحقق من البريد الإلكتروني');
        });
    }

    /**
     * التحقق من الرمز المدخل
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:6',
        ], [
            'verification_code.required' => 'يرجى إدخال رمز التحقق',
            'verification_code.digits' => 'يجب أن يتكون رمز التحقق من 6 أرقام',
        ]);

        if (!Session::has('verification_user_id')) {
            return redirect('/')->with('error', 'يرجى التسجيل أولاً');
        }

        $userId = Session::get('verification_user_id');
        $code = $request->verification_code;

        // البحث عن الرمز الصالح
        $verificationCode = EmailVerificationCode::where('user_id', $userId)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verificationCode) {
            return back()->withErrors(['verification_code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }

        // تعليم الرمز كمستخدم
        $verificationCode->used = true;
        $verificationCode->save();

        // الحصول على المستخدم وتعيين البريد الإلكتروني كمُتحقَق منه
        $user = User::find($userId);
        
        if (!$user) {
            Session::forget(['verification_user_id']);
            return redirect('/')->with('error', 'حدث خطأ أثناء معالجة طلبك');
        }

        // تعيين البريد الإلكتروني كمُتحقَق منه
        $user->email_verified_at = now();
        $user->save();

        // تسجيل دخول المستخدم
        Auth::login($user);

        // إزالة بيانات الجلسة
        Session::forget(['verification_user_id']);

        // إعادة توجيه المستخدم بناءً على نوع حسابه
        if ($user->isCraftsman()) {
            return redirect()->route('dashboard');
        } else {
            // إذا كان عميل، وجهه إلى لوحة تحكم العملاء
            return redirect()->route('dashboard')
                ->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح.');
        }
    }

    /**
     * إعادة إرسال رمز التحقق
     */
    public function resendCode()
    {
        if (!Session::has('verification_user_id')) {
            return redirect('/');
        }

        $userId = Session::get('verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            Session::forget(['verification_user_id']);
            return redirect('/');
        }

        // التحقق من عدم إرسال رمز جديد قبل مرور دقيقة على الأقل
        $lastCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCode && $lastCode->created_at->diffInSeconds() < 60) {
            return back()->with('error', 'يرجى الانتظار قليلاً قبل طلب رمز جديد.');
        }

        // إرسال رمز جديد
        $this->sendVerificationCode($user->id);

        return back()->with('success', 'تم إرسال رمز تحقق جديد بنجاح.');
    }
}
