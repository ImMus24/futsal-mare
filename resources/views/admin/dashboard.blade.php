@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras seluruh panel admin)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e2601f;
        --color-primary-dark: #b8481a;
        --color-secondary:    #f5c518;
        --color-bg-main:      #121a23;
        --color-bg-card:      #0a0f14;
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
        --info:    #3B82F6; --info-bg:    rgba(59, 130, 246, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope .eyebrow {
        font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: .14em; text-transform: uppercase;
        color: var(--color-primary); display: inline-flex; align-items: center; gap: 8px; font-weight: 500;
    }
    @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
    @media (prefers-reduced-motion: reduce) { .fm-scope .fm-live-pip { animation: none; } }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }

    .fm-scope .fm-nav-chip {
        background: var(--color-bg-raised); color: var(--color-text-main); border: 1px solid var(--line);
        transition: background .18s ease, transform .18s var(--ease);
    }
    .fm-scope .fm-nav-chip:hover { background: var(--color-primary); transform: translateY(-1px); }

    .fm-scope .fm-metric { transition: transform .2s var(--ease), border-color .2s ease; }
    .fm-scope .fm-metric:hover { transform: translateY(-3px); border-color: var(--line-2); }

    .fm-scope .fm-quick-link {
        background: var(--color-bg-main); border: 1px solid var(--line); transition: background .15s ease, border-color .15s ease;
    }
    .fm-scope .fm-quick-link:hover { background: var(--color-bg-raised); border-color: var(--line-2); }

    .fm-scope .fm-row:hover { background: rgba(255,255,255,0.02); }
    .fm-scope .fm-row { transition: background .15s ease; }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    {{-- Notifikasi Global --}}
    @include('partials.toast')

    <!-- TOP BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-5" style="border-color: var(--line);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center f-display text-lg shadow-lg" style="background: var(--color-primary); color: #fff; transform: rotate(-2deg);">M</div>
            <div>
                <h2 class="f-display text-base uppercase tracking-wide leading-tight" style="color: var(--color-text-main);">Futsal Mare HQ</h2>
                <div class="eyebrow mt-1">Workspace Operasional</div>
            </div>
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3">
            <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-wide transition"
               style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                ⬅️ Beranda Utama
            </a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg uppercase tracking-wide transition"
                        style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                    🚪 Keluar Sesi
                </button>
            </form>
        </div>
    </div>

    <!-- HERO BANNER -->
    <div class="relative p-6 sm:p-8 rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="absolute -right-16 -top-16 w-48 h-48 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,96,31,0.18), transparent 70%);"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <span class="eyebrow">
                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                    Konsol Kontrol Pusat
                </span>
                <h1 class="f-display text-2xl uppercase tracking-tight sm:text-3xl" style="color: var(--color-text-main);">Ringkasan Eksekutif Arena</h1>
                <p class="text-xs font-medium max-w-2xl leading-relaxed" style="color: var(--color-text-muted);">
                    Pantau grafik utilisasi lapangan, transaksi finansial, dan manajemen kasta loyalitas member dalam satu ruang kontrol pusat.
                </p>
            </div>

            <div class="flex flex-wrap lg:flex-nowrap gap-2 p-2 rounded-xl w-full lg:w-auto" style="background: var(--color-bg-main); border: 1px solid var(--line);">
                <a href="{{ route('admin.reservasi.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-semibold text-[10px] rounded-lg uppercase tracking-wider">Log Reservasi</a>
                <a href="{{ route('admin.lapangan.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-semibold text-[10px] rounded-lg uppercase tracking-wider">Kelola Arena</a>
                <a href="{{ route('admin.member.index') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-semibold text-[10px] rounded-lg uppercase tracking-wider">Data Member</a>
                <a href="{{ route('admin.staff.scan') }}" class="fm-nav-chip flex-1 lg:flex-none text-center px-4 py-2.5 font-semibold text-[10px] rounded-lg uppercase tracking-wider">📷 Gate Scanner</a>
                @if(auth()->user()->is_admin == 1)
                    <a href="{{ route('admin.role.index') }}" class="flex-1 lg:flex-none text-center px-4 py-2.5 font-semibold text-[10px] rounded-lg uppercase tracking-wider transition"
                       style="background: rgba(245,197,24,0.1); border: 1px solid rgba(245,197,24,0.35); color: var(--color-secondary);"
                       onmouseover="this.style.background='var(--color-secondary)'; this.style.color='var(--color-bg-main)'"
                       onmouseout="this.style.background='rgba(245,197,24,0.1)'; this.style.color='var(--color-secondary)'">
                        Manajemen Akses
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- METRICS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="fm-metric p-6 rounded-xl shadow-xl flex items-center justify-between" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="space-y-1.5">
                <p class="f-mono text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">Akumulasi Omset Lunas</p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--success);">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <span class="inline-flex text-[9px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded" style="background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.25);">⚡ Terverifikasi Midtrans</span>
            </div>
            <div class="p-4 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">🪙</div>
        </div>

        <div class="fm-metric p-6 rounded-xl shadow-xl flex items-center justify-between" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="space-y-1.5">
                <p class="f-mono text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">Slot Jadwal Aktif</p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--info);">{{ $matchTerkonfirmasi }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Jadwal</span></p>
                <span class="inline-flex text-[9px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded" style="background: var(--info-bg); color: var(--info); border: 1px solid rgba(59,130,246,0.25);">📅 Status Confirmed</span>
            </div>
            <div class="p-4 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">🎮</div>
        </div>

        <div class="fm-metric p-6 rounded-xl shadow-xl flex items-center justify-between" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="space-y-1.5">
                <p class="f-mono text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">Database Pelanggan</p>
                <p class="f-mono text-2xl font-bold tracking-tight" style="color: var(--color-text-main);">{{ $totalMember }} <span class="text-xs font-semibold uppercase" style="color: var(--color-text-meta);">Akun</span></p>
                <span class="inline-flex text-[9px] font-semibold uppercase tracking-wide px-2 py-0.5 rounded" style="background: rgba(226,96,31,0.1); color: var(--color-primary); border: 1px solid rgba(226,96,31,0.25);">🏆 Member Berkasta</span>
            </div>
            <div class="p-4 rounded-xl text-xl" style="background: var(--color-bg-main); border: 1px solid var(--line);">👥</div>
        </div>
    </div>

    <!-- CHART & QUICK ACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 rounded-2xl p-6 shadow-2xl flex flex-col justify-between" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="flex items-center justify-between border-b pb-4 mb-4" style="border-color: var(--line);">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">{{ $metric['label'] }}</p>
                    <p class="f-mono text-2xl font-bold mt-2" style="color: var(--color-text-main);">{{ $metric['value'] }}</p>
                </div>
                <div class="w-2.5 h-2.5 rounded-full fm-live-pip" style="background: var(--success); animation: fm-pulse 1.6s infinite;"></div>
            </div>
            <div class="relative w-full h-64 flex items-center justify-center rounded-xl p-4" style="background: var(--color-bg-main); border: 1px solid var(--line);">
                <canvas id="dashboardPerformanceChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="lg:col-span-4 rounded-2xl p-6 shadow-2xl flex flex-col justify-between space-y-4" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="border-b pb-4" style="border-color: var(--line);">
                <h3 class="f-display text-xs uppercase tracking-wider" style="color: var(--color-text-main);">Aksi Cepat Gerbang</h3>
                <p class="text-[10px] font-semibold mt-0.5 uppercase tracking-wide" style="color: var(--color-text-meta);">Pintas penanganan operasional</p>
            </div>

            <div class="flex-1 grid grid-cols-1 gap-2.5">
                <a href="{{ route('admin.reservasi.index', ['status' => 'Waiting Payment']) }}" class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl transition group">
                    <span class="text-xs font-semibold" style="color: var(--color-text-muted);">⏳ Tinjau Nota Pending</span>
                    <span class="f-mono text-[9px] font-bold px-2 py-0.5 rounded uppercase" style="background: var(--pending-bg); color: var(--pending); border: 1px solid rgba(245,158,11,0.25);">Cek</span>
                </a>

                <a href="{{ route('admin.reservasi.exportExcel') }}" class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl transition group">
                    <span class="text-xs font-semibold" style="color: var(--color-text-muted);">📊 Unduh Laporan Excel</span>
                    <span class="f-mono text-[9px] font-bold px-2 py-0.5 rounded uppercase" style="background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.25);">Unduh</span>
                </a>

                <a href="{{ route('admin.staff.scan') }}" class="fm-quick-link flex items-center justify-between p-3.5 rounded-xl transition group">
                    <span class="text-xs font-semibold" style="color: var(--color-text-muted);">📷 Buka Konsol Scanner QR</span>
                    <span class="f-mono text-[9px] font-bold px-2 py-0.5 rounded uppercase" style="background: var(--info-bg); color: var(--info); border: 1px solid rgba(59,130,246,0.25);">Terminal</span>
                </a>
            </div>

            <div class="p-3 rounded-lg text-[9px] font-bold text-center uppercase tracking-wider f-mono" style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-meta);">
                Keamanan enkripsi data SSL aktif
            </div>
        </div>
    </div>

    <!-- RECENT TRANSACTIONS -->
    <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="background: var(--color-bg-main); border-bottom: 1px solid var(--line);">
            <div>
                <h2 class="f-display text-xs uppercase tracking-wider flex items-center gap-1.5" style="color: var(--color-text-main);">
                    <span class="w-2 h-2 rounded-full fm-live-pip" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span> Aliran Data Transaksi Terakhir
                </h2>
                <p class="text-[10px] font-semibold mt-0.5 uppercase tracking-wide" style="color: var(--color-text-meta);">Log pemantauan 10 entri data booking terbaru</p>
            </div>
            <span class="self-start sm:self-auto f-mono text-[9px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider" style="background: var(--color-bg-card); color: var(--color-text-muted); border: 1px solid var(--line);">Live Stream Ready</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-semibold uppercase tracking-wider" style="border-bottom: 1px solid var(--line); color: var(--color-text-meta); background: var(--color-bg-main);">
                        <th class="p-5">Detail Arena</th>
                        <th class="p-5">Nomor Order</th>
                        <th class="p-5">Identitas Pelanggan</th>
                        <th class="p-5">Waktu Slot</th>
                        <th class="p-5">Total Bayar</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-xs font-medium" style="border-color: var(--line); color: var(--color-text-muted);">
                    @forelse($reservasis as $reservasi)
                    <tr class="fm-row">
                        <td class="p-5">
                            <div class="font-bold text-sm uppercase tracking-tight" style="color: var(--color-text-main);">{{ $reservasi->lapangan->nama_lapangan ?? 'N/A' }}</div>
                            <div class="f-mono text-[10px] font-semibold uppercase tracking-wider mt-0.5" style="color: var(--color-text-meta);">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                        </td>

                        <td class="p-5 f-mono uppercase tracking-wider" style="color: var(--color-text-muted);">{{ $reservasi->nomor_reservasi }}</td>

                        <td class="p-5">
                            <div class="text-sm font-semibold tracking-tight" style="color: var(--color-text-main);">{{ $reservasi->user->name ?? 'Guest User' }}</div>
                            @if($reservasi->user && $reservasi->user->membership)
                                @if($reservasi->user->membership->membership_type == 'Gold')
                                    <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase" style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);">🏆 Gold</span>
                                @elseif($reservasi->user->membership->membership_type == 'Silver')
                                    <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase" style="background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3);">🥈 Silver</span>
                                @else
                                    <span class="inline-block mt-1 f-mono text-[8px] font-bold tracking-widest px-2 py-0.5 rounded uppercase" style="background: rgba(180,83,9,0.15); color: #b45309; border: 1px solid rgba(180,83,9,0.3);">🥉 Bronze</span>
                                @endif
                            @else
                                <span class="inline-block mt-1 f-mono text-[8px] font-semibold tracking-widest px-2 py-0.5 rounded uppercase" style="background: var(--color-bg-main); color: var(--color-text-meta); border: 1px solid var(--line);">Non-Member</span>
                            @endif
                        </td>

                        <td class="p-5">
                            <div style="color: var(--color-text-main);">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}</div>
                            <div class="f-mono text-[10px] mt-0.5 uppercase tracking-wide" style="color: var(--info);">⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA</div>
                        </td>

                        <td class="p-5 font-bold f-mono text-sm" style="color: var(--color-text-main);">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>

                        <td class="p-5">
                            @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-semibold uppercase tracking-wider" style="background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.25);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--success);"></span> {{ $reservasi->status }}
                                </span>
                            @elseif($reservasi->status == 'Cancelled')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-semibold uppercase tracking-wider" style="background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(239,68,68,0.25);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--danger);"></span> Cancelled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-semibold uppercase tracking-wider" style="background: var(--pending-bg); color: var(--pending); border: 1px solid rgba(245,158,11,0.25);">
                                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--pending); animation: fm-pulse 1.6s infinite;"></span> Pending
                                </span>
                            @endif
                        </td>

                        <td class="p-5 text-center">
                            <a href="{{ route('admin.reservasi.index', ['status' => $reservasi->status]) }}"
                               class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-[10px] font-semibold uppercase tracking-wider transition f-mono"
                               style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);"
                               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center font-semibold uppercase tracking-wider text-xs" style="color: var(--color-text-meta);">
                            ⚽ Belum ada entri log reservasi yang tercatat di sistem database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservasis->hasPages())
            <div class="p-6 data-dark-pagination" style="background: var(--color-bg-main); border-top: 1px solid var(--line);">
                {{ $reservasis->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('dashboardPerformanceChart').getContext('2d');

        // Data ASLI dari controller (AdminDashboardController::index()) —
        // bukan angka hardcode. Dihitung dari total jam booking Confirmed/
        // Completed per hari, 7 hari terakhir.
        const labelUtilisasi = @json($labelUtilisasi);
        const dataUtilisasi = @json($dataUtilisasi);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelUtilisasi,
                datasets: [{
                    label: 'Utilisasi Lapangan (Jam)',
                    data: dataUtilisasi,
                    borderColor: '#e2601f',
                    backgroundColor: 'rgba(226, 96, 31, 0.06)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#e2601f',
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: { color: '#5c6979', font: { size: 9, weight: 'bold', family: 'JetBrains Mono' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.03)' },
                        ticks: { color: '#5c6979', font: { size: 9, weight: 'bold', family: 'JetBrains Mono' }, precision: 0 }
                    }
                }
            }
        });
    });
</script>
@endsection