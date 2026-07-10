<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Reservasi - Futsal Mare Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] text-slate-200 font-sans antialiased">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition group tracking-wide uppercase">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
            </a>
            <div class="text-[10px] font-mono font-bold text-slate-600 tracking-wider uppercase">Data Audit Transaksi</div>
        </div>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-[#152238] p-6 rounded-3xl border border-slate-800 shadow-2xl">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase">📅 Log Data Reservasi</h1>
                <p class="text-xs text-slate-400 mt-1">Pantau jadwal masuk, kendalikan status pembayaran, dan lakukan audit transaksi lapangan.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reservasi.exportExcel', ['status' => request('status')]) }}" class="px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-lg shadow-emerald-950/40 tracking-wider uppercase transition flex items-center gap-2">
                    📊 Unduh Excel (.xls)
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-950/40 border border-emerald-800/60 text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-md">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#152238] p-4 rounded-2xl border border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.reservasi.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <select name="status" onchange="this.form.submit()" class="bg-[#0B131F] border border-slate-800 rounded-xl text-xs font-bold text-slate-300 px-4 py-2.5 focus:border-[#E25E20] focus:ring-0 min-w-[220px] cursor-pointer">
                    <option value="">Status Transaksi (Semua)</option>
                    <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>🟢 Berhasil (Confirmed)</option>
                    <option value="Waiting Payment" {{ request('status') == 'Waiting Payment' ? 'selected' : '' }}>🟡 Menunggu Pembayaran</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>🔵 Selesai Main (Completed)</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>❌ Dibatalkan (Cancelled)</option>
                </select>
            </form>
            <div class="text-[11px] font-bold text-slate-400">
                Menampilkan <span class="text-white font-black">{{ $reservasis->total() }}</span> entri records lapangan.
            </div>
        </div>

        <div class="bg-[#152238] rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#0F172A] border-b border-slate-800/60 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <th class="py-4 px-6">ID / Transaksi</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Arena</th>
                            <th class="py-4 px-6">Jadwal Tanding</th>
                            <th class="py-4 px-6 text-right">Total Bayar</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-center">Aksi Pengelolaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-xs font-semibold">
                        @forelse($reservasis as $reservasi)
                            <tr class="hover:bg-[#0B131F]/30 transition duration-150">
                                <td class="py-4 px-6">
                                    <span class="text-white font-mono font-bold block">{{ $reservasi->nomor_reservasi }}</span>
                                    <span class="text-[10px] text-slate-500 font-mono block mt-0.5">ID Record: #{{ $reservasi->id }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-white font-bold block uppercase tracking-wide">{{ $reservasi->user->name ?? 'User Terhapus' }}</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">{{ $reservasi->user->email ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-[#E25E20] font-black uppercase tracking-wide">{{ $reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus' }}</span>
                                    <span class="text-[10px] text-slate-500 block mt-0.5">🌱 {{ $reservasi->lapangan->jenis_rumput ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-slate-200 block">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d F Y') }}</span>
                                    <span class="text-[10px] text-emerald-400 font-bold block mt-0.5">⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA</span>
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-white font-mono">
                                    Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($reservasi->status == 'Confirmed')
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 rounded-lg uppercase tracking-wider">🟢 Lunas (Confirmed)</span>
                                    @elseif($reservasi->status == 'Waiting Payment')
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-amber-950/60 text-amber-400 border border-amber-900/40 rounded-lg uppercase tracking-wider">🟡 Waiting Payment</span>
                                    @elseif($reservasi->status == 'Completed')
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-blue-950/60 text-blue-400 border border-blue-900/40 rounded-lg uppercase tracking-wider">🔵 Completed</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-red-950/60 text-red-400 border border-red-900/40 rounded-lg uppercase tracking-wider">❌ Cancelled</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 flex items-center justify-center gap-3">
                                    <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="bg-[#0B131F] border border-slate-800 text-[11px] font-bold text-slate-300 rounded-lg px-2 py-1.5 focus:border-[#E25E20] focus:ring-0 cursor-pointer">
                                            <option value="" disabled selected>⚙️ Ubah Status</option>
                                            <option value="Confirmed">Lunas (Confirmed)</option>
                                            <option value="Waiting Payment">Waiting Payment</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </form>

                                    <form action="{{ route('admin.reservasi.delete', $reservasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus record data reservasi ini dari log sistem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-950/30 border border-red-900/40 text-red-400 hover:bg-red-900/50 text-[11px] font-bold rounded-lg transition" title="Hapus Record">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500 font-bold">
                                    <div class="text-2xl mb-1">📅</div>
                                    Tidak ditemukan catatan data reservasi yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservasis->hasPages())
                <div class="p-4 bg-[#0F172A]/40 border-t border-slate-800">
                    {{ $reservasis->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>