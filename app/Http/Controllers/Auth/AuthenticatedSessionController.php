<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Jalankan proses autentikasi (cek email/password)
        $request->authenticate();

        // 2. Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // 3. Pengecekan ROLE untuk pengalihan (Routing)
        // Jika user adalah admin, arahkan ke dashboard admin
        if (Auth::user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        }

        // Jika bukan admin, arahkan ke dashboard member/biasa
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}