@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras kelola lapangan & log reservasi)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e25e20;
        --color-primary-dark: #cb5119;
        --color-secondary:    #f5c518;
        --color-bg-main:      #121a23;
        --color-bg-card:      #0a0f14;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);
        --info:    #3B82F6; --info-bg:    rgba(59, 130, 246, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }

    .fm-scope .metric-card {
        background: var(--color-bg-card); border: 1px solid var(--line); border-radius: 16px;
        padding: 24px; display: flex; align-items: center; justify-content: space-between;
        transition: border-color .2s ease, transform .2s ease;
    }
    .fm-scope .metric-card:hover { transform: translateY(-2px); }

    .fm-scope .quick-link {
        display: flex; align-items: center; gap: 12px; padding: 16px; border-radius: 12px;
        background: var(--color-bg-card); border: 1px solid var(--line); transition: border-color .2s ease, transform .2s ease;
    }
    .fm-scope .quick-link:hover { border-color: var(--color-primary); transform: translateY(-2px); }

    table.fm-table { width: 100%; border-collapse: collapse; }
    .fm-scope table.fm-table th {
        text-align: left; font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--color-text-meta);
        text-transform: uppercase; letter-spacing: .05em; padding: 12px 16px;
        border-bottom: 1px solid var(--line); background: rgba(15, 23, 42, 0.2);
    }
    .fm-scope table.fm-table td { padding: 14px 16px; border-bottom: 1px solid var(--line); font-size: 13px; font-weight: 500; }
    .fm-scope table.fm-table tr:last-child td { border-bottom: none; }

    .badge-status { font-family: 'JetBrains Mono', monospace; font-size: 10px; padding: 4px 9px; border-radius: 6px; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
    .badge-confirmed { background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.3); }
    .badge-pending   { background: var(--pending-bg); color: var(--pending); border: 1px solid rgba(245,158,11,0.3); }
    .badge-cancelled { background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(239,68,68,0.3); }

    @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    <!-- 1. HEADER HERO WIDGET -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 rounded-2xl shadow-2xl relative overflow-hidden"
         style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,94,32,0.18), transparent 70%);"></div>
        <div class="relative z-10">
            <span class="inline-flex items-center gap-2 f-mono text-[11px] font-semibold uppercase tracking-widest" style="color: var(--color-primary);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                Panel Kontrol Admin
            </span>
            <h1 class="f-display text-2xl uppercase tracking-tight mt-1.5" style="color: var(--color-text-main);">👋 Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}</h1>
            <p class="text-xs mt-1.5 max-w-lg" style="color: var(--color-text-muted);">Ringkasan performa bisnis Futsal Mare secara keseluruhan, hari ini.</p>
        </div>
    </div>

    <!-- 2. NOTIFIKASI -->
    @if(session('success'))
        <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
             style="background: var(--success-bg); border: 1px solid rgba(34,197,94,0.3); color: var(--success);">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
             style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 rounded-2xl text-xs font-semibold shadow-md"
             style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
            <p class="f-mono uppercase tracking-wide font-bold flex items-center gap-2"><span>⚠️</span> Aksi Gagal Diproses:</p>
            <ul class="list-disc list-inside mt-1.5 font-medium" style="opacity: 0.9;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. METRIK BISNIS UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="metric-card">
            <div class="space-y-1">
                <p class="f-mono text-[11px] uppercase tracking-wide" style="color: var(--color-text-meta);">Total Pendapatan</p>
                <p class="f-mono text-2xl font-bold" style="color: var(--color-secondary);">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="p-3.5 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">🪙</div>
        </div>
        <div class="metric-card">
            <div class="space-y-1">
                <p class="f-mono text-[11px] uppercase tracking-wide" style="color: var(--color-text-meta);">Match Terkonfirmasi</p>
                <p class="f-mono text-2xl font-bold" style="color: var(--success);">{{ $matchTerkonfirmasi }} <span class="text-xs font-medium" style="color: var(--color-text-muted); font-family: 'Work Sans', sans-serif;">Match</span></p>
            </div>
            <div class="p-3.5 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">✅</div>
        </div>
        <div class="metric-card">
            <div class="space-y-1">
                <p class="f-mono text-[11px] uppercase tracking-wide" style="color: var(--color-text-meta);">Total Member Terdaftar</p>
                <p class="f-mono text-2xl font-bold" style="color: var(--color-text-main);">{{ $totalMember }} <span class="text-xs font-medium" style="color: var(--color-text-muted); font-family: 'Work Sans', sans-serif;">Akun</span></p>
            </div>
            <div class="p-3.5 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">👥</div>
        </div>
    </div>

    <!-- 4. AKSES CEPAT -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.reservasi.index') }}" class="quick-link">
            <span class="text-lg">📅</span>
            <div>
                <p class="text-sm font-semibold" style="color: var(--color-text-main);">Log Reservasi</p>
                <p class="f-mono text-[10px] uppercase tracking-wide" style="color: var(--color-text-meta);">Kelola transaksi</p>
            </div>
        </a>
        <a href="{{ route('admin.lapangan.index') }}" class="quick-link">
            <span class="text-lg">🥅</span>
            <div>
                <p class="text-sm font-semibold" style="color: var(--color-text-main);">Kelola Lapangan</p>
                <p class="f-mono text-[10px] uppercase tracking-wide" style="color: var(--color-text-meta);">Atur arena & tarif</p>
            </div>
        </a>
        <a href="{{ route('admin.member.index') }}" class="quick-link">
            <span class="text-lg">👥</span>
            <div>
                <p class="text-sm font-semibold" style="color: var(--color-text-main);">Data Member</p>
                <p class="f-mono text-[10px] uppercase tracking-wide" style="color: var(--color-text-meta);">Kelola loyalitas</p>
            </div>
        </a>
    </div>

    <!-- 5. TABEL RESERVASI TERBARU -->
    <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="p-6 flex items-center justify-between" style="border-bottom: 1px solid var(--line); background: rgba(15, 23, 42, 0.2);">
            <h3 class="text-sm font-bold flex items-center gap-2" style="color: var(--color-text-main);">
                <span class="w-2 h-2 rounded-full" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                Reservasi Terbaru
            </h3>
            <a href="{{ route('admin.reservasi.index') }}" class="f-mono text-[11px] font-semibold uppercase tracking-wide" style="color: var(--color-text-muted);">
                Lihat Semua &rarr;
            </a>
        </div>

        @if($reservasis->isEmpty())
            <div class="p-16 text-center space-y-2">
                <div class="text-3xl">📋</div>
                <p class="text-xs font-bold uppercase tracking-wide" style="color: var(--color-text-meta);">Belum ada data reservasi yang masuk.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="fm-table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Arena</th>
                            <th>Jadwal</th>
                            <th class="text-right">Total</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody style="color: var(--color-text-muted);">
                        @foreach($reservasis as $reservasi)
                            <tr>
                                <td>
                                    <span class="block font-semibold" style="color: var(--color-text-main);">{{ $reservasi->user->name ?? 'User Terhapus' }}</span>
                                    <span class="f-mono text-[10px]" style="color: var(--color-text-meta);">{{ $reservasi->nomor_reservasi }}</span>
                                </td>
                                <td>{{ $reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus' }}</td>
                                <td>
                                    <span class="block" style="color: var(--color-text-main);">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}</span>
                                    <span class="f-mono text-[10px]" style="color: var(--color-primary);">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                                </td>
                                <td class="text-right f-mono font-bold" style="color: var(--color-text-main);">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                        <span class="badge-status badge-confirmed">{{ $reservasi->status }}</span>
                                    @elseif($reservasi->status == 'Cancelled')
                                        <span class="badge-status badge-cancelled">Cancelled</span>
                                    @else
                                        <span class="badge-status badge-pending">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($reservasis->hasPages())
                <div class="p-4" style="background: var(--color-bg-main); border-top: 1px solid var(--line);">
                    {{ $reservasis->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection