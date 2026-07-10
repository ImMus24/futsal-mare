@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    
    <!-- 0. TOP NAVIGATION & GLOBAL BACK BUTTON -->
    <div class="flex items-center justify-between">
        <a href="/" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition group tracking-wide uppercase">
            <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Beranda Utama
        </a>
        <div class="text-[10px] font-mono font-bold text-slate-600 tracking-wider uppercase">Sistem Kontrol Panel Admin</div>
    </div>

    <!-- 1. WELCOME HEADER PANEL -->
    <div class="bg-[#152238] p-6 rounded-3xl border border-slate-800 shadow-2xl flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight uppercase">Sistem Overview Arena</h1>
            <p class="text-slate-400 text-xs font-semibold mt-1">Data terkini operasional inventaris arena dan status gerbang pembayaran Midtrans.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.reservasi.index') }}" class="px-4 py-2.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 font-black text-[10px] rounded-xl uppercase tracking-wider transition">Log Reservasi</a>
            <a href="{{ route('admin.lapangan.index') }}" class="px-4 py-2.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 font-black text-[10px] rounded-xl uppercase tracking-wider transition">Kelola Arena</a>
            <a href="{{ route('admin.member.index') }}" class="px-4 py-2.5 bg-[#0B131F] border border-slate-800 hover:border-slate-700 text-slate-300 font-black text-[10px] rounded-xl uppercase tracking-wider transition">Data Member</a>
        </div>
    </div>

    <!-- 2. METRICS STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pendapatan Card -->
        <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-emerald-800/50 transition duration-300">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Pendapatan Lunas</p>
                <p class="text-2xl font-black text-[#22C55E] font-mono">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl group-hover:scale-110 transition duration-300">🪙</div>
        </div>
        
        <!-- Sesi Card -->
        <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-blue-800/50 transition duration-300">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Match Terjadwal (Aktif)</p>
                <p class="text-2xl font-black text-blue-400 font-mono">{{ $matchTerkonfirmasi }} <span class="text-xs text-slate-500 font-black uppercase">Slot</span></p>
            </div>
            <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl group-hover:scale-110 transition duration-300">📅</div>
        </div>
        
        <!-- Member Card -->
        <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-orange-800/50 transition duration-300">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Pelanggan Terdaftar</p>
                <p class="text-2xl font-black text-white font-mono">{{ $totalMember }} <span class="text-xs text-slate-500 font-black uppercase">User</span></p>
            </div>
            <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl group-hover:scale-110 transition duration-300">👥</div>
        </div>
    </div>

    <!-- 3. MONITORING DATA TABLE -->
    <div class="bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-[#0F172A]/40 flex items-center justify-between">
            <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#E25E20] animate-pulse"></span> Monitoring Booking Real-time
            </h2>
            <span class="text-[9px] font-black bg-[#0B131F] text-slate-400 border border-slate-800 px-2.5 py-1 rounded-md uppercase tracking-wider">Real-time Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-wider bg-[#0f172a]/20">
                        <th class="p-6">Detail Lapangan</th>
                        <th class="p-6">Nomor Order</th>
                        <th class="p-6">Pelanggan</th>
                        <th class="p-6">Waktu Slot Main</th>
                        <th class="p-6">Total Bayar</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-xs font-bold text-slate-300 bg-[#152238]">
                    @foreach($reservasis as $reservasi)
                    <tr class="hover:bg-[#0B131F]/30 transition duration-150">
                        <!-- Detail Arena -->
                        <td class="p-6">
                            <div class="font-black text-white text-sm uppercase tracking-tight">{{ $reservasi->lapangan->nama_lapangan ?? 'N/A' }}</div>
                            <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                        </td>
                        
                        <!-- Nomor Transaksi -->
                        <td class="p-6 font-mono text-slate-400 uppercase tracking-wider">{{ $reservasi->nomor_reservasi }}</td>
                        
                        <!-- Nama User + INTEGRASI BADGE TIER MEMBERSHIP -->
                        <td class="p-6">
                            <div class="text-white text-sm font-bold tracking-tight">{{ $reservasi->user->name ?? 'Guest User' }}</div>
                            
                            @if($reservasi->user && $reservasi->user->membership)
                                @if($reservasi->user->membership->membership_type == 'Gold')
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-amber-500 text-white px-2 py-0.5 rounded uppercase shadow-sm shadow-amber-500/10">🏆 Gold</span>
                                @elseif($reservasi->user->membership->membership_type == 'Silver')
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-slate-500 text-white px-2 py-0.5 rounded uppercase shadow-sm shadow-slate-500/10">🥈 Silver</span>
                                @else
                                    <span class="inline-block mt-1 text-[8px] font-black tracking-widest bg-amber-800 text-white px-2 py-0.5 rounded uppercase shadow-sm shadow-amber-800/10">🥉 Bronze</span>
                                @endif
                            @else
                                <span class="inline-block mt-1 text-[8px] font-bold tracking-widest bg-[#0B131F] text-slate-500 border border-slate-800 px-2 py-0.5 rounded uppercase">Non-Member</span>
                            @endif
                        </td>
                        
                        <!-- Waktu Pertandingan -->
                        <td class="p-6 text-slate-300">
                            {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}<br>
                            <small class="font-mono text-slate-500">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</small>
                        </td>
                        
                        <!-- Nominal Biaya -->
                        <td class="p-6 font-black text-white text-sm font-mono">Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</td>
                        
                        <!-- Status Transaksi -->
                        <td class="p-6">
                            @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                </span>
                            @elseif($reservasi->status == 'Cancelled')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-red-950/60 text-red-400 border border-red-900/40 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Batal
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black bg-amber-950/60 text-amber-400 border border-amber-900/40 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @endif
                        </td>
                        
                        <!-- Aksi Tombol Detail -->
                        <td class="p-6 text-center">
                            <a href="{{ route('admin.reservasi.index', ['status' => $reservasi->status]) }}" class="inline-block px-3 py-2 bg-[#0B131F] border border-slate-800 text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-[#1A3D63] hover:border-transparent transition duration-150 shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($reservasis->hasPages())
            <div class="p-6 border-t border-slate-800 bg-[#0F172A]/20 data-dark-pagination">
                {{ $reservasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection