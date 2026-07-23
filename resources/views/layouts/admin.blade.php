<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#0F172A] border-r border-slate-800 text-white flex flex-col justify-between p-6 fixed h-full z-40 shadow-2xl">
            <div class="space-y-8">
                <div class="flex items-center space-x-3 group">
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-wider leading-none text-white">FUTSAL</span>
                        <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare Admin</span>
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 
                       {{ request()->routeIs('admin.dashboard') 
                          ? 'bg-[#E25E20] text-white shadow-md shadow-orange-950/50' 
                          : 'text-slate-400 hover:bg-[#152238] hover:text-white border border-transparent hover:border-slate-800' }}">
                        <span>📊 Overview</span>
                    </a>
                    
                    <a href="{{ route('admin.reservasi.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 
                       {{ request()->routeIs('admin.reservasi.index') 
                          ? 'bg-[#E25E20] text-white shadow-md shadow-orange-950/50' 
                          : 'text-slate-400 hover:bg-[#152238] hover:text-white border border-transparent hover:border-slate-800' }}">
                        <span>📅 Data Reservasi</span>
                    </a>
                    
                    <a href="{{ route('admin.lapangan.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 
                       {{ request()->routeIs('admin.lapangan.index') 
                          ? 'bg-[#E25E20] text-white shadow-md shadow-orange-950/50' 
                          : 'text-slate-400 hover:bg-[#152238] hover:text-white border border-transparent hover:border-slate-800' }}">
                        <span>🏟️ Kelola Lapangan</span>
                    </a>
                    
                    <a href="{{ route('admin.member.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 
                       {{ request()->routeIs('admin.member.index') 
                          ? 'bg-[#E25E20] text-white shadow-md shadow-orange-950/50' 
                          : 'text-slate-400 hover:bg-[#152238] hover:text-white border border-transparent hover:border-slate-800' }}">
                        <span>👥 Data Member</span>
                    </a>
                </nav>
            </div>

            <div class="pt-6 border-t border-slate-800">
                <span class="text-[10px] font-mono text-slate-600 font-bold tracking-wider">Futsal Mare © 2026</span>
            </div>
        </aside>

        <div class="flex-1 pl-64 flex flex-col">
            <header class="h-20 bg-[#0F172A]/80 backdrop-blur-md shadow-md border-b border-slate-800 flex items-center justify-between px-8 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Webhook Live Active
                    </span>
                </div>
                
                <div class="text-right">
                    <span class="text-xs font-black text-[#E25E20] block uppercase tracking-wide">Sistem Administrator</span>
                    <span class="text-[10px] text-slate-400 font-bold mt-0.5">Mustamin Tamsil</span>
                </div>
            </header>

            <main class="p-8 space-y-8 flex-1 bg-[#0B131F]">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- =================================================== -->
    <!-- ⚠️ TAMBAHKAN @stack('scripts') DI SINI AGAR CHART MUNCUL -->
    <!-- =================================================== -->
    @stack('scripts')

</body>
</html>