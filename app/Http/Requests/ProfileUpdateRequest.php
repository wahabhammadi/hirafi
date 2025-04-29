<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        // إذا كان المستخدم حرفي
        if ($this->user()->role === 'craftsman') {
            $rules = [
                'phone_secondary' => ['nullable', 'string', 'max:20'],
                'avatar' => ['nullable', 'image', 'max:20480'], // 20MB
            ];
            
            // إضافة قواعد التحقق لكلمة المرور إذا تم تقديمها
            if ($this->filled('password')) {
                $rules['current_password'] = ['required', 'current_password'];
                $rules['password'] = ['required', Password::defaults(), 'confirmed'];
                $rules['password_confirmation'] = ['required'];
            }
            
            return $rules;
        }
        
        // للعملاء
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:20480'], // 20MB
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال الاسم',
            'name.max' => 'يجب أن لا يتجاوز الاسم 255 حرف',
            'surname.max' => 'يجب أن لا يتجاوز اللقب 255 حرف',
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',
            'phone.max' => 'يجب أن لا يتجاوز رقم الهاتف 20 رقم',
            'phone_secondary.max' => 'يجب أن لا يتجاوز رقم الهاتف الثانوي 20 رقم',
            'address.max' => 'يجب أن لا يتجاوز العنوان 1000 حرف',
            'avatar.image' => 'يجب أن يكون الملف صورة',
            'avatar.mimes' => 'يجب أن يكون الملف صورة صيغة jpeg, png, jpg, gif',
            'avatar.max' => 'يجب أن لا يتجاوز حجم الصورة 20 ميجابايت',
            'current_password.required' => 'يرجى إدخال كلمة المرور الحالية',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة',
            'password.required' => 'يرجى إدخال كلمة المرور الجديدة',
            'password.confirmed' => 'كلمة المرور الجديدة غير متطابقة',
            'password_confirmation.required' => 'يرجى تأكيد كلمة المرور الجديدة',
        ];
    }
}
