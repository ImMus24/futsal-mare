<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gatelog Gateway - Portal Admin Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 flex min-h-screen items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute inset-0 pointer-events-none opacity-[0.02] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#E25E20] rounded-full filter blur-[180px] opacity-10 pointer-events-none"></div>

    <main class="w-full max-w-md relative z-10 space-y-6">
        
        <div class="flex flex-col items-center text-center space-y-2">
            <div class="flex items-center space-x-3 bg-[#152238]/40 border border-slate-800 px-4 py-2 rounded-2xl backdrop-blur-sm shadow-xl">
                <span class="text-xl font-black text-white tracking-wider">FUTSAL</span>
                <span class="text-xs font-bold text-[#E25E20] tracking-[0.2em] uppercase">MARE</span>
            </div>
            <h1 class="text-base font-black uppercase text-slate-400 tracking-widest pt-2">Otorisasi Administrator</h1>
        </div>

        <div class="bg-[#152238]/80 backdrop-blur-md p-8 rounded-3xl border border-slate-800 shadow-2xl space-y-6">
            
            @if ($errors->any())
                <div class="p-4 bg-red-950/40 border border-red-900/40 text-red-400 rounded-2xl text-xs font-bold space-y-1 shadow-md">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <span>⚠️</span> {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider mb-2">Email Administrator</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@futsalmare.com" class="w-full bg-[#0B131F] border border-slate-800 focus:border-[#E25E20] focus:ring-0 rounded-xl px-4 py-3 text-sm font-semibold text-white placeholder-slate-600 transition">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Kata Sandi Akses</label>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-[#0B131F] border border-slate-800 focus:border-[#E25E20] focus:ring-0 rounded-xl px-4 py-3 text-sm font-bold text-white placeholder-slate-600 tracking-widest transition">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer select-none group">
                        <input type="checkbox" name="remember" class="rounded bg-[#0B131F] border-slate-800 text-[#E25E20] focus:ring-0 cursor-pointer w-4 h-4">
                        <span class="text-[11px] font-bold text-slate-400 group-hover:text-slate-300 transition ml-2">Ingat Sesi Perangkat</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl uppercase tracking-widest shadow-lg shadow-orange-950/40 transform hover:-translate-y-0.5 transition active:translate-y-0">
                        🔑 Masuk Ke Panel Utama
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('landingPage') }}" class="text-[11px] font-bold text-slate-500 hover:text-slate-300 uppercase tracking-wider transition">
                ← Kembali Ke Beranda Utama
            </a>
        </div>
    </main>

</body>
</html>