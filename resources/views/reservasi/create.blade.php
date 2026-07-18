<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $lapangan->nama_lapangan }} - Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- NOTE: pastikan sudah ada di config/services.php:
         'midtrans' => ['client_key' => env('MIDTRANS_CLIENT_KEY'), 'server_key' => env('MIDTRANS_SERVER_KEY')], --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
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
            --danger: #e2574c;
            --success: #2f9e58;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        *{box-sizing:border-box;}
        body { background: var(--ink); color: var(--line); font-family: 'Work Sans', sans-serif; margin:0; }
        h1, h2, h3 { font-family: var(--display); text-transform: uppercase; }
        :focus-visible{ outline:2px solid var(--floodlight); outline-offset:2px; }
        input[type="date"], select {
            width: 100%; background: var(--surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--line);
            padding: 14px; border-radius: 8px; font-size: 14px; font-weight: 600;
        }
        input[type="date"]:focus, select:focus { border-color: var(--turf); outline: none; box-shadow:0 0 0 3px rgba(226,94,32,.25); }
        .label-title { font-size: 11px; color: var(--muted); display: block; margin-bottom: 8px; font-family: var(--mono); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; }

        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; letter-spacing: .05em; width: 100%; transition: all 0.15s ease;
        }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover:not(:disabled) { background: var(--turf-dark); }
        .btn-ui-primary:disabled { background: var(--surface-3); color: var(--muted-2); cursor: not-allowed; }

        .spinner{ width:15px; height:15px; border:2px solid rgba(255,255,255,.35); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; flex-shrink:0; }
        .btn-ui-primary:disabled .spinner{ border-color: rgba(139,151,166,.35); border-top-color: var(--muted-2); }
        @keyframes spin{ to{ transform:rotate(360deg); } }

        .hero-brutal-media {
            height: 200px; border-radius: 8px; position: relative; overflow: hidden;
            background: repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(238,241,234,.05) 40px, rgba(238,241,234,.05) 42px), linear-gradient(160deg, var(--turf-dark), #0f3320);
            border: 1px solid rgba(238,241,234,0.08); margin: 16px 0;
        }
        .hero-brutal-media img { width: 100%; height: 100%; object-fit: cover; }
        .hero-brutal-media::after { content: ""; position: absolute; inset: 10px; border: 2px solid rgba(238,241,234,.25); border-radius: 4px; pointer-events: none; }

        .field-error{
            display:none; align-items:center; gap:6px; font-family:var(--mono); font-size:11px; color:var(--danger); margin-top:8px;
        }
        .field-error.show{ display:flex; }

        #toast-container{ position:fixed; bottom:24px; right:24px; display:flex; flex-direction:column; gap:10px; z-index:1100; }
        .toast{
            background: var(--surface); border:1px solid rgba(238,241,234,.1); border-left:3px solid var(--success);
            padding:14px 16px; border-radius:8px; font-size:13px; min-width:260px; max-width:320px;
            box-shadow:0 15px 40px -10px rgba(0,0,0,.6); display:flex; align-items:flex-start; gap:10px;
            animation:toastIn .2s ease;
        }
        .toast.err{ border-left-color: var(--danger); }
        .toast .t-ic{ font-weight:900; flex-shrink:0; }
        .toast.ok .t-ic{ color: var(--success); }
        .toast.err .t-ic{ color: var(--danger); }
        @keyframes toastIn{ from{ opacity:0; transform:translateX(20px);} to{ opacity:1; transform:translateX(0);} }

        @media (prefers-reduced-motion: reduce){ *{ animation:none !important; transition:none !important; } }
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
                        {{ $lapangan->deskripsi ?? 'Lapangan futsal dengan pencahayaan lampu sorot LED dan permukaan berkualitas untuk kenyamanan bermain malam hari.' }}
                    </p>

                    <div class="hero-brutal-media">
                        @if($lapangan->foto_lapangan)
                            <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="Foto {{ $lapangan->nama_lapangan }}" loading="lazy">
                        @endif
                    </div>

                    <div style="background: var(--ink); border: 1px solid rgba(238,241,234,0.06); border-radius: 8px; padding: 16px; margin-top: 16px; font-size: 12px; color: var(--muted); font-weight: 500;">
                        <span style="color: var(--turf); font-family: var(--mono); font-weight: 700; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.02em;">Info Tarif Fleksibel</span>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 4px; margin-bottom: 4px; border-bottom: 1px dashed rgba(238,241,234,0.08);">
                            <span>Peak Hour (16.00 – 22.00)</span><b>+Rp50.000/jam</b>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span>Akhir Pekan (Sabtu & Minggu)</span><b>+Rp20.000/jam</b>
                        </div>
                    </div>
                </div>

                <div style="padding-top: 20px; border-top: 1px dashed rgba(238,241,234,0.1); display: flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase;">Tarif Base</span>
                        <div style="font-family: var(--mono); font-size: 24px; color: var(--floodlight); font-weight: 700; line-height: 1; margin-top: 4px;">
                            Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span style="font-size: 12px; color: var(--muted); font-family: 'Work Sans'; font-weight: 500;">/jam</span>
                        </div>
                    </div>
                    <span style="font-family: var(--mono); font-size: 11px; background: rgba(47,158,88,.15); color: #2f9e58; padding: 4px 10px; border-radius: 20px; font-weight: 700;">● TERSEDIA</span>
                </div>
            </div>

            <!-- RIGHT PANEL: FORM RESERVASI -->
            <form id="form_reservasi" action="{{ route('reservasi.store') }}" method="POST" style="padding: 32px; display: flex; flex-direction: column; gap: 24px;">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <!-- step 1: tanggal main -->
                <div>
                    <label class="label-title" for="input_tanggal">1. Tentukan Tanggal Pertandingan</label>
                    <input type="date" id="input_tanggal" name="tanggal_main" value="{{ $tanggal_pilihan }}" min="{{ date('Y-m-d') }}" onchange="gantiTanggal(this.value)">
                </div>

                <!-- step 2: slot jam tanding -->
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
                                <input type="radio" name="jam_mulai" value="{{ $jam }}" class="peer sr-only"
                                    {{ $isBooked ? 'disabled' : '' }} onchange="hitungTotal()" {{ $shouldCheck ? 'checked' : '' }}
                                    aria-label="Jam {{ $jam_format }}{{ $isBooked ? ' (sudah dipesan)' : '' }}">

                                <div class="w-full text-center py-3 rounded-md font-mono text-xs font-bold border transition-all duration-150 select-none
                                    peer-disabled:bg-[#0B131F]/30 peer-disabled:border-slate-800 peer-disabled:text-slate-600 peer-disabled:cursor-not-allowed peer-disabled:line-through
                                    peer-checked:bg-[#e25e20] peer-checked:text-white peer-checked:border-transparent peer-checked:scale-105 peer-checked:shadow-lg
                                    peer-focus-visible:ring-2 peer-focus-visible:ring-[#f5c518] peer-focus-visible:ring-offset-2 peer-focus-visible:ring-offset-[#121a23]
                                    bg-[#212d3c] border-transparent text-slate-300 hover:border-slate-600">
                                    {{ $jam_format }}
                                </div>
                            </label>
                        @endfor
                    </div>
                    <div class="field-error" id="err_jam">⚠ Silakan pilih jam main terlebih dahulu.</div>
                </div>

                <!-- step 3: durasi -->
                <div>
                    <label class="label-title" for="input_durasi">3. Durasi Pemakaian Lapangan</label>
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
                        <b style="text-transform: uppercase; font-family: var(--mono); letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Pembayaran Aman via Midtrans</b>
                        Mendukung QRIS instan dan Virtual Account Bank otomatis, tanpa perlu verifikasi slip manual.
                    </div>
                </div>

                <!-- total price checkout widget -->
                <div style="background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.06); border-radius: 8px; padding: 20px; display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                    <div>
                        <span style="font-size: 12px; color: var(--muted); font-weight: 600; display: block;">Estimasi Total Tagihan</span>
                        <span id="rincian_surcharge" style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); display: block; margin-top: 2px; font-weight: 700; text-transform: uppercase;"></span>
                    </div>
                    <span id="live_total_harga" style="font-family: var(--mono); font-size: 26px; color: var(--floodlight); font-weight: 700;">Rp0</span>
                </div>

                <button type="submit" id="btn_submit" class="btn-ui btn-ui-primary">
                    <span id="btn_submit_label">Kunci Jadwal Arena →</span>
                </button>
            </form>
        </div>
    </main>

    <div id="toast-container"></div>

    <script>
        const hargaPerJam = {{ $lapangan->harga_per_jam }};
        const BTN_LABEL_DEFAULT = 'Kunci Jadwal Arena →';
        const BTN_LABEL_LOADING = 'Memproses...';

        // Template URL cancel-instant — placeholder diganti nomor reservasi asli saat dipakai.
        // Menghindari hardcode path manual supaya tetap ikut kalau prefix route berubah.
        const CANCEL_INSTANT_URL_TEMPLATE = "{{ route('reservasi.cancelInstant', ['nomor_reservasi' => 'GANTI_NOMOR']) }}";

        function gantiTanggal(tanggal) {
            const url = new URL(window.location.href);
            url.searchParams.set('tanggal_main', tanggal);
            window.location.href = url.toString();
        }

        function showToast(type, msg){
            const box = document.createElement('div');
            box.className = 'toast ' + type;
            box.innerHTML = `<span class="t-ic">${type === 'ok' ? '✓' : '✕'}</span><span>${msg}</span>`;
            document.getElementById('toast-container').appendChild(box);
            setTimeout(() => {
                box.style.opacity = '0';
                box.style.transition = 'opacity .3s';
                setTimeout(() => box.remove(), 300);
            }, 4000);
        }

        // Simpan pesan untuk ditampilkan SETELAH location.reload() — toast biasa akan
        // langsung hilang karena DOM dibuang begitu halaman dimuat ulang.
        function queueToastAfterReload(type, msg){
            sessionStorage.setItem('pending_toast', JSON.stringify({ type, msg }));
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
                document.getElementById('live_total_harga').innerText = 'Pilih jam main';
                document.getElementById('rincian_surcharge').innerText = '';
                return;
            }

            const parts = inputTanggal.split('-');
            const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
            const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);

            for (let i = 0; i < durasi; i++) {
                let currentHour = startHour + i;
                let hargaSlot = hargaPerJam;
                if (currentHour >= 16 && currentHour < 22) hargaSlot += 50000;
                if (isWeekend) hargaSlot += 20000;
                total += hargaSlot;
            }

            if (isWeekend) infoSurcharge.push('Weekend Rate');
            if (startHour >= 16 || (startHour + durasi) > 16) infoSurcharge.push('Peak Rate');

            document.getElementById('live_total_harga').innerText = 'Rp' + total.toLocaleString('id-ID');
            document.getElementById('rincian_surcharge').innerText = infoSurcharge.join(' | ');

            document.getElementById('err_jam').classList.remove('show');
        }

        window.addEventListener('DOMContentLoaded', () => {
            hitungTotal();

            // Tampilkan toast yang "dititipkan" sebelum reload (misal dari pembatalan instan)
            const pending = sessionStorage.getItem('pending_toast');
            if (pending) {
                sessionStorage.removeItem('pending_toast');
                try {
                    const { type, msg } = JSON.parse(pending);
                    showToast(type, msg);
                } catch (e) { /* abaikan kalau datanya rusak */ }
            }
        });

        function setButtonLoading(isLoading){
            const btn = document.getElementById('btn_submit');
            const label = document.getElementById('btn_submit_label');
            btn.disabled = isLoading;
            label.innerHTML = isLoading
                ? '<span class="spinner"></span> ' + BTN_LABEL_LOADING
                : BTN_LABEL_DEFAULT;
        }

        document.getElementById('form_reservasi').addEventListener('submit', function(e) {
            e.preventDefault();

            const radioJam = document.querySelector('input[name="jam_mulai"]:checked');
            if (!radioJam) {
                document.getElementById('err_jam').classList.add('show');
                document.getElementById('err_jam').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            setButtonLoading(true);
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
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
                    throw new Error(data?.message || `Kendala koneksi (status ${response.status})`);
                }
                return data;
            })
            .then(data => {
                if (!data.success) {
                    showToast('err', data.message || 'Gagal mengamankan slot, silakan coba lagi.');
                    setButtonLoading(false);
                    return;
                }

                const currentOrder = data.nomor_reservasi;

                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => { window.location.href = data.redirect; },
                    onPending: (result) => { window.location.href = data.redirect; },
                    onError: (result) => {
                        showToast('err', 'Pembayaran gagal diproses, silakan coba lagi.');
                        setButtonLoading(false);
                    },
                    onClose: () => {
                        const cancelUrl = CANCEL_INSTANT_URL_TEMPLATE.replace('GANTI_NOMOR', currentOrder);

                        fetch(cancelUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            }
                        })
                        .then(res => res.json())
                        .then(result => {
                            if (!result.success) {
                                // Gagal cancel (misal reservasi tidak ditemukan) — jangan klaim
                                // "slot dilepas" kalau sebenarnya kita tidak yakin statusnya.
                                setButtonLoading(false);
                                showToast('err', result.message || 'Gagal memproses pembatalan, periksa status booking di dashboard.');
                                return;
                            }

                            if (result.already_confirmed) {
                                // Race condition: webhook settlement sudah masuk duluan sebelum
                                // popup ditutup — bukan pembatalan, langsung arahkan ke redirect sukses.
                                window.location.href = data.redirect;
                                return;
                            }

                            // Pembatalan normal — titip toast supaya tetap muncul setelah reload,
                            // lalu reload untuk menyegarkan papan jadwal (slot ini kembali kosong).
                            queueToastAfterReload('err', 'Pembayaran dibatalkan, slot jam dilepas kembali.');
                            location.reload();
                        })
                        .catch(() => {
                            setButtonLoading(false);
                            showToast('err', 'Gagal menghubungi server. Periksa status booking di dashboard sebelum mencoba lagi.');
                        });
                    }
                });
            })
            .catch(error => {
                setButtonLoading(false);
                showToast('err', error.message);
            });
        });
    </script>
</body>
</html>