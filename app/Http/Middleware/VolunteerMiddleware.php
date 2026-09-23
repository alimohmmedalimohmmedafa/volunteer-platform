<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VolunteerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->status === 'suspended') {
            auth()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'تم تعطيل هذا الحساب. يرجى التواصل مع الإدارة.',
                ]);
        }

        if (auth()->user()->role !== 'volunteer') {
            abort(403, 'غير مصرح لك بالوصول إلى مساحة المتطوع.');
        }

        return $next($request);
    }
}
