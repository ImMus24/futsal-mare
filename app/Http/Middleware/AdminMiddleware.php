<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memastikan user sudah login DAN memiliki role/status sebagai admin
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->is_admin == 1)) {
            return $next($request);
        }

        // Jika bukan admin, tendang balik ke halaman awal dengan pesan error
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
    }
}