<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePassenger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'))->with('error', 'Silakan login terlebih dahulu untuk melanjutkan pemesanan.');
        }

        if (! $request->user()->isPassenger()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Akses khusus Penumpang.'], 403);
            }

            abort(403, 'Akses ditolak. Fitur ini khusus untuk akun Penumpang.');
        }

        return $next($request);
    }
}
