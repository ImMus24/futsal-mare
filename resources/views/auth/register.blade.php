<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Member Baru - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased min-h-screen flex items-center justify-center p-4 selection:bg-[#E25E20] selection:text-white">

    <div class="w-full max-w-md bg-[#152238]/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-800/80 overflow-hidden p-8 sm:p-10 relative group">
        <div class="absolute -top-20 -left-20 w-48 h-48 bg-[#E25E20] rounded-full filter blur-[80px] opacity-10 pointer-events-none group-hover:opacity-15 transition duration-500"></div>
        <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-blue-600 rounded-full filter blur-[80px] opacity-5 pointer-events-none"></div>

        <div class="text-center mb-6 relative z-10">
            <a href="{{ route('landingPage') }}" class="inline-flex items-center space-x-3 mb-4 group/logo">
                <div class="relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#E25E20] to-orange-500 rounded-xl filter blur-sm opacity-20 group-hover/logo:opacity-40 transition duration-300"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Futsal Mare" class="h-10 w-auto object-contain transform group-hover/logo:rotate-6 transition duration-300 relative z-10">
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-black text-[#E25E20] tracking-[0.25em] uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <h2 class="text-2xl font-black text-white tracking-tight uppercase">Gabung Member Arena</h2>
            <p class="text-slate-400 text-[10px] mt-1.5 font-bold uppercase tracking-widest">Dapatkan kemudahan akses reservasi instan 24 jam</p>
        </div>

        <div class="relative z-10">
            <a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white font-black text-xs rounded-xl shadow-md transition-all duration-200 uppercase tracking-wider hover:bg-[#0c1624]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar Instan via Google
            </a>
        </div>

        <div class="relative my-5 text-center">
            <span class="absolute inset-x-0 top-1/2 h-px bg-slate-800/80 -translate-y-1/2"></span>
            <span class="relative bg-[#152238] px-4 text-[9px] font-black text-slate-500 uppercase tracking-widest">Atau Isi Form Manual</span>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4 relative z-10">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" required value="{{ old('name') }}" autocomplete="name" autofocus
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3.5 focus:border-[#E25E20] focus:ring-0 transition-all duration-200 shadow-inner placeholder-slate-600 font-medium hover:border-slate-700" placeholder="Nama Lengkap Anda">
                @error('name') 
                    <p class="text-red-400 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                        <span>⚠️</span> {{ $message }}
                    </p> 
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Email Aktif</label>
                <input type="email" name="email" required value="{{ old('email') }}" autocomplete="email"
                    class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3.5 focus:border-[#E25E20] focus:ring-0 transition-all duration-200 shadow-inner placeholder-slate-600 font-medium hover:border-slate-700" placeholder="nama@email.com">
                @error('email') 
                    <p class="text-red-400 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                        <span>⚠️</span> {{ $message }}
                    </p> 
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" required autocomplete="new-password"
                        class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3.5 pr-12 focus:border-[#E25E20] focus:ring-0 transition-all duration-200 shadow-inner placeholder-slate-600 font-mono hover:border-slate-700" placeholder="Minimal 8 Karakter">
                    <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-[10px] font-black uppercase select-none tracking-wider">
                        Lihat
                    </button>
                </div>
                @error('password') 
                    <p class="text-red-400 text-[11px] font-bold mt-1.5 flex items-center gap-1">
                        <span>⚠️</span> {{ $message }}
                    </p> 
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ulangi Kata Sandi</label>
                <div class="relative">
                    <input type="password" id="confirmPasswordInput" name="password_confirmation" required autocomplete="new-password"
                        class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-xs p-3.5 pr-12 focus:border-[#E25E20] focus:ring-0 transition-all duration-200 shadow-inner placeholder-slate-600 font-mono hover:border-slate-700" placeholder="Konfirmasi Sandi">
                    <button type="button" id="toggleConfirmPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 text-[10px] font-black uppercase select-none tracking-wider">
                        Lihat
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#E25E20] to-orange-600 hover:from-[#cb5119] hover:to-orange-700 text-white font-black text-xs rounded-xl uppercase tracking-widest shadow-lg shadow-black/30 transition-all duration-300 transform hover:-translate-y-0.5 mt-4">
                Daftar Akun Baru
            </button>
        </form>

        <p class="text-center text-xs font-bold text-slate-500 mt-6 uppercase tracking-wide relative z-10">
            Sudah terdaftar? <a href="{{ route('login') }}" class="text-[#E25E20] font-black normal-case hover:underline pl-1 transition hover:text-orange-400">Masuk Sini &rarr;</a>
        </p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Setup Toggle for Main Password
            const passwordInput = document.getElementById("passwordInput");
            const togglePassword = document.getElementById("togglePassword");
            
            togglePassword.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    togglePassword.textContent = "Sembunyikan";
                    togglePassword.classList.add("text-[#E25E20]");
                } else {
                    passwordInput.type = "password";
                    togglePassword.textContent = "Lihat";
                    togglePassword.classList.remove("text-[#E25E20]");
                }
            });

            // Setup Toggle for Confirmation Password
            const confirmPasswordInput = document.getElementById("confirmPasswordInput");
            const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

            toggleConfirmPassword.addEventListener("click", function () {
                if (confirmPasswordInput.type === "password") {
                    confirmPasswordInput.type = "text";
                    toggleConfirmPassword.textContent = "Sembunyikan";
                    toggleConfirmPassword.classList.add("text-[#E25E20]");
                } else {
                    confirmPasswordInput.type = "password";
                    toggleConfirmPassword.textContent = "Lihat";
                    toggleConfirmPassword.classList.remove("text-[#E25E20]");
                }
            });
        });
    </script>
</body>
</html>