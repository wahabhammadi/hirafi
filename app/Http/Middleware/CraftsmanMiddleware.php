<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CraftsmanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول للوصول إلى هذه الصفحة.');
        }
        
        $user = auth()->user();
        
        if (!$user->craftsman) {
            return redirect()->route('craftsman.register')
                ->with('error', 'يجب أن تكون مسجلاً كحرفي للوصول إلى هذه الصفحة.');
        }
        
        // Check if craftsman has completed their profile
        if (!$user->craftsman->phone_secondary) {
            return redirect()->route('craftsman.complete-profile')
                ->with('warning', 'يرجى إكمال ملفك الشخصي قبل الوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
} 