<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportAccessMiddleware
{
    /**
     * Handle an incoming request.
     * Authorization untuk Reports: Admin & Guru only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow only Admin & Guru
        if (!in_array($user->role, ['admin', 'guru'])) {
            abort(403, 'Access denied - Reports are only available for Admin and Teachers');
        }

        return $next($request);
    }
}
