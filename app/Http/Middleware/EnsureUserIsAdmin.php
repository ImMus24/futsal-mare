<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login sama sekali -> lempar ke halaman login admin
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Sudah login tapi bukan admin -> tolak akses
        if (Auth::user()->is_admin != 1) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        return $next($request);
    }
}