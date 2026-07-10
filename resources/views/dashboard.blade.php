<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth">

    <nav class="bg-[#0F172A]/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain transform group-hover:rotate-6 transition duration-300">
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <div class="flex items-center space-x-6">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#E25E20]">Member Aktif</span>
                    <span class="text-xs text-slate-300 font-bold mt-0.5">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 border border-red-500/30 text-red-400 hover:bg-red-500/10 hover:text-red-500 text-xs font-black rounded-xl uppercase tracking-wider transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-[#152238] p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none opacity-[0.02] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:16px_16px]"></div>
            
            <div class="relative z-10 space-y-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                    <p class="text-slate-400 text-xs sm:text-sm font-medium mt-1">Pantau jadwal tanding tim Anda dan dapatkan keuntungan prioritas booking.</p>
                </div>
                
                @php
                    $membership = Auth::user()->membership ?? (object)['membership_type' => 'Bronze', 'points' => 0];
                    $tierColors = [
                        'Gold' => 'bg-amber-500 text-white border-amber-600 shadow-amber-500/20',
                        'Silver' => 'bg-slate-500 text-white border-slate-600 shadow-slate-500/20',
                        'Bronze' => 'bg-amber-800 text-white border-amber-900 shadow-amber-800/20'
                    ];
                @endphp
                <div class="inline-flex items-center gap-3 bg-[#0B131F] border border-slate-800 rounded-2xl p-2.5 pr-4">
                    <span class="px-3 py-1 text-[10px] font-black rounded-xl border uppercase tracking-wider shadow-sm {{ $tierColors[$membership->membership_type] ?? $tierColors['Bronze'] }}">
                        🏆 Tier {{ $membership->membership_type }}
                    </span>
                    <span class="text-xs font-bold text-slate-300">
                        ⭐ {{ $membership->points }} Loyalty Points
                    </span>
                    @if($membership->membership_type === 'Gold')
                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest animate-pulse">• Akses Prioritas Aktif</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('landingPage') }}" class="inline-flex items-center justify-center px-6 py-4 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl shadow-lg shadow-orange-950/40 tracking-widest uppercase transition-all duration-200 transform hover:-translate-y-0.5 relative z-10">
                + Sewa Lapangan Lagi
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-950/40 border border-emerald-800/60 text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm animate-fade-in">
                <span class="text-sm">✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-950/40 border border-red-800/60 text-red-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm animate-fade-in">
                <span class="text-sm">⚠️</span> {{ session('error') }}
            </div>
        @endif

        @php
            $totalBooking = $reservasis->count();
            $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
            $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-lg flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Total Reservasi</p>
                    <p class="text-3xl font-black text-white">{{ $totalBooking }} <span class="text-xs text-slate-500 font-bold uppercase">Match</span></p>
                </div>
                <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl">📅</div>
            </div>
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-lg flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Jadwal Terkonfirmasi</p>
                    <p class="text-3xl font-black text-emerald-400">{{ $lunasBooking }} <span class="text-xs text-slate-500 font-bold uppercase">Match</span></p>
                </div>
                <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl">✅</div>
            </div>
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-lg flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Total Kontribusi Sewa</p>
                    <p class="text-2xl font-black text-[#22C55E]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-xl">🪙</div>
            </div>
        </div>

        <div class="bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-800 bg-[#0F172A]/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#E25E20]"></span> Riwayat Reservasi Lapangan
                </h2>
                
                <div class="flex items-center gap-3">
                    <div id="bulk_action_panel" class="hidden items-center gap-2 bg-[#0B131F] border border-slate-800 px-3 py-1.5 rounded-xl animate-fade-in">
                        <span id="selected_count" class="text-[10px] font-mono font-black bg-slate-800 text-slate-300 px-2 py-0.5 rounded-md">0</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dipilih</span>
                        <button type="submit" form="bulk_delete_form" class="ml-2 px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-black text-[10px] rounded-lg uppercase tracking-wider shadow transition">
                            🗑️ Hapus Massal
                        </button>
                    </div>
                    <span class="text-[9px] font-black bg-[#0B131F] text-slate-400 border border-slate-800 px-2.5 py-1 rounded-md uppercase tracking-wider">Real-time Data</span>
                </div>
            </div>

            <form id="bulk_delete_form" action="{{ route('reservasi.destroyMassal') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua riwayat transaksi terpilih sekaligus?')">
                @csrf
                @method('DELETE')
            </form>

            @foreach($reservasis as $reservasi)
                @if(strtolower($reservasi->status) == 'waiting payment')
                    <form id="form_batal_{{ $reservasi->id }}" action="{{ route('reservasi.batal', $reservasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal reservasi match ini? Status akan otomatis berubah menjadi Batal.')">
                        @csrf
                    </form>
                @endif
            @endforeach

            @if($reservasis->isEmpty())
                <div class="p-16 text-center space-y-2 bg-[#152238]">
                    <div class="text-3xl">🏃‍♂️</div>
                    <p class="text-slate-500 font-bold text-sm">Anda belum memiliki riwayat reservasi jadwal apa pun.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-wider bg-[#0f172a]/20">
                                <th class="p-6 w-10">
                                    <input type="checkbox" id="check_all_master" class="rounded border-slate-800 bg-[#0B131F] text-[#E25E20] focus:ring-[#E25E20] focus:ring-offset-[#152238]">
                                </th>
                                <th class="p-6">Detail Lapangan</th>
                                <th class="p-6">Nomor Reservasi</th>
                                <th class="p-6">Tanggal Main</th>
                                <th class="p-6">Waktu Slot</th>
                                <th class="p-6">Metode Bayar</th>
                                <th class="p-6">Total Bayar</th>
                                <th class="p-6">Status Pembayaran</th>
                                <th class="p-6 text-center">Aksi Administrasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-xs font-bold text-slate-300 bg-[#152238]">
                            @foreach($reservasis as $reservasi)
                                <tr class="hover:bg-[#0B131F]/30 transition duration-150 data-row">
                                    <td class="p-6">
                                        @if(strtolower($reservasi->status) != 'waiting payment')
                                            <input type="checkbox" name="ids[]" value="{{ $reservasi->id }}" form="bulk_delete_form" class="row-checkbox rounded border-slate-800 bg-[#0B131F] text-[#E25E20] focus:ring-[#E25E20] focus:ring-offset-[#152238]">
                                        @else
                                            <input type="checkbox" disabled class="rounded border-slate-900 bg-slate-900/40 text-slate-700 cursor-not-allowed opacity-30" title="Selesaikan pembayaran atau proses verifikasi dahulu">
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="font-black text-white text-sm uppercase tracking-tight">{{ $reservasi->lapangan->nama_lapangan }}</div>
                                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                                    </td>
                                    <td class="p-6 font-mono text-slate-400 uppercase tracking-wider">
                                        {{ $reservasi->nomor_reservasi }}
                                    </td>
                                    <td class="p-6 font-semibold text-slate-300">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="p-6 font-mono text-xs text-slate-400">
                                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                                    </td>
                                    <td class="p-6">
                                        <span class="inline-block px-2 py-1 bg-[#0B131F] text-slate-400 border border-slate-800 rounded-md font-black uppercase tracking-wide text-[9px]">
                                            {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'Gateway') }}
                                        </span>
                                    </td>
                                    <td class="p-6 font-black text-white text-sm">
                                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                    </td>
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
                                    <td class="p-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(strtolower($reservasi->status) == 'waiting payment')
                                                <button type="submit" form="form_batal_{{ $reservasi->id }}" class="px-3 py-2 bg-[#0B131F] hover:bg-red-950/30 text-red-400 border border-slate-800 hover:border-red-900/40 font-black text-[10px] rounded-xl uppercase tracking-wider transition duration-150">
                                                    ❌ Batalkan Booking
                                                </button>
                                            @elseif($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                                <a href="{{ route('reservasi.tiket', $reservasi->id) }}" target="_blank"
                                                   class="inline-block px-3 py-2 bg-[#0B131F] border border-slate-800 hover:bg-[#1A3D63] text-white font-black text-[10px] rounded-xl uppercase tracking-wider shadow-sm transition duration-150">
                                                    🎟️ Tiket
                                                </a>
                                            @else
                                                <span class="text-[10px] font-black text-slate-600">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const masterCheck = document.getElementById('check_all_master');
            const rowChecks = document.querySelectorAll('.row-checkbox');
            const actionPanel = document.getElementById('bulk_action_panel');
            const selectedCount = document.getElementById('selected_count');

            if (!masterCheck) return;

            masterCheck.addEventListener('change', function () {
                rowChecks.forEach(checkbox => {
                    checkbox.checked = masterCheck.checked;
                    toggleRowStyle(checkbox);
                });
                refreshPanelVisibility();
            });

            rowChecks.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    toggleRowStyle(checkbox);
                    if (!checkbox.checked) masterCheck.checked = false;
                    refreshPanelVisibility();
                });
            });

            function toggleRowStyle(checkbox) {
                const row = checkbox.closest('.data-row');
                if (checkbox.checked) {
                    row.classList.add('bg-slate-800/20');
                } else {
                    row.classList.remove('bg-slate-800/20');
                }
            }

            function refreshPanelVisibility() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                selectedCount.innerText = checkedCount;

                if (checkedCount > 0) {
                    actionPanel.classList.remove('hidden');
                    actionPanel.classList.add('flex');
                } else {
                    actionPanel.classList.remove('flex');
                    actionPanel.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>