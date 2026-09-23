<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->role !== 'organization') {
            abort(403, 'غير مسموح لك بالوصول إلى هذه الصفحة.');
        }

        $organization = $user->organization;

        if (!$organization) {
            abort(403, 'لا توجد منظمة مرتبطة بهذا الحساب.');
        }

        if ($organization->status === 'pending') {
            return redirect()->route('organization.pending');
        }

        if ($organization->status === 'rejected') {
            return redirect()->route('organization.rejected');
        }

        if ($organization->status !== 'approved') {
            abort(403);
        }

        return $next($request);
    }
}