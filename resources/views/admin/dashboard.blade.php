@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare HQ (Elite Enterprise Admin v4)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

    .fm-scope {
        --color-primary:        #f97316;
        --color-primary-dark:   #c2410c;
        --color-primary-light:  #fb923c;
        --color-primary-glow:   rgba(249, 115, 22, 0.25);
        --color-secondary:      #fbbf24;
        --color-bg-main:        #05070a;
        --color-bg-card:        #0b111e;
        --color-bg-raised:      #131c31;
        --color-bg-hover:       #1e293b;
        --color-text-main:      #ffffff;
        --color-text-muted:     #94a3b8;
        --color-text-meta:      #64748b;
        --line:                 rgba(255, 255, 255, 0.08);
        --line-strong:          rgba(255, 255, 255, 0.16);
        --line-glow:            rgba(249, 115, 22, 0.4);
        --ease:                 cubic-bezier(.16, 1, .3, 1);
        --radius-lg:            20px;
        --radius-md:            14px;
        --radius-sm:            10px;

        --success: #22c55e; --success-bg: rgba(34, 197, 94, 0.12); --success-border: rgba(34, 197, 94, 0.25);
        --pending: #fbbf24; --pending-bg: rgba(251, 191, 36, 0.12); --pending-border: rgba(251, 191, 36, 0.25);
        --danger:  #ef4444; --danger-bg:  rgba(239, 68, 68, 0.12);  --danger-border:  rgba(239, 68, 68, 0.25);
        --info:    #3b82f6; --info-bg:    rgba(59, 130, 246, 0.12);  --info-border:    rgba(59, 130, 246, 0.25);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
        background: var(--color-bg-main);
    }

    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .03em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }

    .fm-scope .eyebrow {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: var(--color-primary);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
    }

    .fm-scope .fm-bg-texture {
        background-image:
            radial-gradient(circle at 10% 5%, rgba(249, 115, 22, 0.07), transparent 45%),
            radial-gradient(circle at 90% 15%, rgba(59, 130, 246, 0.05), transparent 40%),
            linear-gradient(var(--line) 1px, transparent 1px),
            linear-gradient(90deg, var(--line) 1px, transparent 1px);
        background-size: auto, auto, 48px 48px, 48px 48px;
        background-position: 0 0, 0 0, -1px -1px, -1px -1px;
        opacity: .6;
    }

    @keyframes fm-pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .4; transform: scale(0.9); } }
    @keyframes fm-fade-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .fm-scope .fm-live-pip { animation: fm-pulse 2s infinite ease-in-out; }
    .fm-scope .fm-animate-in { animation: fm-fade-up .5s var(--ease) both; }
    .fm-scope .fm-animate-in:nth-child(1) { animation-delay: .03s; }
    .fm-scope .fm-animate-in:nth-child(2) { animation-delay: .09s; }
    .fm-scope .fm-animate-in:nth-child(3) { animation-delay: .15s; }

    .fm-scope .fm-card {
        background: var(--color-bg-card);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(16px);
        transition: border-color .25s ease, transform .25s var(--ease), box-shadow .25s ease;
    }
    .fm-scope .fm-card:hover { border-color: var(--line-strong); }

    .fm-scope .fm-metric-card {
        position: relative;
        background: radial-gradient(130% 130% at 0% 0%, var(--color-bg-raised) 0%, var(--color-bg-card) 100%);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all .3s var(--ease);
    }
    .fm-scope .fm-metric-card::before {
        content: '';
        position: absolute; inset: 0 0 auto 0; height: 3px;
        background: linear-gradient(90deg, transparent, var(--accent-color, var(--color-primary)), transparent);
        opacity: 0; transition: opacity .3s ease;
    }
    .fm-scope .fm-metric-card:hover {
        transform: translateY(-5px);
        border-color: var(--line-glow);
        box-shadow: 0 20px 40px -15px rgba(0,0,0,.6), 0 0 0 1px var(--color-primary-glow) inset;
    }
    .fm-scope .fm-metric-card:hover::before { opacity: 1; }

    .fm-scope .fm-nav-chip {
        background: var(--color-bg-raised);
        color: var(--color-text-muted);
        border: 1px solid var(--line);
        transition: all .2s var(--ease);
        white-space: nowrap;
    }
    .fm-scope .fm-nav-chip:hover {
        background: var(--color-primary);
        color: #ffffff;
        border-color: var(--color-primary);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px var(--color-primary-glow);
    }

    .fm-scope .fm-nav-chip-alt {
        background: rgba(251,191,36,0.12);
        border: 1px solid rgba(251,191,36,0.3);
        color: var(--color-secondary);
        transition: all .2s var(--ease);
        white-space: nowrap;
    }
    .fm-scope .fm-nav-chip-alt:hover {
        background: var(--color-secondary);
        color: #0d131a;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(251,191,36,0.25);
    }

    /* Unified header action buttons (Beranda / Keluar) */
    .fm-scope .fm-header-btn {
        background: var(--color-bg-card);
        border: 1px solid var(--line);
        color: var(--color-text-muted);
        transition: all .2s var(--ease);
    }
    .fm-scope .fm-header-btn:hover {
        color: #ffffff;
        border-color: var(--line-strong);
        background: var(--color-bg-hover);
    }
    .fm-scope .fm-header-btn.is-danger {
        background: var(--danger-bg);
        border-color: var(--danger-border);
        color: var(--danger);
    }
    .fm-scope .fm-header-btn.is-danger:hover {
        background: var(--danger);
        color: #ffffff;
        border-color: var(--danger);
    }

    .fm-scope .fm-quick-link {
        background: var(--color-bg-main);
        border: 1px solid var(--line);
        transition: all .2s ease;
    }
    .fm-scope .fm-quick-link:hover {
        background: var(--color-bg-raised);
        border-color: var(--line-strong);
        transform: translateX(4px);
    }
    .fm-scope .fm-quick-link .fm-arrow { transition: transform .2s ease; }
    .fm-scope .fm-quick-link:hover .fm-arrow { transform: translateX(4px); }

    .fm-scope .fm-table-row { transition: background-color .15s ease; }
    .fm-scope .fm-table-row:hover { background-color: rgba(255, 255, 255, 0.025); }

    /* Unified table row action button (Kelola) */
    .fm-scope .fm-table-action {
        background: var(--color-bg-main);
        border: 1px solid var(--line);
        color: var(--color-text-muted);
        transition: all .2s ease;
    }
    .fm-scope .fm-table-action:hover {
        background: var(--color-primary);
        color: #ffffff;
        border-color: var(--color-primary);
    }

    .fm-scope .fm-avatar {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 12px;
        background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);
        flex-shrink: 0;
    }

    .fm-scope .fm-search-wrap { position: relative; }
    .fm-scope .fm-search-wrap svg {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        pointer-events: none; opacity: .6;
    }
    .fm-scope .fm-search-input {
        padding-left: 38px;
        background: var(--color-bg-main);
        border: 1px solid var(--line);
        border-radius: var(--radius-sm);
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .fm-scope .fm-search-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px var(--color-primary-glow);
        outline: none;
    }

    .fm-scope .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .fm-scope .overflow-x-auto::-webkit-scrollbar-track { background: transparent; }
    .fm-scope .overflow-x-auto::-webkit-scrollbar-thumb { background: var(--line-strong); border-radius: 10px; }
