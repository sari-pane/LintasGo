<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request and ensure the authenticated user has one of the allowed roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            $loginRoute = in_array('admin', $roles, true) && ! in_array('passenger', $roles, true)
                ? route('admin.login')
                : route('login');

            return redirect()->guest($loginRoute)->with('error', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        if (! empty($roles) && ! in_array($request->user()->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Anda tidak memiliki hak akses untuk fitur ini.'], 403);
            }

            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
