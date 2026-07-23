<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Terminal Access - Futsal Mare</title>
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
<body class="bg-[#0A0F14] font-sans antialiased text-slate-200 flex min-h-screen items-center justify-center p-4 relative overflow-hidden">

    <!-- GRID BACKGROUND & GLOW ACCENTS -->
    <div class="absolute inset-0 pointer-events-none opacity-[0.04] bg-[radial-gradient(#38bdf8_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    <div class="absolute -top-32 -right-32 w-[450px] h-[450px] bg-[#0284c7] rounded-full filter blur-[180px] opacity-15 pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-[450px] h-[450px] bg-[#E25E20] rounded-full filter blur-[180px] opacity-10 pointer-events-none"></div>

    <main class="w-full max-w-md relative z-10 space-y-6">

        <!-- BRANDING & BADGE HEADER -->
        <div class="flex flex-col items-center text-center space-y-3">
            <div class="inline-flex items-center space-x-2.5 bg-[#121A23]/80 border border-slate-800 px-4 py-2 rounded-2xl backdrop-blur-md shadow-xl">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="f-display text-xl uppercase text-white tracking-wide">FUTSAL</span>
                <span class="f-mono text-xs font-bold text-sky-400 tracking-[0.2em] uppercase">GATE TERMINAL</span>
            </div>
            <div>
                <h1 class="text-lg font-black uppercase text-white tracking-wider">Otorisasi Petugas Scanner</h1>
                <p class="text-xs text-slate-400 mt-0.5">Masuk untuk mengaktifkan sistem scanner & validasi tiket</p>
            </div>
        </div>

        <!-- MAIN CARD LOGIN -->
        <div class="bg-[#121A23]/90 backdrop-blur-xl p-8 rounded-3xl border border-slate-800/80 shadow-2xl relative overflow-hidden space-y-6">

            <!-- CYAN BORDER TOP HIGHLIGHT -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-sky-500 to-transparent"></div>

            <!-- ERROR NOTIFICATION BANNER -->
            @if ($errors->any())
                <div class="p-4 bg-red-950/50 border border-red-500/30 text-red-300 rounded-2xl text-xs font-semibold space-y-1.5 shadow-lg">
                    <div class="f-mono text-[10px] uppercase font-bold text-red-400 tracking-wider flex items-center gap-1.5">
                        <span>⚠️</span> Kredensial Tidak Valid:
                    </div>
                    <ul class="list-disc list-inside space-y-1 opacity-90 pl-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- LOGIN FORM -->
            <form action="{{ route('staff.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block f-mono text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-2">ID Staff / Email Operational</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="staff@futsalmare.com" class="w-full bg-[#0A0F14] border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 rounded-xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-600 transition duration-150 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block f-mono text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-2">PIN / Kata Sandi Terminal</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-[#0A0F14] border border-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-slate-600 tracking-widest transition duration-150 outline-none">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none group">
                        <input type="checkbox" name="remember" class="rounded bg-[#0A0F14] border-slate-800 text-sky-500 focus:ring-0 cursor-pointer w-4 h-4">
                        <span class="text-xs font-semibold text-slate-400 group-hover:text-slate-300 transition ml-2">Tetap Login di Sesi Ini</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-500 text-white font-black text-xs rounded-xl uppercase tracking-widest shadow-lg shadow-sky-950/40 transform hover:-translate-y-0.5 transition active:translate-y-0 duration-150 flex items-center justify-center gap-2">
                        <span>📲</span> Buka Terminal Scanner
                    </button>
                </div>
            </form>
        </div>

        <!-- NAVIGATION FOOTER -->
        <div class="text-center">
            <a href="{{ route('landingPage') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-300 uppercase tracking-wider transition duration-150">
                <span>&larr;</span> Kembali Ke Beranda Utama
            </a>
        </div>
    </main>

</body>
</html>