<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول للوصول إلى هذه الصفحة.');
        }

        $user = auth()->user();

        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')
            ->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
    }
}
