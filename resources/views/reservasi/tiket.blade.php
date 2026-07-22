<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Reservasi #{{ $reservasi->nomor_reservasi ?? 'E-TICKET' }} - Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- JavaScript Library to convert HTML elements to pure Images -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-2: #1a2431;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --turf-dark: #cb5119;
            --floodlight: #f5c518;
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
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4 {
            font-family: var(--display);
            letter-spacing: .01em;
            text-transform: uppercase;
        }

        .label-brutal {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--muted-2);
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }

        .value-brutal {
            font-size: 14px;
            font-weight: 600;
            color: var(--line);
        }

        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; color: #0a0f14 !important; }
            .print-card { background: #ffffff !important; border: 2px solid #0a0f14 !important; box-shadow: none !important; border-radius: 0 !important; color: #0a0f14 !important; }
            .print-header { background: #f1f5f9 !important; border-bottom: 2px solid #0a0f14 !important; color: #0a0f14 !important; }
            .print-badge { background: #0a0f14 !important; color: #ffffff !important; border-radius: 0 !important; }
            .label-brutal { color: #475569 !important; }
            .value-brutal { color: #0a0f14 !important; font-weight: 700 !important; }
            .print-box { background: #f8fafc !important; border: 1px solid #cbd5e1 !important; }
            .print-total { color: #0f172a !important; font-weight: 900 !important; }
        }
    </style>
</head>
<body class="antialiased scroll-smooth selection:bg-[#E25E20] selection:text-white">

    <!-- NAVIGATION HEADER -->
    <header class="no-print" style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(238, 241, 234, 0.08); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 80px;">
            <a href="{{ route('landingPage') }}" style="display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 22px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE
            </a>
            <a href="{{ route('dashboard') }}" style="font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700; border: 1px solid rgba(238,241,234,0.15); padding: 8px 14px; border-radius: 6px;">
                &larr; Dashboard Member
            </a>
        </div>
    </header>

    <!-- TICKET LAYOUT CENTER DECK -->
    <main class="max-w-md mx-auto px-4 py-12 print:py-0 print:px-0">

        <!-- TARGET CAPTURE BOX CONTAINER (ID: element-to-capture) -->
        <div id="element-to-capture" class="print-card" style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.1); border-radius: var(--radius); overflow: hidden; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6); padding-bottom: 1px;">

            <!-- Ticket Header -->
            <div class="print-header" style="background: rgba(15, 23, 42, 0.3); padding: 24px; text-align: center; border-bottom: 1px solid rgba(238, 241, 234, 0.08);">
                <span class="print-badge" style="display: inline-block; padding: 4px 12px; background: var(--turf); color: white; font-family: var(--mono); font-size: 10px; font-weight: 700; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.05em;">
                    E-Tiket Resmi Match
                </span>

                <h3 style="font-size: 13px; color: var(--muted); letter-spacing: 0.05em; margin-top: 16px;">NOMOR INVOICE RESERVASI</h3>
                <p style="font-family: var(--mono); font-size: 20px; font-weight: 700; color: var(--floodlight); letter-spacing: 0.02em; margin-top: 2px;">
                    {{ $reservasi->nomor_reservasi }}
                </p>
            </div>

            <!-- Ticket Body -->
            <div style="padding: 24px;" class="space-y-4">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px dashed rgba(238, 241, 234, 0.1);" class="print:border-slate-300">
                    <div>
                        <span class="label-brutal">Nama Pemesan</span>
                        <p class="value-brutal" style="color: white;">{{ auth()->user()->name ?? 'Pelanggan' }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="label-brutal">Status Gerbang</span>
                        <span style="font-family: var(--mono); font-size: 11px; font-weight: 700; background: rgba(47,158,88,0.15); color: #2f9e58; border: 1px solid rgba(47,158,88,0.25); padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                            Lunas
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="label-brutal">Arena Lapangan</span>
                        <h2 class="value-brutal" style="font-family: var(--body); font-weight: 700; font-size: 18px; color: white;">
                            {{ $reservasi->lapangan->nama_lapangan ?? 'Lapangan Utama' }}
                        </h2>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <span class="label-brutal">Tanggal Main</span>
                            <p class="value-brutal">
                                {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <span class="label-brutal">Waktu Slot WITA</span>
                            <p class="value-brutal" style="font-family: var(--mono); color: var(--turf);">
                                {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Content Center Display -->
                <div class="print-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.06); border-radius: 12px; margin: 20px 0;">
                    @if(!empty($qrUrl))
                        <!-- Tambahkan atribut crossorigin="anonymous" untuk mencegah masalah CORS pada html2canvas -->
                        <img src="{{ $qrUrl }}" crossorigin="anonymous" alt="QR Code E-Tiket" style="width: 180px; height: 180px; background: white; padding: 12px; border-radius: 8px;" class="print:p-0">
                    @else
                        <div style="width: 180px; height: 180px; display: flex; align-items: center; justify-content: center; background: rgba(226,87,76,0.08); border: 1px dashed rgba(226,87,76,0.3); border-radius: 8px; text-align: center; padding: 12px;">
                            <span style="font-family: var(--mono); font-size: 10px; color: #e2574c; font-weight: 700; text-transform: uppercase;">QR tidak tersedia, hubungi admin</span>
                        </div>
                    @endif
                    <span style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 12px; font-weight: 700;">Pindai Masuk Pengawas Arena</span>
                </div>

                <!-- Financial Checkout Summary Sheet -->
                <div class="print-box" style="background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.06); border-radius: 8px; padding: 16px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span class="label-brutal">Total Biaya Lunas</span>
                        <span style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); font-weight: 700; text-transform: uppercase;">
                            Gtw: {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'QRIS') }}
                        </span>
                    </div>
                    <span class="print-total" style="font-family: var(--mono); font-size: 22px; color: var(--floodlight); font-weight: 700;">
                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Ground Rules Policy Note -->
                <div style="font-size: 12px; color: var(--muted); line-height: 1.6; font-weight: 500; padding-top: 8px; padding-bottom: 12px;" class="space-y-1">
                    <span class="label-brutal" style="color: var(--line);">📌 Ketentuan Lapangan:</span>
                    <p>• Hadir di lokasi stadium 15 menit sebelum kick-off untuk sinkronisasi administrasi.</p>
                    <p>• Wajib memakai sepatu olahraga / futsal standar (non-cleat / tanpa pul besi).</p>
                    <p>• Tunjukkan kode QR di atas ke kamera pengawas untuk proses autentikasi pintu gerbang.</p>
                </div>
            </div>

            <!-- Form Action Triggers (Integrated Image Download Button) -->
            <div class="no-print" style="padding: 0 24px 24px 24px; display: flex; flex-direction: column; gap: 8px;">
                <button id="downloadImageBtn" type="button" class="w-full py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-bold text-xs tracking-widest uppercase shadow-md transition duration-150">
                    💾 Unduh Gambar Tiket (PNG)
                </button>
                <button onclick="window.print()" class="w-full py-4 bg-[#212d3c] border border-slate-700/60 hover:bg-slate-700 text-white rounded-xl font-bold text-xs tracking-widest uppercase transition duration-150">
                    🖨️ Cetak / Simpan PDF
                </button>
            </div>
        </div>
    </main>

    <!-- INTERACTIVE CAPTURE DOWNLOAD SCRIPT ENGINE -->
    <script type="text/javascript">
        document.getElementById('downloadImageBtn').addEventListener('click', function () {
            const targetElement = document.getElementById('element-to-capture');
            const actionButtons = targetElement.querySelector('.no-print');

            // Sembunyikan tombol aksi agar tidak ikut ter-render di dalam gambar PNG
            if (actionButtons) actionButtons.style.display = 'none';

            html2canvas(targetElement, {
                backgroundColor: '#121a23',
                scale: 2,
                logging: false,
                useCORS: true,       // Mengizinkan pengambilan gambar lintas domain (misal dari storage/URL eksternal)
                allowTaint: false    // Menjaga keamanan canvas agar data URL tetap dapat diekstrak
            }).then(function (canvas) {
                actionButtons.style.display = 'flex';

                const imageURI = canvas.toDataURL("image/png");
                const temporaryLink = document.createElement('a');
                temporaryLink.download = 'Tiket_FutsalMare_{{ $reservasi->nomor_reservasi }}.png';
                temporaryLink.href = imageURI;
                document.body.appendChild(temporaryLink);
                temporaryLink.click();
                document.body.removeChild(temporaryLink);
            }).then(() => {
                // Berikan notifikasi sukses kecil yang elegan (opsional)
                console.log("E-Tiket berhasil diunduh.");
            }).catch(function (error) {
                actionButtons.style.display = 'flex';
                alert("Gagal merender file gambar. Kalau QR code sudah tampil normal di halaman ini tapi hasil download tetap kosong/tidak terbaca scanner, kemungkinan penyebabnya format SVG QR tidak selalu bisa di-screenshot html2canvas — hubungi admin untuk beralih ke format PNG.");
            });
        });
    </script>
</body>
</html>