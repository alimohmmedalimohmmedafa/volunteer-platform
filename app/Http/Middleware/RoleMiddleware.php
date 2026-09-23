<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->status === 'suspended') {
            auth()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'تم تعطيل هذا الحساب. يرجى التواصل مع الإدارة.',
                ]);
        }

        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}