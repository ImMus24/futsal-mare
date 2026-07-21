@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare HQ (Refined Admin Dashboard v2)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

    .fm-scope {
        --color-primary:        #e2601f;
        --color-primary-dark:   #b8481a;
        --color-primary-light:  #ff8a4c;
        --color-primary-glow:   rgba(226, 96, 31, 0.25);
        --color-secondary:      #f5c518;
        --color-bg-main:        #0a0f16;
        --color-bg-card:        #121a24;
        --color-bg-raised:      #1a2432;
        --color-bg-hover:       #202b3a;
        --color-text-main:      #ffffff;
        --color-text-muted:     #94a3b8;
        --color-text-meta:      #5b6b81;
        --line:                 rgba(255, 255, 255, 0.07);
        --line-strong:          rgba(255, 255, 255, 0.14);
        --line-glow:            rgba(226, 96, 31, 0.35);
        --ease:                 cubic-bezier(.22, 1, .36, 1);
        --radius-lg:            18px;
        --radius-md:            12px;
        --radius-sm:            8px;

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.12); --success-border: rgba(34, 197, 94, 0.25);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.12); --pending-border: rgba(245, 158, 11, 0.25);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.12);  --danger-border:  rgba(239, 68, 68, 0.25);
        --info:    #3B82F6; --info-bg:    rgba(59, 130, 246, 0.12);  --info-border:    rgba(59, 130, 246, 0.25);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
        background: var(--color-bg-main);
    }

    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .02em; }
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
        font-weight: 600;
    }

    /* Background texture: faint grid + noise-free glow */
    .fm-scope .fm-bg-texture {
        background-image:
            radial-gradient(circle at 15% 0%, rgba(226, 96, 31, 0.06), transparent 45%),
            radial-gradient(circle at 100% 20%, rgba(59, 130, 246, 0.05), transparent 40%),
            linear-gradient(var(--line) 1px, transparent 1px),
            linear-gradient(90deg, var(--line) 1px, transparent 1px);
        background-size: auto, auto, 42px 42px, 42px 42px;
        background-position: 0 0, 0 0, -1px -1px, -1px -1px;
        opacity: .5;
    }

    /* Animations */
    @keyframes fm-pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .4; transform: scale(0.92); } }
    @keyframes fm-fade-up { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fm-shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .fm-scope .fm-live-pip { animation: fm-pulse 1.8s infinite ease-in-out; }
    .fm-scope .fm-animate-in { animation: fm-fade-up .45s var(--ease) both; }
    .fm-scope .fm-animate-in:nth-child(1) { animation-delay: .02s; }
    .fm-scope .fm-animate-in:nth-child(2) { animation-delay: .08s; }
    .fm-scope .fm-animate-in:nth-child(3) { animation-delay: .14s; }

    /* Cards */
    .fm-scope .fm-card {
        background: var(--color-bg-card);
        border: 1px solid var(--line);
        border-radius: var(--radius-lg);
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
        position: absolute; inset: 0 0 auto 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent-color, var(--color-primary)), transparent);
        opacity: 0; transition: opacity .3s ease;
    }
    .fm-scope .fm-metric-card:hover {
        transform: translateY(-4px);
        border-color: var(--line-glow);
        box-shadow: 0 16px 36px -12px rgba(0,0,0,.5), 0 0 0 1px var(--color-primary-glow) inset;
    }
    .fm-scope .fm-metric-card:hover::before { opacity: 1; }

    /* Nav chips */
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
        transform: translateY(-1px);
        box-shadow: 0 6px 16px var(--color-primary-glow);
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
    .fm-scope .fm-quick-link:hover .fm-arrow { transform: translateX(3px); }

    /* Table */
    .fm-scope .fm-table-row { transition: background-color .15s ease; }
    .fm-scope .fm-table-row:hover { background-color: rgba(255, 255, 255, 0.03); }

    .fm-scope .fm-avatar {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 11px;
        background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);
        flex-shrink: 0;
    }

    /* Search input */
    .fm-scope .fm-search-wrap { position: relative; }
    .fm-scope .fm-search-wrap svg {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        pointer-events: none; opacity: .55;
    }
    .fm-scope .fm-search-input {
        padding-left: 34px;
        background: var(--color-bg-main);
        border: 1px solid var(--line);
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .fm-scope .fm-search-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px var(--color-primary-glow);
        outline: none;
    }

    /* Scrollbar for table overflow */
    .fm-scope .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .fm-scope .overflow-x-auto::-webkit-scrollbar-track { background: transparent; }
    .fm-scope .overflow-x-auto::-webkit-scrollbar-thumb { background: var(--line-strong); border-radius: 10px; }

    /* Focus states */
    .fm-scope a:focus-visible, .fm-scope button:focus-visible, .fm-scope input:focus-visible {
        outline: 2px solid var(--color-primary);
        outline-offset: 2px;
        border-radius: 4px;
    }

    /* Pagination polish */
    .fm-scope nav[role="navigation"] { font-family: 'JetBrains Mono', monospace; font-size: 11px; }
