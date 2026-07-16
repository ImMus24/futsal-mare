<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth selection:bg-[#E25E20] selection:text-white">

    <nav class="bg-[#0F172A]/70 backdrop-blur-xl shadow-2xl sticky top-0 z-50 border-b border-slate-800/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                    <div class="relative flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#E25E20] to-orange-500 rounded-xl filter blur-md opacity-20 group-hover:opacity-40 transition duration-300"></div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Futsal Mare" class="h-12 w-auto object-contain transform group-hover:rotate-6 transition duration-300 relative z-10">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                        <span class="text-[10px] font-black text-[#E25E20] tracking-[0.3em] uppercase mt-0.5">Mare</span>
                    </div>
                </a>

                <div class="flex items-center space-x-6">
                    <div class="hidden sm:flex flex-col text-right border-r border-slate-800/80 pr-4">
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#E25E20]">Sesi Member Aktif</span>
                        <span class="text-xs text-white font-bold mt-0.5">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-950/20 border border-red-950/40 text-red-400 text-[10px] font-black rounded-xl uppercase tracking-wider hover:bg-red-900/30 transition duration-200">
                            Keluar Sesi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 animate-fade-in">
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-gradient-to-r from-[#152238] via-[#1a2d4b] to-[#0B131F] p-6 sm:p-8 rounded-3xl border border-slate-800/80 shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-16 -top-16 w-32 h-32 bg-[#E25E20] rounded-full filter blur-[80px] opacity-5"></div>
            <div class="absolute inset-0 pointer-events-none opacity-[0.01] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:16px_16px]"></div>
            
            <div class="relative z-10 space-y-3.5">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight uppercase">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                    <p class="text-slate-400 text-xs sm:text-sm font-medium mt-1">Pantau jadwal tanding tim Anda dan dapatkan keuntungan prioritas booking arena.</p>
                </div>
                
                @php
                    $membership = Auth::user()->membership ?? (object)['membership_type' => 'Bronze', 'points' => 0];
                    $tierColors = [
                        'Gold' => 'from-amber-500 to-yellow-400 text-white border-amber-600 shadow-amber-500/10',
                        'Silver' => 'from-slate-500 to-slate-400 text-white border-slate-600 shadow-slate-500/10',
                        'Bronze' => 'from-amber-800 to-amber-700 text-white border-amber-900 shadow-amber-800/10'
                    ];
                @endphp
                <div class="inline-flex items-center gap-3 bg-[#0B131F]/60 border border-slate-800 rounded-2xl p-2.5 pr-4 backdrop-blur-sm">
                    <span class="px-3 py-1 text-[9px] font-black rounded-xl border uppercase tracking-widest shadow-md bg-gradient-to-r {{ $tierColors[$membership->membership_type] ?? $tierColors['Bronze'] }}">
                        🏆 Tier {{ $membership->membership_type }}
                    </span>
                    <span class="text-xs font-black text-slate-300 font-mono">
                        ⭐ {{ $membership->points }} Loyalty Points
                    </span>
                    @if($membership->membership_type === 'Gold')
                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest animate-pulse border border-amber-900/40 bg-amber-950/30 px-2 py-0.5 rounded-md">• Prioritas Aktif</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('landingPage') }}" class="w-full lg:w-auto inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-[#E25E20] to-orange-600 hover:from-[#cb5119] hover:to-orange-700 text-white font-black text-xs rounded-xl shadow-xl shadow-orange-950/40 tracking-widest uppercase transition-all duration-200 transform hover:-translate-y-0.5 relative z-10">
                + Sewa Lapangan Lagi
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-950/40 border border-emerald-800/60 text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-md animate-pulse">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-950/40 border border-red-800/60 text-red-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-md">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        @if($membership->points == 0 && $membership->membership_type === 'Bronze')
            <div class="bg-gradient-to-r from-[#1A2E4C]/60 to-[#0F172A]/90 p-5 rounded-2xl border border-dashed border-slate-700/60 shadow-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in relative overflow-hidden group">
                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-gradient-to-tr from-amber-500 to-yellow-500 rounded-full filter blur-[40px] opacity-5 group-hover:opacity-10 transition duration-500"></div>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-[#0B131F] border border-slate-800 rounded-xl text-lg shadow-inner select-none">🎁</div>
                    <div class="space-y-0.5">
                        <h4 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1.5">
                            Mulai Petualangan Tim Anda! <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-ping"></span>
                        </h4>
                        <p class="text-[11px] text-slate-400 leading-relaxed max-w-3xl">
                            Status Anda saat ini adalah <span class="text-amber-600 font-black">Bronze Member</span>. Kumpulkan poin dengan melakukan booking lapangan! Setiap transaksi sukses bernilai <span class="text-white font-bold font-mono">+10 Poin</span>, mendekatkan Anda ke tingkatan <span class="text-slate-300 font-bold">Silver (100 Poin)</span> atau <span class="text-amber-400 font-bold">Gold (300 Poin)</span> untuk menikmati diskon sewa otomatis hingga 10%.
                        </p>
                    </div>
                </div>
                <button type="button" onclick="alert('💡 INFO TIER MEMBERSHIP FUTSAL MARE:\n\n🥉 Bronze (Awal): Akumulasi poin aktif.\n🥈 Silver (100 Poin): Diskon otomatis 5% setiap sewa.\n🏆 Gold (300 Poin): Diskon otomatis 10% + Akses sistem prioritas booking 24/7!')" class="shrink-0 text-center px-4 py-2.5 bg-[#0B131F] border border-slate-800 hover:border-transparent hover:bg-[#E25E20] text-slate-300 hover:text-white font-black text-[10px] rounded-xl uppercase tracking-wider transition-all duration-200">
                    Pelajari Benefit Tier &rarr;
                </button>
            </div>
        @endif

        @php
            $totalBooking = $reservasis->count();
            $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
            $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-slate-700 transition duration-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Akumulasi Reservasi</p>
                    <p class="text-3xl font-black text-white font-mono tracking-tight">{{ $totalBooking }} <span class="text-xs text-slate-500 font-black uppercase">Slot</span></p>
                </div>
                <div class="p-4 bg-[#0B131F] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300">📅</div>
            </div>
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-emerald-500/30 transition duration-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Match Terkonfirmasi</p>
                    <p class="text-3xl font-black text-emerald-400 font-mono tracking-tight">{{ $lunasBooking }} <span class="text-xs text-slate-500 font-black uppercase">Match</span></p>
                </div>
                <div class="p-4 bg-[#0B131F] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-emerald-950/30">✅</div>
            </div>
            <div class="bg-[#152238] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between group hover:border-blue-500/30 transition duration-300">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kontribusi Finansial</p>
                    <p class="text-2xl font-black text-blue-400 font-mono tracking-tight">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-[#0B131F] border border-slate-800 rounded-xl text-xl shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-blue-950/30">🪙</div>
            </div>
        </div>

        <div id="riwayat-tabel" class="bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-800 bg-[#0F172A]/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#E25E20] animate-pulse"></span> Aliran Histori Transaksi Anda
                </h2>
                
                <div class="flex items-center gap-3">
                    <div id="bulk_action_panel" class="hidden items-center gap-2 bg-[#0B131F] border border-slate-800 px-3 py-1.5 rounded-xl animate-fade-in shadow-inner">
                        <span id="selected_count" class="text-[10px] font-mono font-black bg-slate-800 text-slate-300 px-2 py-0.5 rounded-md">0</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Terpilih</span>
                        <button type="submit" form="bulk_delete_form" class="ml-2 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-black text-[9px] rounded-lg uppercase tracking-wider shadow-md transition duration-150">
                            🗑️ Hapus Sekaligus
                        </button>
                    </div>
                    <span class="text-[9px] font-black bg-[#0B131F] text-slate-400 border border-slate-800 px-2.5 py-1 rounded-md uppercase tracking-wider">Live Sync Ready</span>
                </div>
            </div>

            <form id="bulk_delete_form" action="{{ route('reservasi.destroyMassal') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua riwayat transaksi terpilih sekaligus dari sistem?')">
                @csrf
                @method('DELETE')
            </form>

            @foreach($reservasis as $reservasi)
                @if(strtolower($reservasi->status) == 'waiting payment')
                    <form id="form_batal_{{ $reservasi->id }}" action="{{ route('reservasi.batal', $reservasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal reservasi match ini? Status akan otomatis bermutasi menjadi Batal.')">
                        @csrf
                    </form>
                @endif
            @endforeach

            @if($reservasis->isEmpty())
                <div class="p-16 text-center space-y-2 bg-[#152238]">
                    <div class="text-3xl animate-bounce">🏃‍♂️</div>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wide">Anda belum memiliki riwayat data reservasi aktif.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 text-[10px] font-black uppercase tracking-wider bg-[#0f172a]/20">
                                <th class="p-6 w-10">
                                    <input type="checkbox" id="check_all_master" class="rounded border-slate-800 bg-[#0B131F] text-[#E25E20] focus:ring-0 focus:ring-offset-0 w-4 h-4 cursor-pointer">
                                </th>
                                <th class="p-6">Detail Arena</th>
                                <th class="p-6">Nomor Order</th>
                                <th class="p-6">Tanggal Main</th>
                                <th class="p-6">Waktu Slot</th>
                                <th class="p-6">Metode Bayar</th>
                                <th class="p-6">Total Tagihan</th>
                                <th class="p-6">Status Gerbang</th>
                                <th class="p-6 text-center">Konsol Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-xs font-bold text-slate-300 bg-[#152238]">
                            @foreach($reservasis as $reservasi)
                                <tr class="hover:bg-[#0B131F]/40 transition duration-150 data-row">
                                    <td class="p-6">
                                        @if(strtolower($reservasi->status) != 'waiting payment')
                                            <input type="checkbox" name="ids[]" value="{{ $reservasi->id }}" form="bulk_delete_form" class="row-checkbox rounded border-slate-800 bg-[#0B131F] text-[#E25E20] focus:ring-0 focus:ring-offset-0 w-4 h-4 cursor-pointer">
                                        @else
                                            <input type="checkbox" disabled class="rounded border-slate-900 bg-slate-900/30 text-slate-700 cursor-not-allowed opacity-25" title="Selesaikan tagihan Midtrans Anda terlebih dahulu">
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="font-black text-white text-sm uppercase tracking-tight">{{ $reservasi->lapangan->nama_lapangan }}</div>
                                        <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-0.5">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                                    </td>
                                    <td class="p-6 font-mono text-slate-400 uppercase tracking-wider">
                                        {{ $reservasi->nomor_reservasi }}
                                    </td>
                                    <td class="p-6 font-semibold text-slate-200">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="p-6 font-mono text-xs text-blue-400">
                                        ⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA
                                    </td>
                                    <td class="p-6">
                                        <span class="inline-block px-2.5 py-1 bg-[#0B131F] text-slate-400 border border-slate-800 rounded-lg font-black uppercase tracking-wide text-[9px]">
                                            {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'Midtrans') }}
                                        </span>
                                    </td>
                                    <td class="p-6 font-black text-white text-sm font-mono">
                                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="p-6">
                                        @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Confirmed
                                            </span>
                                        @elseif($reservasi->status == 'Cancelled')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black bg-red-950/60 text-red-400 border border-red-900/40 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Cancelled
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black bg-amber-950/60 text-amber-400 border border-amber-900/40 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(strtolower($reservasi->status) == 'waiting payment')
                                                <button type="submit" form="form_batal_{{ $reservasi->id }}" class="px-3 py-1.5 bg-[#0B131F] hover:bg-red-950/30 text-red-400 border border-slate-800 hover:border-red-900/30 font-black text-[10px] rounded-xl uppercase tracking-wider transition duration-150">
                                                    ❌ Batalkan Match
                                                </button>
                                            @elseif($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                                <a href="{{ route('reservasi.tiket', $reservasi->id) }}" target="_blank"
                                                   class="inline-flex items-center justify-center px-3 py-1.5 bg-[#0B131F] border border-slate-800 hover:bg-[#1A3D63] text-white font-black text-[10px] rounded-xl uppercase tracking-wider shadow-sm transition duration-150">
                                                    🎟️ Unduh Tiket
                                                </a>
                                            @else
                                                <span class="text-[10px] font-mono text-slate-600 font-bold uppercase tracking-wider">No Action</span>
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
                    row.classList.add('bg-slate-800/30');
                } else {
                    row.classList.remove('bg-slate-800/30');
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