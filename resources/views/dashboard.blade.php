<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Futsal Mare</title>
    
    <!-- Fonts & Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=JetBrains+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        :root {
            --ink: #05070a;
            --surface: #0b111e;
            --surface-2: #131c31;
            --surface-3: #1e293b;
            --turf: #f97316;
            --turf-dark: #ea580c;
            --turf-glow: rgba(249, 115, 22, 0.25);
            --floodlight: #fbbf24;
            --line: #f8fafc;
            --muted: #94a3b8;
            --muted-2: #64748b;
            --radius: 18px;
            --display: 'Anton', sans-serif;
            --body: 'Plus Jakarta Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; }
        
        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(249, 115, 22, 0.05) 0%, transparent 45%),
                radial-gradient(circle at 90% 90%, rgba(251, 191, 36, 0.03) 0%, transparent 45%);
            background-attachment: fixed;
        }

        h1, h2, h3, h4 { font-family: var(--display); letter-spacing: .03em; text-transform: uppercase; }

        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible {
            outline: 2px solid var(--turf);
            outline-offset: 2px;
        }

        .sr-only {
            position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
            overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0;
        }

        /* Modern UI Buttons */
        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; transition: all .2s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: var(--body); text-transform: uppercase; letter-spacing: .04em;
        }
        .btn-ui:active { transform: scale(.97); }
        .btn-ui-primary { 
            background: linear-gradient(135deg, var(--turf), var(--turf-dark)); 
            color: white; 
            box-shadow: 0 4px 20px var(--turf-glow); 
        }
        .btn-ui-primary:hover { 
            box-shadow: 0 6px 24px rgba(249, 115, 22, 0.45); 
            filter: brightness(1.1);
        }
        .btn-ui-ghost { background: transparent; border-color: rgba(248, 250, 252, 0.12); color: var(--line); }
        .btn-ui-ghost:hover { border-color: var(--line); background: rgba(248, 250, 252, 0.06); }
        .btn-ui-danger { background: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.25); color: #f87171; }
        .btn-ui-danger:hover { background: rgba(239, 68, 68, 0.22); border-color: rgba(239, 68, 68, 0.4); }
        .btn-ui-sm { padding: 7px 14px; font-size: 11px; border-radius: 8px; }

        /* Modern Table Components */
        table.brutal-data { width: 100%; border-collapse: separate; border-spacing: 0; }
        table.brutal-data th { 
            text-align: left; font-family: var(--mono); font-size: 11px; color: var(--muted-2); 
            text-transform: uppercase; letter-spacing: .06em; padding: 16px 20px; 
            border-bottom: 1px solid rgba(248, 250, 252, 0.08); background: rgba(11, 17, 30, 0.9);
            backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 10;
        }
        table.brutal-data td { padding: 18px 20px; border-bottom: 1px solid rgba(248, 250, 252, 0.04); font-size: 13px; font-weight: 500; transition: background .15s ease; }
        table.brutal-data tr:hover td { background: rgba(248, 250, 252, 0.02); }
        table.brutal-data tr.row-hidden { display: none; }

        /* Brutal Badges */
        .badge-brutal { font-family: var(--mono); font-size: 11px; padding: 5px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
        .badge-brutal-pending { background: rgba(251, 191, 36, 0.12); color: var(--floodlight); border: 1px solid rgba(251, 191, 36, 0.25); }
        .badge-brutal-confirmed { background: rgba(34, 197, 94, 0.12); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.25); }
        .badge-brutal-cancelled { background: rgba(239, 68, 68, 0.12); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.25); }

        /* Progress Bar */
        .tier-track { width: 100%; height: 8px; background: var(--ink); border-radius: 999px; overflow: hidden; border: 1px solid rgba(248, 250, 252, 0.08); }
        .tier-fill { height: 100%; background: linear-gradient(90deg, var(--turf), var(--floodlight)); border-radius: 999px; transition: width 0.8s cubic-bezier(0.16, 1, 0.3, 1); }

        /* Custom Inputs & Selects */
        .search-input, .select-input {
            background: var(--ink); border: 1px solid rgba(248, 250, 252, 0.12); color: var(--line);
            font-family: var(--body); font-size: 13px; padding: 10px 16px 10px 38px; border-radius: 10px;
            width: 100%; transition: all .2s ease;
        }
        .select-input { padding-left: 14px; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%238b97a6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 36px; }
        .search-input:focus, .select-input:focus { border-color: var(--turf); box-shadow: 0 0 0 3px var(--turf-glow); }
        .search-wrap { position: relative; width: 100%; max-width: 260px; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--muted-2); pointer-events: none; }

        /* Glassmorphism Card Effect */
        .card-glass {
            background: linear-gradient(135deg, rgba(11, 17, 30, 0.85), rgba(11, 17, 30, 0.5));
            backdrop-filter: blur(16px);
            border: 1px solid rgba(248, 250, 252, 0.08);
            border-radius: var(--radius);
        }
        
        /* Modal Backdrop */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(5, 7, 10, 0.85); backdrop-filter: blur(8px);
            z-index: 100; display: flex; align-items: center; justify-content: center; opacity: 0;
            pointer-events: none; transition: opacity .25s ease;
        }
        .modal-backdrop.active { opacity: 1; pointer-events: auto; }
        .modal-card {
            background: var(--surface); border: 1px solid rgba(248, 250, 252, 0.12);
            border-radius: 20px; width: 100%; max-width: 480px; padding: 28px;
            transform: scale(0.95); transition: transform .25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-backdrop.active .modal-card { transform: scale(1); }
    </style>
</head>
<body class="selection:bg-[var(--turf)] selection:text-white">

    {{-- Notifikasi Global Toast --}}
    @include('partials.toast')

    <!-- NAVIGATION BAR -->
    <header class="sticky top-0 z-50 bg-[var(--ink)]/90 backdrop-blur-md border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('landingPage') }}" class="flex items-center gap-3 font-display text-2xl text-white no-underline tracking-wide hover:opacity-90 transition">
                <span class="w-3.5 h-3.5 bg-[var(--turf)] rounded-sm rotate-45 shadow-[0_0_12px_var(--turf)]"></span>
                FUTSAL MARE
            </a>

            <div class="flex items-center gap-6">
                <div class="hidden sm:flex flex-col text-right border-r border-slate-800 pr-5">
                    <span class="font-mono text-[10px] text-[var(--turf)] font-bold uppercase tracking-wider">Sesi Member Aktif</span>
                    <span class="text-sm font-bold text-white tracking-wide mt-0.5">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- MAIN DASHBOARD CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

        @php
            $tierColors = [
                'Gold' => 'from-amber-400 to-yellow-500 text-slate-950 border-amber-300 shadow-[0_0_15px_rgba(245,158,11,0.3)]',
                'Silver' => 'from-slate-300 to-slate-400 text-slate-950 border-slate-200',
                'Bronze' => 'from-amber-800 to-amber-700 text-white border-amber-600'
            ];
            $tierThresholds = ['Bronze' => 0, 'Silver' => 100, 'Gold' => 300];
            $currentTier = $membership->membership_type ?? 'Bronze';
            $nextTier = $currentTier === 'Gold' ? null : ($currentTier === 'Silver' ? 'Gold' : 'Silver');
            $progressPercent = $nextTier
                ? min(100, round(($membership->points / $tierThresholds[$nextTier]) * 100))
                : 100;
            $pointsToGo = $nextTier ? max(0, $tierThresholds[$nextTier] - $membership->points) : 0;
        @endphp

        <!-- 1. USER PROFILE & LOYALTY TIER HERO -->
        <div class="card-glass p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 relative overflow-hidden shadow-2xl">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-[var(--turf)] rounded-full filter blur-[120px] opacity-15 pointer-events-none" aria-hidden="true"></div>

            <div class="relative z-10 space-y-6 flex-1">
                <div>
                    <h1 class="text-3xl sm:text-4xl text-white leading-tight">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! ⚽</h1>
                    <p class="text-[var(--muted)] text-sm font-medium mt-2 max-w-2xl">Kelola reservasi arena futsal, pantau status transaksi, dan kumpulkan poin loyalitas untuk membuka diskon sewa lapangan eksklusif.</p>
                </div>

                <div class="flex flex-col gap-3 max-w-md">
                    <div class="inline-flex items-center gap-3 bg-[var(--ink)] border border-slate-800/80 rounded-xl p-3 w-fit shadow-inner">
                        <span class="bg-gradient-to-r {{ $tierColors[$currentTier] ?? $tierColors['Bronze'] }} font-mono text-[11px] font-bold uppercase px-3 py-1 rounded-md tracking-wider border">
                            🏆 Tier {{ $currentTier }}
                        </span>
                        <span class="font-mono text-sm text-[var(--line)] font-bold">
                            ⭐ {{ number_format($membership->points ?? 0) }} Poin
                        </span>
                    </div>

                    @if($nextTier)
                        <div class="px-0.5 space-y-1.5">
                            <div class="flex justify-between items-center text-[11px] font-mono">
                                <span class="text-[var(--muted-2)] uppercase tracking-wider">Progres ke Tier {{ $nextTier }}</span>
                                <span class="text-[var(--muted)] font-bold">{{ $pointsToGo }} poin lagi</span>
                            </div>
                            <div class="tier-track" role="progressbar" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="tier-fill" style="width: {{ $progressPercent }}%;"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-[11px] font-mono text-amber-400 font-semibold uppercase tracking-wider">🌟 Tier Maksimal Dicapai! Nikmati Benefit Utama.</p>
                    @endif
                </div>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row lg:flex-col gap-3 w-full lg:w-auto">
                <a href="{{ route('landingPage') }}" class="btn-ui btn-ui-primary w-full text-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Sewa Lapangan
                </a>
            </div>
        </div>

        <!-- 2. REAL-TIME CORE METRICS INDEX -->
        @php
            $totalBooking = $reservasis->count();
            $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
            $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
            
            $upcomingMatch = $reservasis->where('status', 'Confirmed')
                ->where('tanggal_main', '>=', now()->toDateString())
                ->sortBy('tanggal_main')
                ->first();
        @endphp
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="card-glass p-6 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="font-mono text-[11px] text-[var(--muted-2)] uppercase tracking-wider">Total Reservasi</p>
                    <p class="font-mono text-3xl text-white font-bold leading-none">{{ $totalBooking }} <span class="text-xs font-body text-[var(--muted)] font-semibold">Slot</span></p>
                </div>
                <div class="p-3.5 bg-[var(--ink)] border border-slate-800 rounded-xl text-xl">📅</div>
            </div>

            <div class="card-glass p-6 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="font-mono text-[11px] text-[var(--muted-2)] uppercase tracking-wider">Match Terkonfirmasi</p>
                    <p class="font-mono text-3xl text-emerald-400 font-bold leading-none">{{ $lunasBooking }} <span class="text-xs font-body text-[var(--muted)] font-semibold">Match</span></p>
                </div>
                <div class="p-3.5 bg-[var(--ink)] border border-slate-800 rounded-xl text-xl">✅</div>
            </div>

            <div class="card-glass p-6 flex items-center justify-between">
                <div class="space-y-1">
                    <p class="font-mono text-[11px] text-[var(--muted-2)] uppercase tracking-wider">Total Pengeluaran</p>
                    <p class="font-mono text-xl text-[var(--floodlight)] font-bold leading-none">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="p-3.5 bg-[var(--ink)] border border-slate-800 rounded-xl text-xl">🪙</div>
            </div>

            <div class="card-glass p-6 flex flex-col justify-between border-l-4 border-l-[var(--turf)]">
                <p class="font-mono text-[11px] text-[var(--muted-2)] uppercase tracking-wider">Jadwal Terdekat</p>
                @if($upcomingMatch)
                    <div class="space-y-0.5 mt-2">
                        <p class="text-sm font-bold text-white truncate">{{ $upcomingMatch->lapangan->nama_lapangan ?? 'Arena Futsal' }}</p>
                        <p class="font-mono text-xs text-[var(--turf)] font-semibold">
                            {{ \Carbon\Carbon::parse($upcomingMatch->tanggal_main)->translatedFormat('d M Y') }} • {{ substr($upcomingMatch->jam_mulai, 0, 5) }}
                        </p>
                    </div>
                @else
                    <p class="text-xs text-[var(--muted)] italic mt-2">Tidak ada jadwal mendatang.</p>
                @endif
            </div>
        </div>

        <!-- 3. DATA TABLES CONSOLE BOARD -->
        <div class="card-glass overflow-hidden shadow-2xl">
            <!-- Filter & Search Controls Header -->
            <div class="p-6 border-b border-slate-800/80 bg-slate-900/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="font-body font-bold text-base text-white flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 bg-[var(--turf)] rounded-full animate-pulse" aria-hidden="true"></span>
                    Riwayat Transaksi & Reservasi
                </h3>

                <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto">
                    @if($reservasis->isNotEmpty())
                        <div class="w-full sm:w-36">
                            <label for="status_filter" class="sr-only">Filter Status</label>
                            <select id="status_filter" class="select-input">
                                <option value="">Semua Status</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="search-wrap flex-1 sm:flex-initial">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                            <label for="table_search" class="sr-only">Cari riwayat transaksi</label>
                            <input type="text" id="table_search" class="search-input" placeholder="Cari Order / Arena...">
                        </div>
                    @endif

                    <div id="bulk_action_panel" class="hidden items-center gap-2 bg-[var(--ink)] border border-slate-800 px-3 py-1.5 rounded-xl">
                        <span id="selected_count" class="font-mono text-xs font-bold bg-[var(--surface-3)] text-white px-2 py-0.5 rounded">0</span>
                        <span class="text-[11px] font-bold text-[var(--muted-2)] uppercase">Terpilih</span>
                        <button type="button" onclick="confirmMassDelete()" class="btn-ui btn-ui-danger btn-ui-sm ml-2">
                            🗑️ Hapus Sekaligus
                        </button>
                    </div>
                </div>
            </div>

            <form id="bulk_delete_form" action="{{ route('reservasi.destroyMassal') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            @foreach($reservasis as $reservasi)
                @if(in_array(strtolower($reservasi->status), ['waiting payment', 'pending']))
                    <form id="form_batal_{{ $reservasi->id }}" action="{{ route('reservasi.batal', $reservasi->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endif
            @endforeach

            @if($reservasis->isEmpty())
                <div class="py-20 px-6 text-center space-y-4">
                    <div class="text-5xl" aria-hidden="true">⚽</div>
                    <div class="space-y-1">
                        <p class="text-white font-bold text-base uppercase font-display tracking-wider">Belum Ada Reservasi</p>
                        <p class="text-[var(--muted-2)] text-xs">Anda belum memiliki riwayat data reservasi dalam sistem.</p>
                    </div>
                    <a href="{{ route('landingPage') }}" class="btn-ui btn-ui-primary btn-ui-sm w-fit mx-auto mt-2">
                        + Buat Reservasi Sekarang
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="brutal-data" id="riwayat_table">
                        <thead>
                            <tr>
                                <th class="w-12 text-center">
                                    <label class="sr-only" for="check_all_master">Pilih semua baris</label>
                                    <input type="checkbox" id="check_all_master" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-[var(--turf)] focus:ring-[var(--turf)] cursor-pointer">
                                </th>
                                <th>Detail Arena</th>
                                <th>Nomor Order</th>
                                <th>Tanggal Main</th>
                                <th>Waktu Slot</th>
                                <th>Metode Bayar</th>
                                <th>Total Tagihan</th>
                                <th>Status</th>
                                <th class="text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasis as $reservasi)
                                @php 
                                    $statusLower = strtolower($reservasi->status); 
                                    $normalizedStatus = in_array($statusLower, ['waiting payment', 'pending']) ? 'pending' : $statusLower;
                                @endphp
                                <tr class="data-row" 
                                    data-search="{{ strtolower($reservasi->nomor_reservasi.' '.($reservasi->lapangan->nama_lapangan ?? '')) }}"
                                    data-status="{{ $normalizedStatus }}">
                                    
                                    <td class="text-center">
                                        @if($normalizedStatus != 'pending')
                                            <label class="sr-only" for="chk_{{ $reservasi->id }}">Pilih reservasi {{ $reservasi->nomor_reservasi }}</label>
                                            <input type="checkbox" id="chk_{{ $reservasi->id }}" name="ids[]" value="{{ $reservasi->id }}" form="bulk_delete_form" class="row-checkbox w-4 h-4 rounded border-slate-700 bg-slate-900 text-[var(--turf)] focus:ring-[var(--turf)] cursor-pointer">
                                        @else
                                            <input type="checkbox" disabled aria-label="Selesaikan pembayaran terlebih dahulu" class="w-4 h-4 opacity-20 cursor-not-allowed" title="Selesaikan tagihan pembayaran terlebih dahulu">
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="text-sm font-bold text-white">{{ $reservasi->lapangan->nama_lapangan ?? 'Arena Utama' }}</div>
                                        <div class="font-mono text-[10px] text-[var(--muted-2)] uppercase font-bold mt-0.5">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                                    </td>
                                    
                                    <td>
                                        <button type="button" onclick="salinTeks('{{ $reservasi->nomor_reservasi }}', this)" class="bg-transparent border-0 p-0 cursor-pointer font-mono text-slate-400 hover:text-white uppercase inline-flex items-center gap-1.5 transition text-xs" title="Klik untuk salin">
                                            <span>{{ $reservasi->nomor_reservasi }}</span>
                                            <svg class="w-3.5 h-3.5 text-[var(--muted-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                    </td>
                                    
                                    <td class="text-[var(--line)] font-semibold text-xs">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}
                                    </td>
                                    
                                    <td class="font-mono text-[var(--turf)] font-bold text-xs">
                                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA
                                    </td>
                                    
                                    <td>
                                        <span class="font-mono text-[10px] font-bold uppercase bg-[var(--ink)] border border-slate-800 px-2 py-1 rounded text-[var(--muted)]">
                                            {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'Midtrans') }}
                                        </span>
                                    </td>
                                    
                                    <td class="font-mono text-sm font-bold text-white">
                                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                    </td>
                                    
                                    <td>
                                        @if(in_array($reservasi->status, ['Confirmed', 'Completed']))
                                            <span class="badge-brutal badge-brutal-confirmed">Confirmed</span>
                                        @elseif($reservasi->status == 'Cancelled')
                                            <span class="badge-brutal badge-brutal-cancelled">Cancelled</span>
                                        @else
                                            <span class="badge-brutal badge-brutal-pending">Pending</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($normalizedStatus == 'pending')
                                                @if(isset($reservasi->snap_token))
                                                    <button type="button" onclick="paySnap('{{ $reservasi->snap_token }}')" class="btn-ui btn-ui-primary btn-ui-sm">
                                                        Bayar
                                                    </button>
                                                @endif
                                                <button type="button" onclick="confirmCancel('{{ $reservasi->id }}')" class="btn-ui btn-ui-danger btn-ui-sm">
                                                    Batal
                                                </button>
                                            @elseif(in_array($reservasi->status, ['Confirmed', 'Completed']))
                                                <a href="{{ route('reservasi.tiket', $reservasi->id) }}" target="_blank" rel="noopener" class="btn-ui btn-ui-ghost btn-ui-sm !bg-slate-800/80">
                                                    Tiket
                                                </a>
                                            @else
                                                <span class="font-mono text-xs text-[var(--muted-2)] font-bold uppercase">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div id="no_search_result" class="hidden py-12 text-center text-[var(--muted-2)] text-xs font-semibold uppercase tracking-wider">
                        Tidak ada transaksi yang cocok dengan kriteria pencarian/filter Anda.
                    </div>
                </div>

                @if(method_exists($reservasis, 'links'))
                    <div class="p-5 border-t border-slate-800/80">
                        {{ $reservasis->links() }}
                    </div>
                @endif
            @endif
        </div>
    </main>

    <!-- MODERN MODAL CONFIRMATION -->
    <div id="confirmation_modal" class="modal-backdrop">
        <div class="modal-card space-y-5">
            <div class="space-y-2">
                <h3 id="modal_title" class="text-xl text-white">Konfirmasi Tindakan</h3>
                <p id="modal_description" class="text-sm text-[var(--muted)]">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="btn-ui btn-ui-ghost btn-ui-sm">Batal</button>
                <button type="button" id="modal_confirm_btn" class="btn-ui btn-ui-danger btn-ui-sm">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- CLIENT SCRIPT LOGIC -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const masterCheck = document.getElementById('check_all_master');
            const rowChecks = document.querySelectorAll('.row-checkbox');
            const actionPanel = document.getElementById('bulk_action_panel');
            const selectedCount = document.getElementById('selected_count');
            const searchInput = document.getElementById('table_search');
            const statusFilter = document.getElementById('status_filter');
            const allRows = document.querySelectorAll('#riwayat_table tbody .data-row');
            const noResultMsg = document.getElementById('no_search_result');

            if (masterCheck) {
                masterCheck.addEventListener('change', function () {
                    rowChecks.forEach(checkbox => {
                        const row = checkbox.closest('.data-row');
                        if (!checkbox.disabled && !row.classList.contains('row-hidden')) {
                            checkbox.checked = masterCheck.checked;
                            toggleRowStyle(checkbox);
                        }
                    });
                    refreshPanelVisibility();
                });

                rowChecks.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        toggleRowStyle(checkbox);
                        if (!checkbox.checked && masterCheck) masterCheck.checked = false;
                        refreshPanelVisibility();
                    });
                });
            }

            function toggleRowStyle(checkbox) {
                const row = checkbox.closest('.data-row');
                if (checkbox.checked) {
                    row.classList.add('bg-slate-800/40');
                } else {
                    row.classList.remove('bg-slate-800/40');
                }
            }

            function refreshPanelVisibility() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                if (selectedCount) selectedCount.innerText = checkedCount;
                
                if (actionPanel) {
                    if (checkedCount > 0) {
                        actionPanel.classList.remove('hidden');
                        actionPanel.classList.add('flex');
                    } else {
                        actionPanel.classList.remove('flex');
                        actionPanel.classList.add('hidden');
                    }
                }
            }

            function filterTable() {
                const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
                const selectedStatus = statusFilter ? statusFilter.value.toLowerCase() : '';
                let visibleCount = 0;

                allRows.forEach(row => {
                    const matchesSearch = row.dataset.search.includes(term);
                    const matchesStatus = selectedStatus === '' || row.dataset.status === selectedStatus;

                    const isVisible = matchesSearch && matchesStatus;
                    row.classList.toggle('row-hidden', !isVisible);

                    if (!isVisible) {
                        const cb = row.querySelector('.row-checkbox');
                        if (cb && cb.checked) {
                            cb.checked = false;
                            toggleRowStyle(cb);
                        }
                    } else {
                        visibleCount++;
                    }
                });

                if (noResultMsg) {
                    noResultMsg.classList.toggle('hidden', visibleCount !== 0);
                }
                
                refreshPanelVisibility();
            }

            if (searchInput) searchInput.addEventListener('input', filterTable);
            if (statusFilter) statusFilter.addEventListener('change', filterTable);
        });

        function salinTeks(teks, btn) {
            navigator.clipboard.writeText(teks).then(function () {
                const innerSpan = btn.querySelector('span');
                const originalText = innerSpan.innerText;
                innerSpan.innerText = 'Tersalin! ✓';
                btn.classList.add('text-emerald-400');
                setTimeout(() => { 
                    innerSpan.innerText = originalText; 
                    btn.classList.remove('text-emerald-400');
                }, 1800);
            });
        }

        function paySnap(snapToken) {
            if (typeof snap !== 'undefined' && snapToken) {
                snap.pay(snapToken, {
                    onSuccess: function(result){ window.location.reload(); },
                    onPending: function(result){ window.location.reload(); },
                    onError: function(result){ alert("Pembayaran gagal!"); },
                    onClose: function(){ console.log('Snap modal ditutup'); }
                });
            }
        }

        let pendingAction = null;

        function openModal(title, description, onConfirm) {
            document.getElementById('modal_title').innerText = title;
            document.getElementById('modal_description').innerText = description;
            pendingAction = onConfirm;
            document.getElementById('confirmation_modal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('confirmation_modal').classList.remove('active');
            pendingAction = null;
        }

        document.getElementById('modal_confirm_btn').addEventListener('click', function() {
            if (pendingAction) pendingAction();
            closeModal();
        });

        function confirmCancel(id) {
            openModal(
                'Batalkan Reservasi',
                'Apakah Anda yakin ingin membatalkan pesanan ini? Slot jam lapangan akan dilepaskan kembali.',
                function() {
                    document.getElementById('form_batal_' + id).submit();
                }
            );
        }

        function confirmMassDelete() {
            openModal(
                'Hapus Riwayat Terpilih',
                'Apakah Anda yakin ingin menghapus semua riwayat transaksi yang dipilih dari tampilan Anda?',
                function() {
                    document.getElementById('bulk_delete_form').submit();
                }
            );
        }
    </script>
</body>
</html>