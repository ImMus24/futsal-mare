<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun Anda - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden p-8 sm:p-10 relative">
        <div class="absolute -top-20 -right-20 w-40 h-40 bg-[#E25E20] rounded-full filter blur-[60px] opacity-10 pointer-events-none"></div>
        
        <div class="text-center mb-8 relative z-10">
            <a href="{{ route('landingPage') }}" class="inline-flex items-center space-x-2 mb-4 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                <div class="flex flex-col text-left">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight uppercase">Selamat Datang Kembali!</h2>
            <p class="text-slate-400 text-xs mt-1 font-bold uppercase tracking-wider">Masuk untuk mengamankan slot tanding tim Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-sm p-3.5 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="nama@email.com">
                @error('email') <p class="text-red-400 text-xs font-bold mt-1.5">⚠️ {{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Kata Sandi</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#E25E20] hover:underline tracking-tight">Lupa Sandi?</a>
                </div>
                <input type="password" name="password" required
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-sm p-3.5 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="••••••••">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-slate-800 bg-[#0B131F] text-[#E25E20] focus:ring-[#E25E20] focus:ring-offset-[#152238]">
                <label for="remember" class="ml-2 text-xs font-bold text-slate-400 select-none cursor-pointer uppercase tracking-wide">Ingat perangkat ini</label>
            </div>

            <button type="submit" class="w-full py-4 bg-[#0B131F] border border-slate-800 hover:border-transparent hover:bg-[#E25E20] text-white font-black text-xs rounded-xl uppercase tracking-widest transition-all duration-300 shadow-md">
                Masuk Aplikasi
            </button>
        </form>

        <div class="relative my-6 text-center">
            <span class="absolute inset-x-0 top-1/2 h-px bg-slate-800 -translate-y-1/2"></span>
            <span class="relative bg-[#152238] px-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Atau Masuk Lewat</span>
        </div>

        <div class="mb-6">
            <a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white font-black text-xs rounded-xl shadow-md transition uppercase tracking-wider">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Akun Google
            </a>
        </div>

        <p class="text-center text-xs font-bold text-slate-500 uppercase tracking-wide">
            Belum punya akun? <a href="{{ route('register') }}" class="text-[#E25E20] font-black normal-case hover:underline">Daftar Sekarang &rarr;</a>
        </p>
    </div>

</body>
</html>