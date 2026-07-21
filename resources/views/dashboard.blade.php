<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-2: #1a2431;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --turf-dark: #cb5119;
            --turf-glow: rgba(226, 94, 32, 0.2);
            --floodlight: #f5c518;
            --floodlight-dim: rgba(245, 197, 24, 0.15);
            --line: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        .wrap { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        h1, h2, h3, h4 { font-family: var(--display); letter-spacing: .01em; text-transform: uppercase; }
        
        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 600; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; transition: transform .15s ease, background .15s ease;
            font-family: var(--body); text-transform: uppercase; letter-spacing: .05em;
        }
        .btn-ui:active { transform: scale(.97); }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-ghost { background: transparent; border-color: rgba(238, 241, 234, 0.25); color: var(--line); }
        .btn-ui-ghost:hover { border-color: var(--line); }
        .btn-ui-danger { background: rgba(226, 87, 76, 0.15); border-color: rgba(226, 87, 76, 0.3); color: #e2574c; }
        .btn-ui-danger:hover { background: rgba(226, 87, 76, 0.25); }
        .btn-ui-sm { padding: 8px 14px; font-size: 12px; border-radius: 6px; }

        table.brutal-data { width: 100%; border-collapse: collapse; }
        table.brutal-data th { text-align: left; font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .05em; padding: 12px 16px; border-bottom: 1px solid rgba(238, 241, 234, 0.1); background: rgba(15, 23, 42, 0.2); }
        table.brutal-data td { padding: 16px; border-bottom: 1px solid rgba(238, 241, 234, 0.06); font-size: 13px; font-weight: 500; }
        table.brutal-data tr:last-child td { border-bottom: none; }

        .badge-brutal { font-family: var(--mono); font-size: 11px; padding: 4px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px; }
        .badge-brutal-pending { background: rgba(245, 197, 24, 0.15); color: var(--floodlight); border: 1px solid rgba(245, 197, 24, 0.25); }
        .badge-brutal-confirmed { background: rgba(47, 158, 88, 0.18); color: #2f9e58; border: 1px solid rgba(47, 158, 88, 0.25); }
        .badge-brutal-cancelled { background: rgba(226, 87, 76, 0.15); color: #e2574c; border: 1px solid rgba(226, 87, 76, 0.25); }
    </style>
</head>
<body class="scroll-smooth selection:bg-[#E25E20] selection:text-white">

    {{-- Notifikasi Toast Global --}}
    @include('partials.toast')

    <!-- NAVIGATION BAR (Brutalism Match Sync) -->
    <header style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(238, 241, 234, 0.08); position: sticky; top: 0; z-index: 50;">
        <div class="nav wrap" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; height: 80px;">
            <!-- Brand Identity -->
            <a href="{{ route('landingPage') }}" style="display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 24px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE
            </a>

            <!-- User Account Actions & Quick Logout -->
            <div style="display: flex; align-items: center; gap: 24px;">
                <div class="hidden sm:flex flex-col text-right" style="border-right: 1px solid rgba(238, 241, 234, 0.1); padding-right: 16px;">
                    <span style="font-family: var(--mono); font-size: 10px; color: var(--turf); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Sesi Member Aktif</span>
                    <span style="font-size: 13px; color: white; font-weight: 700; margin-top: 2px;">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm">
                        Keluar Sesi
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- MAIN DASHBOARD CONSOLE CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 animate-fade-in">
        
        <!-- 1. USER PROFILE & LOYALTY TIER OVERVIEW HERO -->
        <div style="background: linear-gradient(120deg, var(--surface), #0B131F); border-radius: var(--radius); border: 1px solid rgba(238, 241, 234, 0.08); padding: 32px; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start; gap: 24px; position: relative; overflow: hidden;" class="flex-col lg:flex-row lg:items-center">
            <div class="absolute -right-16 -top-16 w-32 h-32 bg-[var(--turf)] rounded-full filter blur-[80px] opacity-5"></div>
            
            <div style="position: relative; z-index: 10;" class="space-y-4">
                <div>
                    <h1 style="font-size: 32px; color: white; line-height: 1;">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                    <p style="color: var(--muted); font-size: 14px; font-weight: 500; margin-top: 6px;">Pantau jadwal tanding tim Anda dan dapatkan keuntungan prioritas booking arena.</p>
                </div>

                @php
                    $tierColors = [
                        'Gold' => 'from-amber-500 to-yellow-400 text-slate-950 border-amber-600',
                        'Silver' => 'from-slate-500 to-slate-400 text-white border-slate-600',
                        'Bronze' => 'from-amber-800 to-amber-700 text-white border-amber-900'
                    ];
                @endphp
                <div style="display: inline-flex; align-items: center; gap: 12px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 12px; padding: 10px 16px;">
                    <span class="bg-gradient-to-r {{ $tierColors[$membership->membership_type] ?? $tierColors['Bronze'] }}" style="font-family: var(--mono); font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 4px 10px; border-radius: 6px; letter-spacing: 0.05em; border: 1px solid transparent;">
                        🏆 Tier {{ $membership->membership_type }}
                    </span>
                    <span style="font-family: var(--mono); font-size: 12px; color: var(--line); font-weight: 700;">
                        ⭐ {{ $membership->points }} Loyalty Points
                    </span>
                    @if($membership->membership_type === 'Gold')
                        <span style="font-family: var(--mono); font-size: 10px; color: var(--floodlight); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; background: rgba(245, 197, 24, 0.1); border: 1px solid rgba(245, 197, 24, 0.2); padding: 2px 6px; border-radius: 4px;" class="animate-pulse">• Prioritas Aktif</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('landingPage') }}" class="btn-ui btn-ui-primary" style="position: relative; z-index: 10; width: 100%; text-align: center;">
                + Sewa Lapangan Lagi
            </a>
        </div>

        <!-- 2. CONTEXTUAL MEMBERSHIP AWARENESS WIDGET FOR NEW USERS -->
        @if($membership->points == 0 && $membership->membership_type === 'Bronze')
            <div style="background: linear-gradient(90deg, rgba(33, 45, 60, 0.6), var(--surface)); border-radius: var(--radius); border: 1px dashed rgba(238, 241, 234, 0.15); padding: 24px; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start; gap: 16px; position: relative; overflow: hidden;" class="group sm:flex-row sm:items-center">
                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-gradient-to-tr from-amber-600 to-yellow-500 rounded-full filter blur-[40px] opacity-5"></div>
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="padding: 12px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 8px; font-size: 18px; user-select: none;">🎁</div>
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <h4 style="font-family: var(--body); font-size: 14px; font-weight: 700; color: white; display: flex; align-items: center; gap: 6px;">
                            Mulai Petualangan Tim Anda! <span style="width: 6px; height: 6px; background: var(--turf); border-radius: 50%;" class="animate-ping"></span>
                        </h4>
                        <p style="color: var(--muted); font-size: 13px; font-weight: 500; line-height: 1.6; max-width: 860px;">
                            Status Anda saat ini adalah <span style="color: var(--turf); font-weight: 700;">Bronze Member</span>. Kumpulkan poin dengan melakukan booking lapangan! Setiap transaksi sukses bernilai <span style="color: white; font-weight: 700; font-family: var(--mono);">+10 Poin</span>, mendekatkan Anda ke tingkatan <span style="color: var(--line); font-weight: 600;">Silver (100 Poin)</span> atau <span style="color: var(--floodlight); font-weight: 700;">Gold (300 Poin)</span> untuk menikmati diskon sewa otomatis hingga 10%.
                        </p>
                    </div>
                </div>
                <button type="button" onclick="alert('💡 INFO TIER MEMBERSHIP FUTSAL MARE:\n\n🥉 Bronze (Awal): Akumulasi poin aktif.\n🥈 Silver (100 Poin): Diskon otomatis 5% setiap sewa.\n🏆 Gold (300 Poin): Diskon otomatis 10% + Akses sistem prioritas booking 24/7!')" class="btn-ui btn-ui-ghost btn-ui-sm" style="flex-shrink: 0; width: 100%;">
                    Pelajari Benefit Tier &rarr;
                </button>
            </div>
        @endif

        <!-- 3. REAL-TIME CORE METRICS INDEX -->
        @php
            $totalBooking = $reservasis->count();
            $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
            $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div style="background: var(--surface); padding: 24px; border-radius: 12px; border: 1px solid rgba(238, 241, 234, 0.08); display: flex; align-items: center; justify-content: space-between;" class="group hover:border-slate-700 transition duration-300">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <p style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .06em;">Total Akumulasi Reservasi</p>
                    <p style="font-family: var(--mono); font-size: 28px; color: white; font-weight: 700; line-height: 1;">{{ $totalBooking }} <span style="font-size: 12px; font-family: 'Work Sans'; color: var(--muted); font-weight: 600; text-transform: uppercase;">Slot</span></p>
                </div>
                <div style="padding: 14px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 8px; font-size: 20px;" class="shadow-inner group-hover:scale-110 transition duration-300">📅</div>
            </div>
            <div style="background: var(--surface); padding: 24px; border-radius: 12px; border: 1px solid rgba(238, 241, 234, 0.08); display: flex; align-items: center; justify-content: space-between;" class="group hover:border-emerald-500/30 transition duration-300">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <p style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .06em;">Match Terkonfirmasi</p>
                    <p style="font-family: var(--mono); font-size: 28px; color: #2f9e58; font-weight: 700; line-height: 1;">{{ $lunasBooking }} <span style="font-size: 12px; font-family: 'Work Sans'; color: var(--muted); font-weight: 600; text-transform: uppercase;">Match</span></p>
                </div>
                <div style="padding: 14px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 8px; font-size: 20px;" class="shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-emerald-950/20">✅</div>
            </div>
            <div style="background: var(--surface); padding: 24px; border-radius: 12px; border: 1px solid rgba(238, 241, 234, 0.08); display: flex; align-items: center; justify-content: space-between;" class="group hover:border-blue-500/30 transition duration-300">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <p style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .06em;">Kontribusi Finansial</p>
                    <p style="font-family: var(--mono); font-size: 24px; color: var(--floodlight); font-weight: 700; line-height: 1;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div style="padding: 14px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 8px; font-size: 20px;" class="shadow-inner group-hover:scale-110 transition duration-300 group-hover:bg-blue-950/20">🪙</div>
            </div>
        </div>

        <!-- 4. DATA TABLES CONSOLE BOARD -->
        <div id="riwayat-tabel" style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: var(--radius); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid rgba(238, 241, 234, 0.08); background: rgba(15, 23, 42, 0.2); display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start; gap: 16px;" class="sm:flex-row sm:items-center">
                <h3 style="font-family: var(--body); font-weight: 700; text-transform: none; font-size: 16px; color: white; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 8px; height: 8px; background: var(--turf); border-radius: 50%;" class="animate-pulse"></span> Aliran Histori Transaksi Anda
                </h3>
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div id="bulk_action_panel" class="hidden items-center gap-2 bg-[#0a0f14] border border-slate-800 px-3 py-1.5 rounded-lg animate-fade-in shadow-inner">
                        <span id="selected_count" style="font-family: var(--mono); font-size: 11px; font-weight: 700; background: var(--surface-3); color: var(--line); padding: 2px 6px; border-radius: 4px;">0</span>
                        <span style="font-size: 11px; font-weight: 700; color: var(--muted-2); text-transform: uppercase; letter-spacing: 0.05em;">Terpilih</span>
                        <button type="submit" form="bulk_delete_form" class="btn-ui btn-ui-primary btn-ui-sm" style="padding: 6px 12px; font-size: 11px; border-radius: 4px; margin-left: 8px;">
                            🗑️ Hapus Sekaligus
                        </button>
                    </div>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted); background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.1); padding: 4px 10px; border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">Live Sync Ready</span>
                </div>
            </div>

            <!-- ACTION ACTION DATA FORM GATEWAYS -->
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
                <div style="padding: 64px 24px; text-align: center; color: var(--muted-2); font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em;" class="space-y-2">
                    <div style="font-size: 32px;" class="animate-bounce">🏃</div>
                    <p>Anda belum memiliki riwayat data reservasi aktif dalam database.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="brutal-data">
                        <thead>
                            <tr>
                                <th style="width: 48px; text-align: center;"><input type="checkbox" id="check_all_master" style="width: 15px; height: 14px; cursor: pointer;"></th>
                                <th>Detail Arena</th>
                                <th>Nomor Order</th>
                                <th>Tanggal Main</th>
                                <th>Waktu Slot</th>
                                <th>Metode Bayar</th>
                                <th>Total Tagihan</th>
                                <th>Status Gerbang</th>
                                <th style="text-align: center;">Konsol Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservasis as $reservasi)
                                <tr class="data-row" style="transition: background 0.15s ease;">
                                    <!-- Bulk Selection Row Node -->
                                    <td style="text-align: center;">
                                        @if(strtolower($reservasi->status) != 'waiting payment')
                                            <input type="checkbox" name="ids[]" value="{{ $reservasi->id }}" form="bulk_delete_form" class="row-checkbox" style="width: 15px; height: 14px; cursor: pointer;">
                                        @else
                                            <input type="checkbox" disabled style="opacity: 0.25; cursor: not-allowed;" title="Selesaikan tagihan Midtrans Anda terlebih dahulu">
                                        @endif
                                    </td>
                                    
                                    <!-- Field Arena Detail Info -->
                                    <td>
                                        <div style="font-size: 15px; font-weight: 700; color: white;">{{ $reservasi->lapangan->nama_lapangan }}</div>
                                        <div style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); text-transform: uppercase; font-weight: 700; margin-top: 2px;">Rumput {{ $reservasi->lapangan->jenis_rumput ?? 'Sintetis' }}</div>
                                    </td>
                                    
                                    <!-- Field Order Invoice Token -->
                                    <td style="font-family: var(--mono); color: var(--muted); text-transform: uppercase; letter-spacing: 0.02em;">
                                        {{ $reservasi->nomor_reservasi }}
                                    </td>
                                    
                                    <!-- Field Tanggal Pertandingan -->
                                    <td style="color: var(--line); font-weight: 600;">
                                        {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d M Y') }}
                                    </td>
                                    
                                    <!-- Field Alokasi Slot Jam -->
                                    <td style="font-family: var(--mono); color: var(--turf); font-weight: 700;">
                                        ⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA
                                    </td>
                                    
                                    <!-- Field Metode Pembayaran Node -->
                                    <td>
                                        <span style="font-family: var(--mono); font-size: 10px; font-weight: 700; text-transform: uppercase; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); padding: 4px 8px; border-radius: 4px; color: var(--muted);">
                                            {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'Midtrans') }}
                                        </span>
                                    </td>
                                    
                                    <!-- Field Finansial Cost -->
                                    <td style="font-family: var(--mono); font-size: 14px; font-weight: 700; color: white;">
                                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                                    </td>
                                    
                                    <!-- Field Transaction Status Node -->
                                    <td>
                                        @if($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                            <span class="badge-brutal badge-brutal-confirmed">
                                                <span style="width: 6px; height: 6px; background: #2f9e58; border-radius: 50%;"></span> Confirmed
                                            </span>
                                        @elseif($reservasi->status == 'Cancelled')
                                            <span class="badge-brutal badge-brutal-cancelled">
                                                <span style="width: 6px; height: 6px; background: #e2574c; border-radius: 50%;"></span> Cancelled
                                            </span>
                                        @else
                                            <span class="badge-brutal badge-brutal-pending">
                                                <span style="width: 6px; height: 6px; background: var(--floodlight); border-radius: 50%;" class="animate-pulse"></span> Pending
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Field Action Interaction Nodes -->
                                    <td style="text-align: center;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                            @if(strtolower($reservasi->status) == 'waiting payment')
                                                <button type="submit" form="form_batal_{{ $reservasi->id }}" class="btn-ui btn-ui-danger btn-ui-sm" style="padding: 6px 12px; font-size: 11px; border-radius: 6px;">
                                                    ❌ Batalkan Match
                                                </button>
                                            @elseif($reservasi->status == 'Confirmed' || $reservasi->status == 'Completed')
                                                <a href="{{ route('reservasi.tiket', $reservasi->id) }}" target="_blank" class="btn-ui btn-ui-ghost btn-ui-sm" style="padding: 6px 12px; font-size: 11px; border-radius: 6px; background: var(--surface-3);">
                                                    🎟️ Unduh Tiket
                                                </a>
                                            @else
                                                <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase;">No Action</span>
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

    <!-- CLIENT OPERATIONAL CONTAINER JAVASCRIPT JS -->
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
                    row.style.background = "rgba(238, 241, 234, 0.03)";
                } else {
                    row.style.background = "transparent";
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