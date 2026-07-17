<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $lapangan->nama_lapangan }} - Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <style>
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --turf-dark: #cb5119;
            --floodlight: #f5c518;
            --floodlight-dim: rgba(245, 197, 24, 0.15);
            --line: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        body { background: var(--ink); color: var(--line); font-family: 'Work Sans', sans-serif; }
        h1, h2, h3 { font-family: var(--display); text-transform: uppercase; }
        input[type="date"], select {
            width: 100%; background: var(--surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--line);
            padding: 14px; border-radius: 8px; font-size: 14px; font-weight: 600;
        }
        input[type="date"]:focus, select:focus { border-color: var(--turf); outline: none; }
        .label-title { font-size: 11px; color: var(--muted); display: block; margin-bottom: 8px; font-family: var(--mono); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; }
        
        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; letter-spacing: .05em; width: 100%; transition: all 0.15s ease;
        }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-primary:disabled { background: var(--surface-3); color: var(--muted-2); cursor: not-allowed; }

        .hero-brutal-media {
            height: 200px; border-radius: 8px; position: relative; overflow: hidden;
            background: repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(238,241,234,.05) 40px, rgba(238,241,234,.05) 42px), linear-gradient(160deg, var(--turf-dark), #0f3320);
            border: 1px solid rgba(238,241,234,0.08); margin: 16px 0;
        }
        .hero-brutal-media img { width: 100%; height: 100%; object-fit: cover; }
        .hero-brutal-media::after { content: ""; position: absolute; inset: 10px; border: 2px solid rgba(238,241,234,.25); border-radius: 4px; pointer-events: none; }
    </style>
</head>
<body class="antialiased min-h-screen">

    <!-- HEADER NAVIGATION -->
    <header style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(238,241,234,0.08); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('landingPage') }}" style="display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 22px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE
            </a>
            <a href="{{ route('landingPage') }}" style="font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700;">
                &larr; Kembali
            </a>
        </div>
    </header>

    <!-- CORE PANEL CONTAINER -->
    <main style="max-width: 1180px; margin: 0 auto; padding: 40px 24px;">
        <div style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: var(--radius); overflow: hidden; display: grid; grid-template-columns: 1fr 1.3fr;" class="grid-cols-1 md:grid-cols-2">
            
            <!-- LEFT PANEL: INFORMATION & BRAND SUMMARY -->
            <div style="padding: 32px; border-right: 1px solid rgba(238, 241, 234, 0.08); display: flex; flex-direction: column; justify-content: space-between;" class="border-b md:border-b-0">
                <div style="margin-bottom: 24px;">
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--turf); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 8px;">
                        Permukaan: {{ $lapangan->jenis_rumput ?? 'Sintetis' }}
                    </span>
                    <h2 style="font-size: 32px; color: white; line-height: 1;">{{ $lapangan->nama_lapangan }}</h2>
                    <p style="color: var(--muted); font-size: 13px; font-weight: 500; margin-top: 12px; line-height: 1.6;">
                        Sistem manajemen jadwal murni fiksasi. Dilengkapi dengan papan skor digital premium serta fiksasi pencahayaan lampu sorot LED terarah bebas silau malam hari.
                    </p>

                    <div class="hero-brutal-media">
                        @if($lapangan->foto_lapangan)
                            @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                            @else
                                <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                            @endif
                        @endif
                    </div>

                    <div style="background: var(--ink); border: 1px solid rgba(238,241,234,0.06); border-radius: 8px; padding: 16px; margin-top: 16px; font-size: 12px; color: var(--muted); font-weight: 500;">
                        <span style="color: var(--turf); font-family: var(--mono); font-weight: 700; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em;">💡 INFO TARIF FLEKSIBEL:</span>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 4px; margin-bottom: 4px; border-bottom: 1px dashed rgba(238,241,234,0.08);">
                            <span>Peak Hour (16:00 - 22:00)</span><b>+Rp 50.000 / Jam</b>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Akhir Pekan (Sabtu & Minggu)</span><b>+Rp 20.000 / Jam</b>
                        </div>
                    </div>
                </div>

                <div style="padding-top: 20px; border-top: 1px dashed rgba(238,241,234,0.1); display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase;">Tarif Base</span>
                        <div style="font-family: var(--mono); font-size: 24px; color: var(--floodlight); font-weight: 700; line-height: 1; margin-top: 4px;">
                            Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span style="font-size: 12px; color: var(--muted); font-family: 'Work Sans'; font-weight: 500;">/jam</span>
                        </div>
                    </div>
                    <span style="font-family: var(--mono); font-size: 11px; background: rgba(47,158,88,.15); color: #2f9e58; padding: 4px 10px; border-radius: 20px; font-weight: 700;">● STADIUM ACTIVE</span>
                </div>
            </div>

            <!-- RIGHT PANEL: FORM RESERVASI -->
            <form id="form_reservasi" action="{{ route('reservasi.store') }}" method="POST" style="padding: 32px; display: flex; flex-direction: column; gap: 24px;">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <!-- step 1: tanggal main -->
                <div>
                    <label class="label-title">1. Tentukan Tanggal Pertandingan</label>
                    <input type="date" id="input_tanggal" name="tanggal_main" value="{{ $tanggal_pilihan }}" min="{{ date('Y-m-d') }}" onchange="gantiTanggal(this.value)">
                </div>

                <!-- step 2: slot jam tanding (Sempurna dengan Peer-Checked Oranye) -->
                <div>
                    <label class="label-title">2. Pilih Jam Mulai Tanding (Slot Waktu WITA)</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 6px;">
                        @php $hasChecked = false; @endphp
                        @for ($jam = 8; $jam <= 21; $jam++)
                            @php
                                $isBooked = in_array($jam, $jam_terpesan);
                                $jam_format = sprintf('%02d:00', $jam);
                                $shouldCheck = (!$isBooked && !$hasChecked);
                                if ($shouldCheck) { $hasChecked = true; }
                            @endphp
                            <label style="position: relative; cursor: pointer;">
                                <!-- Menggunakan class sr-only Tailwind agar input bawaan hilang dan peer-checked berfungsi presisi -->
                                <input type="radio" name="jam_mulai" value="{{ $jam }}" class="peer sr-only"
                                    {{ $isBooked ? 'disabled' : '' }} onchange="hitungTotal()" {{ $shouldCheck ? 'checked' : '' }}>
                                
                                <!-- Div komponen visual utama, akan otomatis berubah oranye saat radio di-check oleh user -->
                                <div class="w-full text-center py-3 rounded-md font-mono text-xs font-bold border transition-all duration-150 select-none
                                    peer-disabled:bg-[#0B131F]/30 peer-disabled:border-slate-800 peer-disabled:text-slate-600 peer-disabled:cursor-not-allowed peer-disabled:line-through
                                    peer-checked:bg-[#e25e20] peer-checked:text-white peer-checked:border-transparent peer-checked:scale-105 peer-checked:shadow-lg
                                    bg-[#212d3c] border-transparent text-slate-300 hover:border-slate-600">
                                    {{ $jam_format }}
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- step 3: durasi -->
                <div>
                    <label class="label-title">3. Durasi Pemakaian Lapangan</label>
                    <select name="durasi" id="input_durasi" onchange="hitungTotal()">
                        <option value="1">1 Jam Sewa Match</option>
                        <option value="2" selected>2 Jam Sewa Match (Sangat Direkomendasikan)</option>
                        <option value="3">3 Jam Sewa Match</option>
                    </select>
                </div>

                <!-- midtrans gateway banner -->
                <div style="background: rgba(245, 197, 24, 0.05); border: 1px solid rgba(245, 197, 24, 0.15); border-radius: 8px; padding: 14px; display: flex; gap: 12px; align-items: flex-start; font-size: 12px; color: var(--floodlight); font-weight: 500;">
                    <span style="font-size: 14px; line-height: 1;">🔒</span>
                    <div>
                        <b style="text-transform: uppercase; font-family: var(--mono); letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Automated Gateway Active</b>
                        Penyelesaian transaksi fiksasi aman terenkripsi via Midtrans. Mendukung QRIS instan, Virtual Account Bank otomatis, tanpa verifikasi slip manual.
                    </div>
                </div>

                <!-- total price checkout widget -->
                <div style="background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.06); border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                    <div>
                        <span style="font-size: 12px; color: var(--muted); font-weight: 600; display: block;">Estimasi Total Tagihan</span>
                        <span id="rincian_surcharge" style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); display: block; margin-top: 2px; font-weight: 700; text-transform: uppercase;"></span>
                    </div>
                    <span id="live_total_harga" style="font-family: var(--mono); font-size: 26px; color: var(--floodlight); font-weight: 700;">Rp 0</span>
                </div>

                <button type="submit" id="btn_submit" class="btn-ui btn-ui-primary">
                    Kunci Jadwal Arena &rarr;
                </button>
            </form>
        </div>
    </main>

    <!-- CALCULATION & INTERACTIVE INTEGRATION ENGINE SCRIPT -->
    <script>
        const hargaPerJam = {{ $lapangan->harga_per_jam }};

        function gantiTanggal(tanggal) {
            window.location.href = "?tanggal_main=" + tanggal;
        }

        function hitungTotal() {
            const inputTanggal = document.getElementById('input_tanggal').value;
            const selectDurasi = document.getElementById('input_durasi').value;
            const radioJam = document.querySelector('input[name="jam_mulai"]:checked');
            
            let startHour = radioJam ? parseInt(radioJam.value) : null;
            let durasi = parseInt(selectDurasi);
            let total = 0;
            let infoSurcharge = [];

            if (!startHour) {
                document.getElementById('live_total_harga').innerText = "Slot Kosong";
                document.getElementById('rincian_surcharge').innerText = "";
                return;
            }

            const parts = inputTanggal.split('-');
            const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
            const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);

            for (let i = 0; i < durasi; i++) {
                let currentHour = startHour + i;
                let hargaSlot = hargaPerJam;

                if (currentHour >= 16 && currentHour < 22) {
                    hargaSlot += 50000;
                }
                if (isWeekend) {
                    hargaSlot += 20000;
                }
                total += hargaSlot;
            }

            if (isWeekend) infoSurcharge.push("Weekend Rate");
            if (startHour >= 16 || (startHour + durasi) > 16) infoSurcharge.push("Peak Rate");

            document.getElementById('live_total_harga').innerText = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('rincian_surcharge').innerText = infoSurcharge.join(' | ');
        }

        window.addEventListener('DOMContentLoaded', () => {
            hitungTotal();
        });

        // ASYNC FORM SUBMISSION CONTROL INTERPOLATION
        document.getElementById('form_reservasi').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const radioJam = document.querySelector('input[name="jam_mulai"]:checked');
            if (!radioJam) {
                alert("Silakan tentukan pilihan slot jam main Anda terlebih dahulu!");
                return;
            }
            
            const btnSubmit = document.getElementById('btn_submit');
            btnSubmit.disabled = true;
            btnSubmit.innerText = "MEMPROSES KONTRAK SLOT...";

            const formData = new FormData(this);

            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    throw new Error(data?.message || `Kendala Koneksi Server (Status: ${response.status})`);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) { window.location.href = data.redirect; },
                        onPending: function(result) { window.location.href = data.redirect; },
                        onError: function(result) {
                            alert("Proses transaksi pembayaran dihentikan sistem.");
                            btnSubmit.disabled = false;
                            btnSubmit.innerText = "Kunci Jadwal Arena &rarr;";
                        },
                        onClose: function() { window.location.href = data.redirect; }
                    });
                } else {
                    alert("Gagal mengamankan alokasi slot: " + data.message);
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = "Kunci Jadwal Arena &rarr;";
                }
            })
            .catch(error => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = "Kunci Jadwal Arena &rarr;";
                alert(error.message);
            });
        });
    </script>
</body>
</html>