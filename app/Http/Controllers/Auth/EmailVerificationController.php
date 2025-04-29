<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class EmailVerificationController extends Controller
{
    /**
     * عرض صفحة التحقق من البريد الإلكتروني
     */
    public function show()
    {
        // تحقق مما إذا كانت هناك جلسة تحقق
        if (!Session::has('awaiting_verification') || !Session::has('verification_user_id')) {
            // إذا لم تكن هناك جلسة تحقق، قم بإعادة التوجيه إلى صفحة التسجيل
            return redirect()->route('register.type')
                ->with('error', 'يرجى التسجيل أولاً قبل التحقق من البريد الإلكتروني.');
        }

        return view('auth.verify-email');
    }

    /**
     * إرسال رمز التحقق إلى البريد الإلكتروني
     */
    public function sendCode(User $user)
    {
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
        $this->sendVerificationEmail($user, $code);

        return true;
    }

    /**
     * إرسال بريد إلكتروني يحتوي على رمز التحقق
     */
    private function sendVerificationEmail(User $user, $code)
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
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'يرجى إدخال رمز التحقق',
            'code.digits' => 'يجب أن يتكون رمز التحقق من 6 أرقام',
        ]);

        if (!Session::has('verification_user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('verification_user_id');
        $code = $request->code;

        // البحث عن الرمز الصالح
        $verificationCode = EmailVerificationCode::where('user_id', $userId)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verificationCode) {
            throw ValidationException::withMessages([
                'code' => ['رمز التحقق غير صحيح أو منتهي الصلاحية.'],
            ]);
        }

        // تعليم الرمز كمستخدم
        $verificationCode->used = true;
        $verificationCode->save();

        // الحصول على المستخدم وتعيين البريد الإلكتروني كمُتحقَق منه
        $user = User::find($userId);
        
        if (!$user) {
            // لم يتم العثور على المستخدم
            Session::forget(['awaiting_verification', 'verification_user_id']);
            return redirect()->route('login')
                ->with('error', 'حدث خطأ أثناء معالجة طلبك. يرجى تسجيل الدخول مجدداً.');
        }

        // تعيين البريد الإلكتروني كمُتحقَق منه
        $user->email_verified_at = now();
        $user->save();

        // تسجيل الدخول بصورة واضحة
        Auth::guard('web')->login($user);

        // الـتأكد من أن المستخدم مسجل الدخول
        if (!Auth::check()) {
            // فشل تسجيل الدخول
            Session::forget(['awaiting_verification', 'verification_user_id']);
            return redirect()->route('login')
                ->with('error', 'حدث خطأ أثناء تسجيل الدخول. يرجى تسجيل الدخول يدوياً.');
        }

        // إزالة بيانات الجلسة
        Session::forget(['awaiting_verification', 'verification_user_id']);

        // إعادة توجيه المستخدم بناءً على نوع حسابه
        if ($user->isCraftsman()) {
            // إذا كان صاحب أعمال، تحقق مما إذا كان قد أكمل ملفه الشخصي
            $craftsman = $user->craftsman;
            
            if ($craftsman && empty($craftsman->phone_secondary)) {
                // إذا لم يكمل ملفه الشخصي، وجهه إلى صفحة إكمال الملف الشخصي
                return redirect()->route('craftsman.complete-profile')
                    ->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح. يرجى إكمال ملفك الشخصي الآن.');
            } else {
                // إذا أكمل ملفه الشخصي، وجهه إلى لوحة التحكم
                return redirect()->route('dashboard')
                    ->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح.');
            }
        } else {
            // إذا كان عميل، وجهه إلى لوحة تحكم العملاء
            return redirect()->route('dashboard')
                ->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح.');
        }
    }

    /**
     * إعادة إرسال رمز التحقق
     */
    public function resend()
    {
        if (!Session::has('verification_user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // التحقق من عدم إرسال رمز جديد قبل مرور دقيقة على الأقل
        $lastCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCode && $lastCode->created_at->diffInSeconds() < 60) {
            return back()->with('resend_error', 'يرجى الانتظار قليلاً قبل طلب رمز جديد.');
        }

        // إرسال رمز جديد
        $this->sendCode($user);

        return back()->with('resend_success', 'تم إرسال رمز تحقق جديد بنجاح.');
    }
}
