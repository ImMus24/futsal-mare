@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras dengan landing page publik)
     Display: Anton · Body: Work Sans · Data: JetBrains Mono
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e2601f; /* turf orange — CTA & elemen aktif */
        --color-primary-dark: #b8481a;
        --color-secondary:    #f5c518; /* floodlight gold — highlight & ikon */
        --color-bg-main:      #121a23; /* navy-slate — latar utama */
        --color-bg-card:      #0a0f14; /* charcoal dalam — panel & tabel */
        --color-bg-raised:    #1a2431;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);
        --line-2:             rgba(238, 241, 234, 0.14);
        --ease: cubic-bezier(.22,1,.36,1);

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope .eyebrow {
        font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: .14em; text-transform: uppercase;
        color: var(--color-primary); display: inline-flex; align-items: center; gap: 8px; font-weight: 500;
    }
    .fm-scope .eyebrow::before { content: ""; width: 14px; height: 2px; background: var(--color-primary); display: inline-block; }
    .fm-glow {
        background: radial-gradient(ellipse 700px 320px at 90% -20%, rgba(226,96,31,0.16), transparent 60%), var(--color-bg-main);
    }
    .fm-live-pip { animation: fm-pulse 1.6s infinite; }
    @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
    @media (prefers-reduced-motion: reduce) { .fm-live-pip { animation: none; } }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }

    /* Tombol & interaksi — dipindah dari inline JS ke CSS agar konsisten & tanpa handler berulang */
    .fm-scope .fm-btn-outline {
        background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);
        transition: color .18s ease, border-color .18s ease;
    }
    .fm-scope .fm-btn-outline:hover { color: #fff; border-color: var(--line-2); }

    .fm-scope .fm-btn-nav {
        background: var(--color-bg-raised); color: var(--color-text-main);
        transition: background .18s ease, transform .18s var(--ease);
    }
    .fm-scope .fm-btn-nav:hover { background: var(--color-primary); transform: translateY(-1px); }

    .fm-scope .fm-btn-gold {
        background: rgba(245,197,24,0.1); border: 1px solid rgba(245,197,24,0.35); color: var(--color-secondary);
        transition: background .18s ease, color .18s ease, transform .18s var(--ease);
    }
    .fm-scope .fm-btn-gold:hover { background: var(--color-secondary); color: var(--color-bg-main); transform: translateY(-1px); }

    .fm-scope .fm-btn-danger {
        background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);
        transition: background .18s ease, color .18s ease;
    }
    .fm-scope .fm-btn-danger:hover { background: var(--danger); color: #fff; }

    .fm-scope .fm-row:hover { background: rgba(255,255,255,0.025); }
    .fm-scope .fm-row { transition: background .15s ease; }

    .fm-scope .fm-metric {
        transition: transform .2s var(--ease), border-color .2s ease;
    }
    .fm-scope .fm-metric:hover { transform: translateY(-3px); border-color: var(--line-2); }

    .fm-scope .fm-avatar {
        width: 28px; height: 28px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; background: var(--color-bg-raised); color: var(--color-text-muted); flex-shrink: 0;
    }
</style>

<div class="fm-scope space-y-8 animate-fade-in">

    {{-- Notifikasi Global --}}
    @include('partials.toast')

    <!-- ============ TOP BAR ============ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-5" style="border-color: var(--line);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center f-display text-lg shadow-lg" style="background: var(--color-primary); color: var(--color-text-main); transform: rotate(-2deg);">M</div>
            <div>
                <h2 class="f-display text-base uppercase tracking-wide leading-tight" style="color: var(--color-text-main);">Futsal Mare HQ</h2>
                <div class="eyebrow mt-1">Workspace Operasional</div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="f-mono text-[10px] hidden sm:inline-flex items-center gap-1.5" style="color: var(--color-text-meta);">
                Diperbarui {{ now()->translatedFormat('d M, H:i') }} WITA
            </span>
            <a href="/" class="fm-btn-outline inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-wide">
                ⬅️ Beranda
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fm-btn-danger px-4 py-2 text-xs font-semibold rounded-lg uppercase tracking-wide">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- ============ HERO CONSOLE ============ -->
    <div class="fm-glow relative p-6 sm:p-8 rounded-2xl shadow-2xl overflow-hidden" style="border: 1px solid var(--line);">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-[10px] font-semibold tracking-widest uppercase"
                      style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-primary);">
                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--color-primary);"></span>
                    Konsol Kontrol Pusat
                </span>
                <h1 class="f-display uppercase tracking-tight mt-3" style="font-size: clamp(26px, 3.4vw, 38px); color: var(--color-text-main);">
                    Ringkasan Eksekutif
                </h1>
                <p class="text-sm mt-1.5 max-w-md font-medium" style="color: var(--color-text-muted);">
                    Pantau omset, jadwal aktif, dan aliran transaksi terbaru dalam satu layar.
                </p>
            </div>

            <div class="flex flex-wrap gap-2 p-2 rounded-xl" style="background: var(--color-bg-card); border: 1px solid var(--line);">
                <a href="{{ route('admin.reservasi.index') }}" class="fm-btn-nav px-4 py-2.5 font-semibold text-[11px] rounded-lg uppercase tracking-wide">
                    Log Reservasii
                </a>
                <a href="{{ route('admin.lapangan.index') }}" class="fm-btn-nav px-4 py-2.5 font-semibold text-[11px] rounded-lg uppercase tracking-wide">
                    Kelola Arena
                </a>
                @if(auth()->user()->is_admin == 1)
                    <a href="{{ route('admin.role.index') }}" class="fm-btn-gold px-4 py-2.5 font-semibold text-[11px] rounded-lg uppercase tracking-wide">
                        Manajemen Aksesss
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ============ METRICS ============ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach([
            ['label' => 'Omset', 'value' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 'icon' => '🪙', 'accent' => 'var(--color-secondary)'],
            ['label' => 'Jadwal Aktif', 'value' => $matchTerkonfirmasi, 'icon' => '🎮', 'accent' => 'var(--color-primary)'],
            ['label' => 'Member', 'value' => $totalMember, 'icon' => '👥', 'accent' => 'var(--success)'],
        ] as $metric)
        <div class="fm-metric relative p-6 rounded-xl shadow-xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="absolute left-0 right-0 bottom-0 h-[3px]" style="background: linear-gradient(90deg, {{ $metric['accent'] }}, transparent 85%);"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">{{ $metric['label'] }}</p>
                    <p class="f-mono text-2xl font-bold mt-2" style="color: var(--color-text-main);">{{ $metric['value'] }}</p>
                </div>
                <div class="p-3.5 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">{{ $metric['icon'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ============ RECENT TRANSACTIONS ============ -->
    <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="p-6 flex justify-between items-center" style="border-bottom: 1px solid var(--line);">
            <div>
                <h2 class="f-display text-sm uppercase tracking-wider" style="color: var(--color-text-main);">Aliran Transaksi Terakhir</h2>
                <p class="text-[11px] mt-0.5" style="color: var(--color-text-meta);">Diperbarui otomatis dari sistem reservasi.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-[9px] font-semibold px-2.5 py-1.5 rounded-md uppercase tracking-widest"
                  style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);">
                <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--color-primary);"></span> LIVE STREAM
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead style="background: var(--color-bg-main); border-bottom: 1px solid var(--line);">
                    <tr class="text-[10px] font-semibold uppercase tracking-wider" style="color: var(--color-text-meta);">
                        <th class="p-5">Arena</th>
                        <th class="p-5">Order</th>
                        <th class="p-5">Pelanggan</th>
                        <th class="p-5">Jadwal</th>
                        <th class="p-5">Total</th>
                        <th class="p-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--line);">
                    @forelse($reservasis as $r)
                        @php
                            $namaPelanggan = $r->user->name ?? 'Guest';
                            $statusStyles = [
                                'Confirmed' => ['bg' => 'var(--success-bg)', 'fg' => 'var(--success)'],
                                'Cancelled' => ['bg' => 'var(--danger-bg)', 'fg' => 'var(--danger)'],
                            ];
                            $status = $statusStyles[$r->status] ?? ['bg' => 'var(--pending-bg)', 'fg' => 'var(--pending)'];
                        @endphp
                        <tr class="fm-row">
                            <td class="p-5 text-sm font-semibold uppercase" style="color: var(--color-text-main);">{{ $r->lapangan->nama_lapangan ?? 'N/A' }}</td>
                            <td class="p-5 f-mono text-xs" style="color: var(--color-text-muted);">{{ $r->nomor_reservasi }}</td>
                            <td class="p-5 text-sm" style="color: var(--color-text-main);">
                                <span class="inline-flex items-center gap-2.5">
                                    <span class="fm-avatar">{{ strtoupper(substr($namaPelanggan, 0, 1)) }}</span>
                                    {{ $namaPelanggan }}
                                </span>
                            </td>
                            <td class="p-5 text-sm" style="color: var(--color-text-muted);">{{ \Carbon\Carbon::parse($r->tanggal_main)->format('d M Y') }}</td>
                            <td class="p-5 f-mono text-sm font-bold" style="color: var(--color-text-main);">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                            <td class="p-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-semibold uppercase tracking-wide" style="background: {{ $status['bg'] }}; color: {{ $status['fg'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $status['fg'] }};"></span> {{ $r->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-2xl">⚽</span>
                                    <p class="text-sm font-semibold" style="color: var(--color-text-main);">Belum ada transaksi</p>
                                    <p class="text-xs" style="color: var(--color-text-meta);">Reservasi baru akan tampil di sini begitu masuk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection