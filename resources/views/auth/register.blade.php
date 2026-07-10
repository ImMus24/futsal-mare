<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Member Baru - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden p-8 sm:p-10 relative">
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-[#E25E20] rounded-full filter blur-[60px] opacity-10 pointer-events-none"></div>

        <div class="text-center mb-6">
            <a href="{{ route('landingPage') }}" class="inline-flex items-center space-x-2 mb-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto object-contain">
                <div class="flex flex-col text-left">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight uppercase">Gabung Member Arena</h2>
            <p class="text-slate-400 text-xs mt-0.5 font-bold uppercase tracking-wider">Dapatkan kemudahan akses reservasi instan 24 jam</p>
        </div>

        <div>
            <a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white font-black text-xs rounded-xl shadow-md transition uppercase tracking-wider">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar Instan via Google
            </a>
        </div>

        <div class="relative my-5 text-center">
            <span class="absolute inset-x-0 top-1/2 h-px bg-slate-800 -translate-y-1/2"></span>
            <span class="relative bg-[#152238] px-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Atau Isi Form Manual</span>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="Nama Anda">
                @error('name') <p class="text-red-400 text-[11px] font-bold mt-1.5">⚠️ {{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Email Aktif</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="nama@email.com">
                @error('email') <p class="text-red-400 text-[11px] font-bold mt-1.5">⚠️ {{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                <input type="password" name="password" required
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="Minimal 8 Karakter">
                @error('password') <p class="text-red-400 text-[11px] font-bold mt-1.5">⚠️ {{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Ulangi Kata Sandi</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3 focus:border-[#E25E20] focus:ring-[#E25E20] transition shadow-inner placeholder-slate-600" placeholder="Konfirmasi Sandi">
            </div>

            <button type="submit" class="w-full py-4 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl uppercase tracking-widest shadow-md transition duration-200 transform hover:-translate-y-0.5 mt-2">
                Daftar Akun Baru
            </button>
        </form>

        <p class="text-center text-xs font-bold text-slate-500 mt-5 uppercase tracking-wide">
            Sudah terdaftar? <a href="{{ route('login') }}" class="text-[#E25E20] font-black normal-case hover:underline">Masuk Sini</a>
        </p>
    </div>

</body>
</html>