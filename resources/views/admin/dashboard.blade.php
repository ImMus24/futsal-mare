@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-fade-in" style="--turf: #e25e20; --floodlight: #f5c518; --surface: #121a23; --mono: 'JetBrains Mono', monospace;">
    
    <!-- TOP BAR WORKSPACE HERO -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-800 pb-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#e25e20] flex items-center justify-center text-white shadow-lg font-black text-lg transform rotate-3">
                M
            </div>
            <div>
                <h2 class="f-display text-base uppercase tracking-wide leading-tight" style="color: var(--color-text-main);">Futsal Mare HQ</h2>
                <div class="eyebrow mt-1">Workspace Operasional</div>
            </div>
        </div>
        
        <div class="flex items-center justify-between sm:justify-end gap-4">
            <a href="/" class="inline-flex items-center gap-2 px-4 py-2 bg-[#121a23] hover:bg-slate-800 border border-slate-800 rounded-lg text-xs font-black text-slate-300 transition duration-150 tracking-wide uppercase">
                <span>⬅️</span> Beranda Utama
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-950/20 hover:bg-red-900/40 border border-red-900/30 text-red-400 text-xs font-black rounded-lg uppercase tracking-wider transition duration-150 shadow-sm">
                    <span>🚪</span> Keluar Sesi
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONSOLE PROFILE HERO BANNER -->
    <div class="relative bg-[#121a23] p-6 sm:p-8 rounded-2xl border border-slate-800 shadow-2xl overflow-hidden group">
        <div class="absolute -right-16 -top-16 w-48 h-48 bg-[#e25e20] rounded-full filter blur-[100px] opacity-5 group-hover:opacity-10 transition duration-500"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black bg-[#0a0f14]/60 text-[#e25e20] tracking-widest uppercase border border-slate-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#e25e20] animate-pulse"></span> Konsol Kontrol Pusat
                </span>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase sm:text-3xl font-display">Ringkasan Eksekutif Arena</h1>
                <p class="text-slate-400 text-xs font-medium max-w-2xl leading-relaxed">
                    Pantau grafik transaksi finansial, optimalisasi alokasi slot lapangan futsal, dan manajemen kasta loyalitas member dalam satu ruang kontrol pusat terpusat.
                </p>
            </div>
            
            <div class="flex flex-wrap lg:flex-nowrap gap-2 bg-[#0a0f14]/40 p-2 rounded-xl border border-slate-800/80 backdrop-blur-sm w-full lg:w-auto">
                <a href="{{ route('admin.reservasi.index') }}" class="flex-1 lg:flex-none text-center px-4 py-2.5 bg-[#212d3c] hover:bg-[#e25e20] hover:text-white border border-slate-800 text-slate-200 font-black text-[10px] rounded-lg uppercase tracking-wider transition duration-150">Log Reservasi</a>
                <a href="{{ route('admin.lapangan.index') }}" class="flex-1 lg:flex-none text-center px-4 py-2.5 bg-[#212d3c] hover:bg-[#e25e20] hover:text-white border border-slate-800 text-slate-200 font-black text-[10px] rounded-lg uppercase tracking-wider transition duration-150">Kelola Arena</a>
                <a href="{{ route('admin.member.index') }}" class="flex-1 lg:flex-none text-center px-4 py-2.5 bg-[#212d3c] hover:bg-[#e25e20] hover:text-white border border-slate-800 text-slate-200 font-black text-[10px] rounded-lg uppercase tracking-wider transition duration-150">Data Member</a>
            </div>
        </div>
    </div>

    <!-- REAL-TIME METRICS BENTO GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#121a23] p-6 rounded-xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-mono">Akumulasi Omset Lunas</p>
                <p class="text-2xl font-black text-[#22C55E] font-mono tracking-tight">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <span class="inline-flex text-[9px] font-bold text-emerald-400/80 uppercase tracking-wide bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-900/40">⚡ Terverifikasi Midtrans</span>
            </div>
            <div class="p-4 bg-[#0a0f14] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-emerald-950/20">🪙</div>
        </div>
        
        <div class="bg-[#121a23] p-6 rounded-xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-blue-500/30 transition-all duration-300 hover:-translate-y-0.5">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-mono">Slot Jadwal Aktif</p>
                <p class="text-2xl font-black text-blue-400 font-mono tracking-tight">{{ $matchTerkonfirmasi }} <span class="text-xs text-slate-500 font-black uppercase">Jadwal</span></p>
                <span class="inline-flex text-[9px] font-bold text-blue-400/80 uppercase tracking-wide bg-blue-950/40 px-2 py-0.5 rounded border border-blue-900/40">📅 Hari Ini & Esok</span>
            </div>
            <div class="p-4 bg-[#0a0f14] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-blue-950/20">🎮</div>
        </div>
        
        <div class="bg-[#121a23] p-6 rounded-xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-orange-500/30 transition-all duration-300 hover:-translate-y-0.5">
            <div class="space-y-2">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest font-mono">Database Pelanggan</p>
                <p class="text-2xl font-black text-white font-mono tracking-tight">{{ $totalMember }} <span class="text-xs text-slate-500 font-black uppercase">Akun</span></p>
                <span class="inline-flex text-[9px] font-bold text-orange-400/80 uppercase tracking-wide bg-orange-950/40 px-2 py-0.5 rounded border border-orange-900/40">🏆 Member Loyal Berkasta</span>
            </div>
            <div class="p-4 bg-[#0a0f14] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-orange-950/20">👥</div>
        </div>
    </div>

    <!-- CHART & FAST ACTIONS DECK PANEL -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="bg-[#121a23] lg:col-span-8 rounded-2xl p-6 border border-slate-800 shadow-2xl flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color: var(--color-text-meta);">{{ $metric['label'] }}</p>
                    <p class="f-mono text-2xl font-bold mt-2" style="color: var(--color-text-main);">{{ $metric['value'] }}</p>
                </div>
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
            <div class="relative w-full h-64 flex items-center justify-center bg-[#0a0f14]/30 rounded-xl border border-slate-800/60 p-4">
                <canvas id="dashboardPerformanceChart" class="w-full h-full"></canvas>
            </div>
        </div>

        <div class="bg-[#121a23] lg:col-span-4 rounded-2xl p-6 border border-slate-800 shadow-2xl flex flex-col justify-between space-y-4">
            <div class="border-b border-slate-800 pb-4">
                <h3 class="text-xs font-black text-white uppercase tracking-wider">Aksi Cepat Gerbang</h3>
                <p class="text-[10px] text-slate-500 font-bold mt-0.5 uppercase tracking-wide">Pintas penanganan bypass darurat</p>
            </div>
            
            <div class="flex-1 grid grid-cols-1 gap-2.5">
                <a href="{{ route('admin.reservasi.index', ['status' => 'Waiting Payment']) }}" class="flex items-center justify-between p-3.5 bg-[#0a0f14]/50 hover:bg-[#0a0f14] border border-slate-800/80 rounded-xl transition group">
                    <span class="text-xs font-bold text-slate-300 group-hover:text-white transition">⏳ Tinjau Nota Pending</span>
                    <span class="text-[9px] font-mono font-black bg-amber-950/40 text-amber-400 border border-amber-900/40 px-2 py-0.5 rounded uppercase">Bypass</span>
                </a>
                
                <a href="{{ route('admin.reservasi.exportExcel') }}" class="flex items-center justify-between p-3.5 bg-[#0a0f14]/50 hover:bg-[#0a0f14] border border-slate-800/80 rounded-xl transition group">
                    <span class="text-xs font-bold text-slate-300 group-hover:text-white transition">📊 Backup Excel Bulanan</span>
                    <span class="text-[9px] font-mono font-black bg-emerald-950/40 text-emerald-400 border border-emerald-900/40 px-2 py-0.5 rounded uppercase">Unduh</span>
                </a>

                <a href="{{ route('staff.scan') }}" target="_blank" class="flex items-center justify-between p-3.5 bg-[#0a0f14]/50 hover:bg-[#0a0f14] border border-slate-800/80 rounded-xl transition group">
                    <span class="text-xs font-bold text-slate-300 group-hover:text-white transition">📷 Buka Konsol Scanner QR</span>
                    <span class="text-[9px] font-mono font-black bg-blue-950/40 text-blue-400 border border-blue-900/40 px-2 py-0.5 rounded uppercase">Terminal</span>
                </a>
            </div>
            
            <div class="bg-[#0a0f14]/30 p-3 rounded-lg border border-slate-800/40 text-[9px] font-mono font-bold text-slate-600 text-center uppercase tracking-wider">
                Keamanan enkripsi data SSL aktif
            </div>
        </div>
        @endforeach
    </div>

    <!-- HISTORI RECENT TRANSACTIONS TABLE -->
    <div class="bg-[#121a23] rounded-2xl shadow-2xl border border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-[#0f172a]/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#e25e20] animate-pulse"></span> Aliran Data Transaksi Terakhir
                </h2>
                <p class="text-[10px] text-slate-500 font-bold mt-0.5 uppercase tracking-wide">Log pemantauan 10 entri data booking terbaru</p>
            </div>
            <span class="self-start sm:self-auto text-[9px] font-mono font-black bg-[#0a0f14] text-slate-400 border border-slate-800 px-2.5 py-1 rounded-md uppercase tracking-wider">Live Stream Ready</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-wider bg-[#0f172a]/20">
                        <th class="p-6">Detail Arena</th>
                        <th class="p-6">Nomor Order</th>
                        <th class="p-6">Identitas Pelanggan</th>
                        <th class="p-6">Waktu Slot Bertanding</th>
                        <th class="p-6">Total Bayar</th>
                        <th class="p-6">Status Gerbang</th>
                        <th class="p-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs font-bold text-slate-300 bg-[#121a23]">
                    @forelse($reservasis as $reservasi)
                    <tr class="hover:bg-[#0a0f14]/30 transition duration-150">
                        <td class="p-6">
                            <div class="font-black text-white text-sm uppercase tracking-tight">{{ $reservasi->lapangan->nama_lapangan ?? 'N/A' }}</div>
                            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5 font-mono">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                        </td>
                        
                        <td class="p-6 font-mono text-slate-400 uppercase tracking-wider">{{ $reservasi->nomor_reservasi }}</td>
                        
                        <td class="p-6">
                            <div class="text-white text-sm font-bold tracking-tight">{{ $reservasi->user->name ?? 'Guest User' }}</div>
                            
                            @if($reservasi->user && $reservasi->user->membership)
                                @if($reservasi->user->membership->membership_type == 'Gold')
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 font-mono px-2 py-0.5 rounded uppercase shadow-sm">🏆 Gold</span>
                                @elseif($reservasi->user->membership->membership_type == 'Silver')
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-gradient-to-r from-slate-500 to-slate-400 text-white font-mono px-2 py-0.5 rounded uppercase shadow-sm">🥈 Silver</span>
                                @else
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-gradient-to-r from-amber-800 to-amber-700 text-white font-mono px-2 py-0.5 rounded uppercase shadow-sm">🥉 Bronze</span>
                                @endif
                            @else
                                <span class="inline-block mt-1 text-[8px] font-bold tracking-widest bg-[#0a0f14] text-slate-500 border border-slate-800 px-2 py-0.5 rounded uppercase font-mono">Non-Member</span>
                            @endif
                        </td>
                        
                        <td class="p-6 text-slate-300">
                            <div class="text-slate-200">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}</div>
                            <div class="font-mono text-slate-500 text-[10px] mt-0.5 uppercase tracking-wide">⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA</div>
                        </td>
                        
                        <td class="p-6 font-black text-white text-sm font-mono">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                        
                        <td class="p-6">
                            @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 uppercase tracking-wider font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Confirmed
                                </span>
                            @elseif($reservasi->status == 'Cancelled')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-black bg-red-950/60 text-red-400 border border-red-900/40 uppercase tracking-wider font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Cancelled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[9px] font-black bg-amber-950/60 text-amber-400 border border-amber-900/40 uppercase tracking-wider font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @endif
                        </td>
                        
                        <td class="p-6 text-center">
                            <a href="{{ route('admin.reservasi.index', ['status' => $reservasi->status]) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-[#0a0f14] border border-slate-800 hover:border-slate-700 text-slate-300 rounded-lg text-[10px] font-black uppercase tracking-wider transition duration-150 shadow-sm font-mono">
                                Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-500 font-bold uppercase tracking-wider text-xs bg-[#121a23]">
                            ⚽ Belum ada entri log reservasi yang tercatat di sistem database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reservasis->hasPages())
            <div class="p-6 border-t border-slate-800 bg-[#0f172a]/20 data-dark-pagination">
                {{ $reservasis->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('dashboardPerformanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Utilisasi Lapangan (Jam)',
                    data: [12, 19, 15, 25, 32, 45, 48],
                    borderColor: '#e25e20',
                    backgroundColor: 'rgba(226, 94, 32, 0.03)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#e25e20',
                    pointRadius: 3
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
                        grid: { color: 'rgba(255, 255, 255, 0.02)' },
                        ticks: { color: '#5c6979', font: { size: 9, weight: 'bold', family: 'JetBrains Mono' } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.02)' },
                        ticks: { color: '#5c6979', font: { size: 9, weight: 'bold', family: 'JetBrains Mono' } }
                    }
                }
            }
        });
    </script>
@endsection