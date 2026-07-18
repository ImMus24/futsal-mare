<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // Mengalihkan pengguna ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani callback dari Google setelah login sukses
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah pengguna sudah terdaftar dengan email ini
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Jika belum terdaftar, buat akun baru otomatis
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Password acak aman
                ]);
            }

            // Login-kan pengguna ke dalam sistem Laravel
            Auth::login($user);

            // Arahkan ke dashboard admin kalau akun ini sudah diberi akses admin
            if ($user->is_admin == 1) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang kembali, Admin ' . $user->name);
            }

            return redirect()->route('dashboard')->with('success', 'Selamat Datang, ' . $user->name);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal melakukan autentikasi menggunakan Google.']);
        }
    }
}