</style>

<div class="fm-scope space-y-6 relative">
    <div class="fixed inset-0 fm-bg-texture pointer-events-none -z-10"></div>

    {{-- Notifikasi Toast Global --}}
    @include('partials.toast')

    <!-- HEADER NAVIGATION BAR -->
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-5" style="border-color: var(--line);">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center f-display text-xl shadow-xl transition-transform hover:scale-105"
                 style="background: linear-gradient(135deg, var(--color-primary-light), var(--color-primary-dark)); color: #fff; transform: rotate(-2deg); box-shadow: 0 4px 14px var(--color-primary-glow);">
                M
            </div>
            <div>
                <h1 class="f-display text-lg uppercase tracking-wider leading-none" style="color: var(--color-text-main);">Futsal Mare HQ</h1>
                <div class="f-mono text-[10px] font-semibold uppercase tracking-widest mt-1 flex items-center gap-1.5" style="color: var(--color-text-meta);">
                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--success);"></span>
                    Admin Management System
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3">
            <div class="hidden md:flex items-center gap-2 px-3 py-2 rounded-xl" style="background: var(--color-bg-card); border: 1px solid var(--line);">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center f-mono text-[10px] font-bold" style="background: var(--color-bg-raised); color: var(--color-primary);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="leading-tight">
                    <div class="text-[11px] font-bold" style="color: var(--color-text-main);">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="f-mono text-[9px] uppercase tracking-wider" style="color: var(--color-text-meta);">
                        {{ (auth()->user()->is_admin ?? 0) == 1 ? 'Super Admin' : 'Staff' }}
                    </div>
                </div>
            </div>

            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200"
               style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
               onmouseover="this.style.color='#fff'; this.style.borderColor='var(--line-strong)'"
               onmouseout="this.style.color='var(--color-text-muted)'; this.style.borderColor='var(--line)'">
               <span>🏠</span> <span class="hidden sm:inline">Beranda Utama</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wider transition-all duration-200 cursor-pointer"
                        style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger);"
                        onmouseover="this.style.background='var(--danger)'; this.style.color='#fff'"
                        onmouseout="this.style.background='var(--danger-bg)'; this.style.color='var(--danger)'">
                    <span>🚪</span> <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </header>

    <!-- HERO CONTROL BANNER -->
    <div class="relative p-6 sm:p-8 rounded-2xl shadow-2xl overflow-hidden fm-card fm-animate-in">
        <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, var(--color-primary-glow), transparent 70%);"></div>
        <div class="absolute -left-10 bottom-0 w-48 h-48 rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(59,130,246,0.08), transparent 70%);"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2.5">
                <span class="eyebrow">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--color-primary);"></span>
                    Konsol Kontrol Pusat
                </span>
                <h2 class="f-display text-2xl uppercase tracking-wide sm:text-3xl" style="color: var(--color-text-main);">
                    Ringkasan Eksekutif Arena
                </h2>
                <p class="text-xs font-medium max-w-2xl leading-relaxed" style="color: var(--color-text-muted);">
                    Pantau statistik utilisasi lapangan, pendapatan riil terverifikasi, dan manajemen kasta member secara terintegrasi dalam satu dasbor.
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <span class="f-mono text-[9px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wider" style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-meta);">
                        📆 {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <!-- QUICK NAVIGATION CHIPS -->
            <div class="flex flex-wrap lg:flex-nowrap gap-2 p-2 rounded-xl w-full lg:w-auto"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                <a href="{{ route('admin.reservasi.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-bold text-[10px] rounded-lg uppercase tracking-wider">
                    📋 Log Reservasi
                </a>
                <a href="{{ route('admin.lapangan.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-bold text-[10px] rounded-lg uppercase tracking-wider">
                    🏟️ Kelola Arena
                </a>
                <a href="{{ route('admin.member.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-bold text-[10px] rounded-lg uppercase tracking-wider">
                    👥 Data Member
                </a>
                @if(Route::has('admin.staff.scan'))
                    <a href="{{ route('admin.staff.scan') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-bold text-[10px] rounded-lg uppercase tracking-wider">
                        📷 QR Gate
                    </a>
                @endif
                @if(auth()->user()->is_admin == 1 && Route::has('admin.role.index'))
                    <a href="{{ route('admin.role.index') }}"
                       class="flex-1 lg:flex-none text-center px-4 py-2.5 font-bold text-[10px] rounded-lg uppercase tracking-wider transition-all"
                       style="background: rgba(245,197,24,0.12); border: 1px solid rgba(245,197,24,0.3); color: var(--color-secondary);"
                       onmouseover="this.style.background='var(--color-secondary)'; this.style.color='#0d131a'"
                       onmouseout="this.style.background='rgba(245,197,24,0.12)'; this.style.color='var(--color-secondary)'">
                       🛡️ Akses Admin
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- METRICS CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- METRIC 1: PENDAPATAN -->
        <div class="fm-metric-card p-6 shadow-xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--success);">
            <div class="space-y-2">
                <p class="f-mono text-[10px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Total Omset Lunas
                </p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--success);">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
                <span class="inline-flex text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"
                      style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                    ⚡ Terkonfirmasi System
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                🪙
            </div>
        </div>

        <!-- METRIC 2: JADWAL AKTIF -->
        <div class="fm-metric-card p-6 shadow-xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--info);">
            <div class="space-y-2">
                <p class="f-mono text-[10px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Slot Match Confirmed
                </p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--info);">
                    {{ $matchTerkonfirmasi }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Jadwal</span>
                </p>
                <span class="inline-flex text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"
                      style="background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border);">
                    📅 Siap Bertanding
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                ⚽
            </div>
        </div>

        <!-- METRIC 3: MEMBER -->
        <div class="fm-metric-card p-6 shadow-xl flex items-center justify-between fm-animate-in" style="--accent-color: var(--color-primary);">
            <div class="space-y-2">
                <p class="f-mono text-[10px] font-bold uppercase tracking-widest" style="color: var(--color-text-meta);">
                    Total Pelanggan Aktif
                </p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--color-text-main);">
                    {{ $totalMember }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Akun</span>
                </p>
                <span class="inline-flex text-[9px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md"
                      style="background: rgba(226,96,31,0.12); color: var(--color-primary); border: 1px solid rgba(226,96,31,0.25);">
                    🏆 Member Tier Loyalty
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-inner"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                👥
            </div>
        </div>
    </div>

    <!-- CHART & QUICK ACTIONS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- PERFORMANCE CHART -->
        <div class="lg:col-span-8 rounded-2xl p-6 shadow-2xl flex flex-col justify-between fm-card fm-animate-in">
            <div class="flex items-center justify-between border-b pb-4 mb-4" style="border-color: var(--line);">
                <div>
                    <h3 class="f-display text-sm uppercase tracking-wider" style="color: var(--color-text-main);">
                        Utilisasi Lapangan — 7 Hari Terakhir
                    </h3>
                    <p class="text-[10px] font-medium mt-0.5 uppercase tracking-wide" style="color: var(--color-text-meta);">
                        Akumulasi durasi booking (Jam) status Confirmed & Completed
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full fm-live-pip" style="background: var(--success);"></span>
                    <span class="f-mono text-[9px] font-bold uppercase tracking-wider" style="color: var(--success);">Live Analytics</span>
                </div>
            </div>

            <div class="relative w-full h-72 rounded-xl p-4"
                 style="background: var(--color-bg-main); border: 1px solid var(--line);">
                <canvas id="dashboardPerformanceChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- QUICK OPERATIONAL ACTIONS -->
        <div class="lg:col-span-4 rounded-2xl p-6 shadow-2xl flex flex-col justify-between space-y-4 fm-card fm-animate-in">
            <div class="border-b pb-4" style="border-color: var(--line);">
                <h3 class="f-display text-sm uppercase tracking-wider" style="color: var(--color-text-main);">
                    Aksi Cepat Gerbang
                </h3>
                <p class="text-[10px] font-medium mt-0.5 uppercase tracking-wide" style="color: var(--color-text-meta);">
                    Pintas penanganan operasional harian
                </p>
            </div>

            <div class="flex-1 space-y-2.5">
                <a href="{{ route('admin.reservasi.index', ['status' => 'Waiting Payment']) }}"
                   class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl group">
                    <span class="text-xs font-semibold flex items-center gap-2" style="color: var(--color-text-muted);">
                        <span>⏳</span> Tinjau Nota Pending
                    </span>
                    <span class="flex items-center gap-1.5 f-mono text-[9px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider"
                          style="background: var(--pending-bg); color: var(--pending); border: 1px solid var(--pending-border);">
                        Periksa <span class="fm-arrow">→</span>
                    </span>
                </a>

                <a href="{{ route('admin.reservasi.exportExcel') }}"
                   class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl group">
                    <span class="text-xs font-semibold flex items-center gap-2" style="color: var(--color-text-muted);">
                        <span>📊</span> Export Laporan (Excel)
                    </span>
                    <span class="flex items-center gap-1.5 f-mono text-[9px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider"
                          style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                        Unduh <span class="fm-arrow">→</span>
                    </span>
                </a>

                @if(Route::has('admin.staff.scan'))
                    <a href="{{ route('admin.staff.scan') }}"
                       class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl group">
                        <span class="text-xs font-semibold flex items-center gap-2" style="color: var(--color-text-muted);">
                            <span>📷</span> Scanner QR Gate Check-in
                        </span>
                        <span class="flex items-center gap-1.5 f-mono text-[9px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider"
                              style="background: var(--info-bg); color: var(--info); border: 1px solid var(--info-border);">
                            Terminal <span class="fm-arrow">→</span>
                        </span>
                    </a>
                @endif
            </div>

            <div class="p-3 rounded-xl text-[9px] font-bold text-center uppercase tracking-widest f-mono flex items-center justify-center gap-2"
                 style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-meta);">
                🔒 System Encrypted & Synchronized
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS TABLE -->
    <div class="rounded-2xl shadow-2xl overflow-hidden fm-card fm-animate-in">
        <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
             style="background: var(--color-bg-main); border-bottom: 1px solid var(--line);">
            <div>
                <h3 class="f-display text-sm uppercase tracking-wider flex items-center gap-2" style="color: var(--color-text-main);">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--color-primary);"></span>
                    Log Transaksi Terbaru
                </h3>
                <p class="text-[10px] font-medium mt-0.5 uppercase tracking-wide" style="color: var(--color-text-meta);">
                    Pantau entri reservasi dan riwayat pembayaran pelanggan secara aktual
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.reservasi.index') }}" class="fm-search-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round">
                        <circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="search" placeholder="Cari Kode / Member..."
                           class="fm-search-input f-mono text-[11px] pr-3.5 py-2 rounded-xl text-white placeholder-slate-500">
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-bold uppercase tracking-wider"
                        style="border-bottom: 1px solid var(--line); color: var(--color-text-meta); background: rgba(0, 0, 0, 0.2);">
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
                        {{-- Lapangan --}}
                        <td class="p-5">
                            <div class="font-bold text-sm uppercase tracking-tight" style="color: var(--color-text-main);">
                                {{ $reservasi->lapangan->nama_lapangan ?? 'Lapangan N/A' }}
                            </div>
                            <div class="f-mono text-[10px] font-semibold uppercase tracking-wider mt-0.5" style="color: var(--color-text-meta);">
                                🏟️ {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis Standard' }}
                            </div>
                        </td>

                        {{-- Order ID --}}
                        <td class="p-5 f-mono font-bold uppercase tracking-wider" style="color: var(--color-text-main);">
                            {{ $reservasi->nomor_reservasi ?? '#' . $reservasi->id }}
                        </td>

                        {{-- Customer --}}
                        <td class="p-5">
                            <div class="flex items-center gap-2.5">
                                <div class="fm-avatar">
                                    {{ strtoupper(substr($reservasi->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold tracking-tight" style="color: var(--color-text-main);">
                                        {{ $reservasi->user->name ?? 'Guest User' }}
                                    </div>
                                    @if($reservasi->user && $reservasi->user->membership)
                                        @php $tier = strtoupper($reservasi->user->membership->membership_type); @endphp
                                        @if($tier === 'GOLD')
                                            <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase"
                                                  style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);">
                                                🏆 Gold Member
                                            </span>
                                        @elseif($tier === 'SILVER')
                                            <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase"
                                                  style="background: rgba(148,163,184,0.15); color: #cbd5e1; border: 1px solid rgba(148,163,184,0.3);">
                                                🥈 Silver Member
                                            </span>
                                        @else
                                            <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase"
                                                  style="background: rgba(180,83,9,0.15); color: #d97706; border: 1px solid rgba(180,83,9,0.3);">
                                                🥉 Bronze Member
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-block mt-1 f-mono text-[8px] font-semibold tracking-widest px-2 py-0.5 rounded uppercase"
                                              style="background: var(--color-bg-main); color: var(--color-text-meta); border: 1px solid var(--line);">
                                            Non-Member
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Waktu Slot --}}
                        <td class="p-5">
                            <div class="font-medium" style="color: var(--color-text-main);">
                                {{ $reservasi->tanggal_main ? \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') : '-' }}
                            </div>
                            <div class="f-mono text-[10px] font-semibold mt-0.5 uppercase tracking-wide" style="color: var(--info);">
                                ⏱️ {{ substr($reservasi->jam_mulai ?? '00:00', 0, 5) }} - {{ substr($reservasi->jam_selesai ?? '00:00', 0, 5) }} WITA
                            </div>
                        </td>

                        {{-- Total Harga --}}
                        <td class="p-5 font-bold f-mono text-sm" style="color: var(--color-text-main);">
                            Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                        </td>

                        {{-- Status Badge --}}
                        <td class="p-5">
                            @if(in_array($reservasi->status, ['Confirmed', 'Completed']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider"
                                      style="background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--success);"></span> {{ $reservasi->status }}
                                </span>
                            @elseif($reservasi->status === 'Cancelled')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider"
                                      style="background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--danger);"></span> Batal
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider"
                                      style="background: var(--pending-bg); color: var(--pending); border: 1px solid var(--pending-border);">
                                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--pending);"></span> {{ $reservasi->status ?? 'Pending' }}
                                </span>
                            @endif
                        </td>

                        {{-- Action Button --}}
                        <td class="p-5 text-center">
                            <a href="{{ route('admin.reservasi.index', ['status' => $reservasi->status]) }}"
                               class="inline-flex items-center justify-center gap-1 px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all f-mono"
                               style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);"
                               onmouseover="this.style.color='#fff'; this.style.borderColor='var(--color-primary)'"
                               onmouseout="this.style.color='var(--color-text-muted)'; this.style.borderColor='var(--line)'">
                                Detail <span>→</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-14 text-center">
                            <div class="text-3xl mb-2 opacity-60">⚽</div>
                            <div class="font-bold uppercase tracking-wider text-xs" style="color: var(--color-text-meta);">
                                Belum ada log transaksi reservasi yang tercatat dalam sistem
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reservasis->hasPages())
            <div class="p-5 border-t" style="background: var(--color-bg-main); border-color: var(--line);">
                {{ $reservasis->links() }}
            </div>
        @endif
    </div>
</div>

<!-- CHART.JS INTEGRATION -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const canvas = document.getElementById('dashboardPerformanceChart');
        if (!canvas) return;

        const existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }

        const ctx = canvas.getContext('2d');
        const labelUtilisasi = @json($labelUtilisasi ?? []);
        const dataUtilisasi = @json($dataUtilisasi ?? []);

        const gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
        gradientFill.addColorStop(0, 'rgba(226, 96, 31, 0.30)');
        gradientFill.addColorStop(1, 'rgba(226, 96, 31, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelUtilisasi,
                datasets: [{
                    label: 'Utilisasi Arena (Jam)',
                    data: dataUtilisasi,
                    borderColor: '#e2601f',
                    backgroundColor: gradientFill,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#e2601f',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#e2601f',
                    pointHoverBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0d131a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2601f',
                        borderColor: 'rgba(255, 255, 255, 0.15)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        bodyFont: { family: 'JetBrains Mono', weight: 'bold', size: 12 },
                        titleFont: { family: 'Work Sans', weight: 'bold', size: 11 },
                        callbacks: {
                            label: function(context) {
                                return `Utilisasi: ${context.parsed.y} Jam Booking`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: {
                            color: '#64748b',
                            font: { size: 10, weight: '600', family: 'JetBrains Mono' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: {
                            color: '#64748b',
                            font: { size: 10, weight: '600', family: 'JetBrains Mono' },
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection