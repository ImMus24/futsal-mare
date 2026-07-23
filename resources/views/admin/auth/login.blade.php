<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gatelog Gateway - Portal Admin Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=JetBrains+Mono:wght@400;500;700&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        body { font-family: var(--body); }
        .f-display { font-family: var(--display); }
        .f-mono { font-family: var(--mono); }
    </style>
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 flex min-h-screen items-center justify-center p-4 relative overflow-hidden">
    
    <!-- GRID & GLOW BACKGROUND ACCENTS -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.03] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:28px_28px]"></div>
    <div class="absolute -top-24 -left-24 w-[400px] h-[400px] bg-[#E25E20] rounded-full filter blur-[160px] opacity-15 pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-[400px] h-[400px] bg-[#3B82F6] rounded-full filter blur-[160px] opacity-10 pointer-events-none"></div>

    <main class="w-full max-w-md relative z-10 space-y-6">
        
        <!-- BRANDING HEADER -->
        <div class="flex flex-col items-center text-center space-y-3">
            <div class="inline-flex items-center space-x-2.5 bg-[#152238]/60 border border-slate-700/50 px-4 py-2 rounded-2xl backdrop-blur-md shadow-2xl">
                <span class="w-2 h-2 rounded-full bg-[#E25E20] animate-pulse"></span>
                <span class="f-display text-xl uppercase text-white tracking-wide">FUTSAL</span>
                <span class="f-mono text-xs font-bold text-[#E25E20] tracking-[0.25em] uppercase">MARE</span>
            </div>
            <div>
                <h1 class="text-lg font-black uppercase text-white tracking-wider">Otorisasi Administrator</h1>
                <p class="text-xs text-slate-400 mt-0.5">Masuk ke panel kontrol utama manajemen arena</p>
            </div>
        </div>

        <!-- MAIN LOGIN CARD -->
        <div class="bg-[#152238]/80 backdrop-blur-xl p-8 rounded-3xl border border-slate-800/80 shadow-2xl relative overflow-hidden space-y-6">
            
            <!-- HIGHLIGHT BORDER TOP -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#E25E20] to-transparent"></div>

            <!-- ALERT ERROR -->
            @if ($errors->any())
                <div class="p-4 bg-red-950/50 border border-red-500/30 text-red-300 rounded-2xl text-xs font-semibold space-y-1.5 shadow-lg">
                    <div class="f-mono text-[10px] uppercase font-bold text-red-400 tracking-wider flex items-center gap-1.5">
                        <span>⚠️</span> Gagal Mengakses System:
                    </div>
                    <ul class="list-disc list-inside space-y-1 opacity-90 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- QUICK ACTIONS: GOOGLE LOGIN & STAFF LOGIN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- GOOGLE LOGIN -->
                <a href="{{ route('admin.google.redirect') }}" class="flex items-center justify-center gap-2 py-3 px-3 bg-[#0B131F] hover:bg-[#1a293e] border border-slate-700/80 hover:border-slate-500 text-slate-200 font-bold text-xs rounded-xl uppercase tracking-wider transition-all duration-200 shadow-md group">
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110 flex-shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span class="truncate">Google</span>
                </a>

                <!-- STAFF SCANNER LOGIN LINK -->
                <a href="{{ route('staff.login') }}" class="flex items-center justify-center gap-2 py-3 px-3 bg-[#0284c7]/10 hover:bg-[#0284c7]/20 border border-sky-500/40 hover:border-sky-400 text-sky-300 font-bold text-xs rounded-xl uppercase tracking-wider transition-all duration-200 shadow-md group">
                    <span class="text-sm transition-transform group-hover:scale-110">📲</span>
                    <span class="truncate">Portal Staff</span>
                </a>
            </div>

            <!-- DIVIDER PEMISAH -->
            <div class="relative flex items-center justify-center">
                <div class="border-t border-slate-800 w-full"></div>
                <span class="bg-[#152238] px-3 f-mono text-[10px] font-bold text-slate-500 uppercase tracking-widest absolute">Atau Email Admin</span>
            </div>

            <!-- FORM LOGIN EMAIL & PASSWORD -->
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block f-mono text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-2">Email Administrator</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@futsalmare.com" class="w-full bg-[#0B131F] border border-slate-800 focus:border-[#E25E20] focus:ring-1 focus:ring-[#E25E20] rounded-xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-600 transition duration-150 outline-none">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block f-mono text-[10px] font-bold uppercase text-slate-400 tracking-wider">Kata Sandi Akses</label>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-[#0B131F] border border-slate-800 focus:border-[#E25E20] focus:ring-1 focus:ring-[#E25E20] rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-slate-600 tracking-widest transition duration-150 outline-none">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none group">
                        <input type="checkbox" name="remember" class="rounded bg-[#0B131F] border-slate-800 text-[#E25E20] focus:ring-0 cursor-pointer w-4 h-4">
                        <span class="text-xs font-semibold text-slate-400 group-hover:text-slate-300 transition ml-2">Ingat Sesi Perangkat</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl uppercase tracking-widest shadow-lg shadow-orange-950/40 transform hover:-translate-y-0.5 transition active:translate-y-0 duration-150">
                        🔑 Masuk Ke Panel Utama
                    </button>
                </div>
            </form>
        </div>

        <!-- NAVIGASI KEMBALI -->
        <div class="text-center">
            <a href="{{ route('landingPage') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-300 uppercase tracking-wider transition duration-150">
                <span>&larr;</span> Kembali Ke Beranda Utama
            </a>
        </div>
    </main>

</body>
</html>