</style>

<div class="fm-scope space-y-8 relative pb-12">
    <div class="fixed inset-0 fm-bg-texture pointer-events-none -z-10"></div>

    @include('partials.toast')

    <!-- HEADER NAVIGATION BAR -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 border-b pb-6" style="border-color: var(--line);">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center f-display text-2xl shadow-2xl transition-transform hover:scale-105"
                 style="background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary-dark)); color: #fff; transform: rotate(-3deg); box-shadow: 0 6px 20px var(--color-primary-glow);">
                M
            </div>
            <div>
                <h1 class="f-display text-xl uppercase tracking-wider leading-none" style="color: var(--color-text-main);">Futsal Mare HQ</h1>
                <div class="f-mono text-[10px] font-bold uppercase tracking-widest mt-1.5 flex items-center gap-2" style="color: var(--color-text-meta);">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--success);"></span>
                    Enterprise Admin Terminal v4.0
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3.5">
            <div class="hidden md:flex items-center gap-2.5 px-3.5 py-2 rounded-xl" style="background: var(--color-bg-card); border: 1px solid var(--line);">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center f-mono text-[11px] font-bold shadow-inner" style="background: var(--color-bg-raised); color: var(--color-primary);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="leading-tight">
                    <div class="text-xs font-bold" style="color: var(--color-text-main);">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="f-mono text-[9px] uppercase tracking-wider mt-0.5" style="color: var(--color-text-meta);">
                        {{ (auth()->user()->is_admin ?? 0) == 1 ? 'Super Administrator' : 'System Staff' }}
                    </div>
                </div>
            </div>

            <a href="{{ url('/') }}"
               class="fm-header-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                <span>🏠</span> <span class="hidden sm:inline">Beranda</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="fm-header-btn is-danger inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl uppercase tracking-wider cursor-pointer">
                    <span>🚪</span> <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <!-- HERO CONTROL BANNER -->
    <div class="relative p-8 rounded-3xl shadow-2xl overflow-hidden fm-card fm-animate-in">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full pointer-events-none filter blur-2xl"
             style="background: radial-gradient(circle, var(--color-primary-glow), transparent 70%);"></div>
        <div class="absolute -left-16 bottom-0 w-64 h-64 rounded-full pointer-events-none filter blur-2xl"
             style="background: radial-gradient(circle, rgba(59,130,246,0.08), transparent 70%);"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <span class="eyebrow">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--color-primary);"></span>
                    Pusat Komando Operasional Arena
                </span>
                <h2 class="f-display text-3xl uppercase tracking-wide sm:text-4xl" style="color: var(--color-text-main);">
                    Ringkasan Eksekutif & Kinerja
                </h2>
                <p class="text-xs font-medium max-w-2xl leading-relaxed" style="color: var(--color-text-muted);">
                    Pantau analitik operasional lapangan secara real-time, validasi transaksi finansial terverifikasi, dan kelola ekosistem member secara terpadu.
                </p>
                <div class="flex items-center gap-2.5 pt-1">
                    <span class="f-mono text-[10px] font-semibold px-3 py-1.5 rounded-lg uppercase tracking-wider" style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-meta);">
                        📆 {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="f-mono text-[10px] font-semibold px-3 py-1.5 rounded-lg uppercase tracking-wider" style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--success);">
                        🟢 System Online
                    </span>
                </div>
            </div>

            <!-- QUICK NAVIGATION CHIPS -->
            <div class="flex flex-wrap lg:flex-nowrap gap-2.5 p-2.5 rounded-2xl w-full lg:w-auto"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                <a href="{{ route('admin.reservasi.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-3 font-bold text-[11px] rounded-xl uppercase tracking-wider">
                    📋 Log Reservasi
                </a>
                <a href="{{ route('admin.lapangan.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-3 font-bold text-[11px] rounded-xl uppercase tracking-wider">
                    🏟️ Kelola Arena
                </a>
                <a href="{{ route('admin.member.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-3 font-bold text-[11px] rounded-xl uppercase tracking-wider">
                    👥 Data Member
                </a>
                @if(auth()->user()->is_admin == 1 && Route::has('admin.role.index'))
                    <a href="{{ route('admin.role.index') }}"
                       class="fm-nav-chip-alt flex-1 lg:flex-none text-center px-4 py-3 font-bold text-[11px] rounded-xl uppercase tracking-wider">
                        🛡️ Akses Admin
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- METRICS CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- METRIC 1 -->
        <div class="fm-metric-card p-6 shadow-2xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--success);">
            <div class="space-y-2">
                <p class="f-mono text-[11px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Total Omset Lunas
                </p>
                <p class="f-mono text-3xl font-bold tracking-tight" style="color: var(--success);">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
                <span class="inline-flex text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg"
                      style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                    ⚡ Terverifikasi Otomatis
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                🪙
            </div>
        </div>

        <!-- METRIC 2 -->
        <div class="fm-metric-card p-6 shadow-2xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--info);">
            <div class="space-y-2">
                <p class="f-mono text-[11px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Slot Match Confirmed
                </p>
                <p class="f-mono text-3xl font-bold tracking-tight" style="color: var(--info);">
                    {{ $matchTerkonfirmasi }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Jadwal</span>
                </p>
                <span class="inline-flex text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg"
                      style="background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border);">
                    📅 Siap Bertanding
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                ⚽
            </div>
        </div>

        <!-- METRIC 3 -->
        <div class="fm-metric-card p-6 shadow-2xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--color-primary);">
            <div class="space-y-2">
                <p class="f-mono text-[11px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Total Pelanggan Aktif
                </p>
                <p class="f-mono text-3xl font-bold tracking-tight" style="color: var(--color-text-main);">
                    {{ $totalMember }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Akun</span>
                </p>
                <span class="inline-flex text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg"
                      style="background: rgba(249,115,22,0.12); color: var(--color-primary); border: 1px solid rgba(249,115,22,0.25);">
                    🏆 Member Tier Loyalty
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                👥
            </div>
        </div>
    </div>

    <!-- CHART & QUICK ACTIONS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 rounded-3xl p-7 shadow-2xl flex flex-col justify-between fm-card fm-animate-in">
            <div class="flex items-center justify-between border-b pb-5 mb-5" style="border-color: var(--line);">
                <div>
                    <h3 class="f-display text-base uppercase tracking-wider" style="color: var(--color-text-main);">
                        Utilisasi Lapangan — 7 Hari Terakhir
                    </h3>
                    <p class="text-[11px] font-medium mt-1 uppercase tracking-wide" style="color: var(--color-text-meta);">
                        Akumulasi durasi booking (Jam) status Confirmed & Completed
                    </p>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg" style="background: var(--color-bg-main); border: 1px solid var(--line);">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--success);"></span>
                    <span class="f-mono text-[10px] font-bold uppercase tracking-wider" style="color: var(--success);">Live Analytics</span>
                </div>
            </div>

            <!-- CONTAINER DENGAN TINGGI ABSOLUT AGAR CHART.JS MERENDER DENGAN SEMPURNA -->
            <div class="relative w-full rounded-2xl p-4"
                 style="background: var(--color-bg-main); border: 1px solid var(--line); height: 320px;">
                <canvas id="dashboardPerformanceChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>

        <!-- QUICK OPERATIONAL ACTIONS -->
        <div class="lg:col-span-4 rounded-3xl p-7 shadow-2xl flex flex-col justify-between space-y-5 fm-card fm-animate-in">
            <div class="border-b pb-5" style="border-color: var(--line);">
                <h3 class="f-display text-base uppercase tracking-wider" style="color: var(--color-text-main);">
                    Aksi Cepat Gerbang
                </h3>
                <p class="text-[11px] font-medium mt-1 uppercase tracking-wide" style="color: var(--color-text-meta);">
                    Pintas penanganan operasional harian
                </p>
            </div>

            <div class="flex-1 space-y-3">
                <a href="{{ route('admin.reservasi.index', ['status' => 'Waiting Payment']) }}"
                   class="fm-quick-link flex items-center justify-between p-4 rounded-2xl group">
                    <span class="text-xs font-semibold flex items-center gap-3" style="color: var(--color-text-muted);">
                        <span class="text-base">⏳</span> Tinjau Nota Pending
                    </span>
                    <span class="flex items-center gap-1.5 f-mono text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider"
                          style="background: var(--pending-bg); color: var(--pending); border: 1px solid var(--pending-border);">
                        Periksa <span class="fm-arrow">→</span>
                    </span>
                </a>

                <a href="{{ route('admin.reservasi.exportExcel') }}"
                   class="fm-quick-link flex items-center justify-between p-4 rounded-2xl group">
                    <span class="text-xs font-semibold flex items-center gap-3" style="color: var(--color-text-muted);">
                        <span class="text-base">📊</span> Export Laporan (Excel)
                    </span>
                    <span class="flex items-center gap-1.5 f-mono text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider"
                          style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                        Unduh <span class="fm-arrow">→</span>
                    </span>
                </a>

                <a href="{{ route('admin.member.index') }}"
                   class="fm-quick-link flex items-center justify-between p-4 rounded-2xl group">
                    <span class="text-xs font-semibold flex items-center gap-3" style="color: var(--color-text-muted);">
                        <span class="text-base">👥</span> Kelola Data Member
                    </span>
                    <span class="flex items-center gap-1.5 f-mono text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider"
                          style="background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border);">
                        Buka <span class="fm-arrow">→</span>
                    </span>
                </a>
            </div>

            <div class="p-3.5 rounded-xl text-[10px] font-bold text-center uppercase tracking-widest f-mono flex items-center justify-center gap-2"
                 style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-meta);">
                🔒 System Encrypted & Synchronized
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS TABLE -->
    <div class="rounded-3xl shadow-2xl overflow-hidden fm-card fm-animate-in">
        <div class="p-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5"
             style="background: var(--color-bg-main); border-bottom: 1px solid var(--line);">
            <div>
                <h3 class="f-display text-base uppercase tracking-wider flex items-center gap-3" style="color: var(--color-text-main);">
                    <span class="w-2.5 h-2.5 rounded-full fm-live-pip" style="background: var(--color-primary);"></span>
                    Log Transaksi Terbaru
                </h3>
                <p class="text-[11px] font-medium mt-1 uppercase tracking-wide" style="color: var(--color-text-meta);">
                    Pantau entri reservasi dan riwayat pembayaran pelanggan secara aktual
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.reservasi.index') }}" class="fm-search-wrap w-full sm:w-72">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" placeholder="Cari Kode / Member..."
                           class="fm-search-input f-mono text-xs w-full py-2.5 pr-4 text-white placeholder-slate-500">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-[11px] font-bold uppercase tracking-wider"
                        style="border-bottom: 1px solid var(--line); color: var(--color-text-meta); background: rgba(0, 0, 0, 0.25);">
                        <th class="p-5">Detail Arena</th>
                        <th class="p-5">Kode Order</th>
                        <th class="p-5">Pelanggan & Tier</th>
                        <th class="p-5">Jadwal Main</th>
                        <th class="p-5">Total Harga</th>
                        <th class="p-5">Status Order</th>
                        <th class="p-5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-xs font-medium" style="border-color: var(--line); color: var(--color-text-muted);">
                    @forelse($reservasis as $reservasi)
                    <tr class="fm-table-row">
                        <td class="p-5">
                            <div class="font-bold text-sm uppercase tracking-tight" style="color: var(--color-text-main);">
                                {{ $reservasi->lapangan->nama_lapangan ?? 'Lapangan N/A' }}
                            </div>
                            <div class="f-mono text-[10px] font-bold uppercase tracking-wider mt-1" style="color: var(--color-text-meta);">
                                🏟️ {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis Standard' }}
                            </div>
                        </td>

                        <td class="p-5 f-mono font-bold uppercase tracking-wider" style="color: var(--color-text-main);">
                            {{ $reservasi->nomor_reservasi ?? '#' . $reservasi->id }}
                        </td>

                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="fm-avatar">
                                    {{ strtoupper(substr($reservasi->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-xs font-bold tracking-tight" style="color: var(--color-text-main);">
                                        {{ $reservasi->user->name ?? 'Guest User' }}
                                    </div>
                                    @if($reservasi->user && $reservasi->user->membership)
                                        @php $tier = strtoupper($reservasi->user->membership->membership_type); @endphp
                                        @if($tier === 'GOLD')
                                            <span class="inline-block mt-1 f-mono text-[9px] font-bold tracking-widest px-2.5 py-0.5 rounded uppercase"
                                                  style="background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.3);">
                                                🏆 Gold Member
                                            </span>
                                        @elseif($tier === 'SILVER')
                                            <span class="inline-block mt-1 f-mono text-[9px] font-bold tracking-widest px-2.5 py-0.5 rounded uppercase"
                                                  style="background: rgba(148,163,184,0.15); color: #cbd5e1; border: 1px solid rgba(148,163,184,0.3);">
                                                🥈 Silver Member
                                            </span>
                                        @else
                                            <span class="inline-block mt-1 f-mono text-[9px] font-bold tracking-widest px-2.5 py-0.5 rounded uppercase"
                                                  style="background: rgba(194,65,12,0.15); color: #ea580c; border: 1px solid rgba(194,65,12,0.3);">
                                                🥉 Bronze Member
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-block mt-1 f-mono text-[9px] font-semibold tracking-widest px-2 py-0.5 rounded uppercase"
                                              style="background: var(--color-bg-main); color: var(--color-text-meta); border: 1px solid var(--line);">
                                            Non-Member
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="p-5">
                            <div class="font-medium" style="color: var(--color-text-main);">
                                {{ $reservasi->tanggal_main ? \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') : '-' }}
                            </div>
                            <div class="f-mono text-[10px] font-bold mt-1 uppercase tracking-wide" style="color: var(--info);">
                                ⏱️ {{ substr($reservasi->jam_mulai ?? '00:00', 0, 5) }} - {{ substr($reservasi->jam_selesai ?? '00:00', 0, 5) }} WITA
                            </div>
                        </td>

                        <td class="p-5 font-bold f-mono text-sm" style="color: var(--color-text-main);">
                            Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                        </td>

                        <td class="p-5">
                            @if(in_array($reservasi->status, ['Confirmed', 'Completed']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                      style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--success);"></span> {{ $reservasi->status }}
                                </span>
                            @elseif($reservasi->status === 'Waiting Payment')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                      style="background: var(--pending-bg); color: var(--pending); border: 1px solid var(--pending-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--pending);"></span> Menunggu Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                      style="background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--danger);"></span> {{ $reservasi->status }}
                                </span>
                            @endif
                        </td>

                        <td class="p-5 text-center">
                            <a href="{{ route('admin.reservasi.index', ['search' => $reservasi->nomor_reservasi]) }}"
                               class="fm-table-action inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider">
                                Kelola
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-14 text-center text-xs font-mono uppercase tracking-widest" style="color: var(--color-text-meta);">
                            Tidak ada data transaksi atau reservasi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('dashboardPerformanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Durasi Main (Jam)',
                        data: [14, 21, 18, 25, 32, 42, 48],
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#f97316',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.04)' },
                            ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.04)' },
                            ticks: { color: '#64748b', font: { family: 'JetBrains Mono', size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush

@endsection