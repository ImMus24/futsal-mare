<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Gate Scanner — Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        .fm-scope {
            --color-primary:      #e25e20;
            --color-primary-dark: #cb5119;
            --color-secondary:    #f5c518;
            --color-bg-main:      #121a23;
            --color-bg-card:      #0a0f14;
            --color-text-main:    #ffffff;
            --color-text-muted:   #94a3b8;
            --color-text-meta:    #5c6979;
            --line: rgba(238, 241, 234, 0.08);

            --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
            --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);

            font-family: 'Work Sans', sans-serif;
            color: var(--color-text-main);
        }
        body { background: var(--color-bg-main); margin: 0; -webkit-font-smoothing: antialiased; }
        .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; text-transform: uppercase; }
        .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
        .fm-scope :focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }

        .scanner-frame { position: relative; }
        .scanner-frame::after {
            content: ""; position: absolute; inset: 16px; border: 2px solid rgba(238, 241, 234, 0.15);
            border-radius: 8px; pointer-events: none;
        }
        @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
        @media (prefers-reduced-motion: reduce) { .fm-scope .live-pip { animation: none !important; } }

        .fm-scope .btn-scan {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
            letter-spacing: .05em; transition: all 0.15s ease; font-family: 'Work Sans', sans-serif;
        }
        .fm-scope .btn-scan-primary { background: var(--color-primary); color: white; }
        .fm-scope .btn-scan-primary:hover { background: var(--color-primary-dark); }
    </style>
</head>
<body class="fm-scope antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP NAVIGATION -->
    <header style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 80px;">
            <div class="f-display" style="display: flex; align-items: center; gap: 10px; font-size: 22px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--color-primary); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE STAFF
            </div>
            <a href="{{ route('admin.dashboard') }}" class="f-mono"
               style="font-size: 11px; color: var(--color-text-muted); text-transform: uppercase; font-weight: 700; border: 1px solid var(--line); padding: 8px 14px; border-radius: 6px; transition: color .15s ease;"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                &larr; Dashboard Admin
            </a>
        </div>
    </header>

    <!-- MAIN SCANNER VIEWPORT -->
    <main style="max-width: 1180px; margin: 0 auto; padding: 40px 24px; width: 100%; flex: 1; display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start;" class="grid-cols-1 md:grid-cols-2">

        <!-- LEFT: KAMERA -->
        <div class="scanner-frame" style="background: var(--color-bg-card); border: 1px solid var(--line); border-radius: 14px; padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px;">
                <h3 class="f-display" style="font-size: 16px; color: white; letter-spacing: 0.05em;">Kamera Terminal Gate Scanner</h3>
                <div class="f-mono" style="font-size: 11px; color: var(--color-primary); font-weight: 700; display: flex; align-items: center; gap: 6px;">
                    <span id="stream_dot" class="live-pip" style="width: 7px; height: 7px; border-radius: 50%; background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                    <span id="stream_text">STREAMING</span>
                </div>
            </div>

            <div id="reader" style="width: 100%; background: var(--color-bg-main); border-radius: 8px; overflow: hidden; border: 1px solid var(--line);"></div>
        </div>

        <!-- RIGHT: KONSOL VERIFIKASI -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="background: var(--color-bg-card); border: 1px solid var(--line); border-radius: 14px; padding: 28px;">
                <h3 class="f-display" style="font-size: 16px; margin-bottom: 12px; color: white; letter-spacing: 0.05em;">Konsol Verifikasi Instan</h3>
                <p style="font-size: 13px; color: var(--color-text-muted); line-height: 1.6; font-weight: 500; margin-bottom: 20px;">
                    Arahkan Kode QR E-Tiket tim pelanggan tepat ke arah lensa kamera scanner. Sistem akan membaca token dan mengeksekusi check-in secara otomatis.
                </p>

                <div id="scanner_result" style="background: var(--color-bg-main); border: 1px dashed var(--line); border-radius: 8px; padding: 24px; min-height: 160px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 12px;">
                    <span style="font-size: 32px;">📷</span>
                    <span class="f-mono" style="font-size: 11px; color: var(--color-text-meta); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">Menunggu Input Scanner...</span>
                </div>
            </div>

            <div style="background: rgba(245, 197, 24, 0.06); border: 1px solid rgba(245, 197, 24, 0.18); border-radius: 8px; padding: 16px; display: flex; gap: 12px; align-items: flex-start; font-size: 12px; color: var(--color-secondary); font-weight: 500;">
                <span style="font-size: 14px; line-height: 1;">🛡️</span>
                <div>
                    <b class="f-mono" style="text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Otoritas Validasi Petugas</b>
                    Halaman ini seharusnya hanya bisa diakses oleh staf/admin. Jangan membagikan tautan endpoint scanner ini kepada pihak luar.
                </div>
            </div>
        </div>
    </main>

    <footer class="f-mono" style="padding: 24px 0; border-top: 1px solid var(--line); font-size: 11px; color: var(--color-text-meta); text-align: center; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">
        &copy; {{ date('Y') }} Futsal Mare HQ Terminal Secure Tunnel System.
    </footer>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const resultConsole = document.getElementById('scanner_result');
            const streamDot = document.getElementById('stream_dot');
            const streamText = document.getElementById('stream_text');
            let html5QrcodeScanner;

            function initScanner() {
                streamDot.style.background = "var(--color-primary)";
                streamDot.style.animation = "fm-pulse 1.6s infinite";
                streamText.innerText = "STREAMING";

                html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }

            function onScanFailure(error) {
                // SENGAJA DIBIARKAN KOSONG.
                // Ini dipanggil setiap frame kamera TIDAK menemukan QR code —
                // kondisi normal yang terjadi puluhan kali per detik selama
                // kamera aktif (mis. saat QR belum diarahkan tepat, terlalu
                // jauh, atau sedang bergerak). Ini bukan error nyata, jadi
                // tidak perlu ditampilkan di UI maupun console.
            }

            function onScanSuccess(decodedText, decodedResult) {
                html5QrcodeScanner.clear();

                streamDot.style.background = "var(--color-text-meta)";
                streamDot.style.animation = "none";
                streamText.innerText = "STANDBY";

                resultConsole.innerHTML = `
                    <div class="f-mono" style="font-size: 12px; color: var(--color-secondary); font-weight: 700; text-transform: uppercase;">
                        ⏳ Memproses Token Match: ${decodedText}...
                    </div>
                `;

                fetch("{{ route('admin.staff.checkin') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({ nomor_reservasi: decodedText })
                })
                .then(async response => {
                    const isJson = response.headers.get('content-type')?.includes('application/json');
                    const data = isJson ? await response.json() : null;
                    return { ok: response.ok, data };
                })
                .then(({ ok, data }) => {
                    if (data && data.success) {
                        resultConsole.innerHTML = `
                            <span style="font-size: 32px;">✅</span>
                            <b class="f-mono" style="font-size: 14px; color: var(--success); text-transform: uppercase; letter-spacing: 0.02em;">Check-In Sukses!</b>
                            <p style="font-size: 12px; color: var(--color-text-muted); font-weight: 500; margin-bottom: 8px;">${data.message}</p>
                            <button id="btn_reset_scanner" class="btn-scan btn-scan-primary mt-2">🔄 Reset &amp; Pindai Ulang</button>
                        `;
                    } else {
                        resultConsole.innerHTML = `
                            <span style="font-size: 32px;">❌</span>
                            <b class="f-mono" style="font-size: 14px; color: var(--danger); text-transform: uppercase; letter-spacing: 0.02em;">Gagal Validasi!</b>
                            <p style="font-size: 12px; color: var(--color-text-muted); font-weight: 500; margin-bottom: 8px;">${(data && data.message) || 'Terjadi kesalahan tak terduga, silakan coba lagi.'}</p>
                            <button id="btn_reset_scanner" class="btn-scan btn-scan-primary mt-2">🔄 Coba Pindai Lagi</button>
                        `;
                    }

                    document.getElementById('btn_reset_scanner').addEventListener('click', function () {
                        resultConsole.innerHTML = `
                            <span style="font-size: 32px;">📷</span>
                            <span class="f-mono" style="font-size: 11px; color: var(--color-text-meta); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">Menunggu Input Scanner...</span>
                        `;
                        initScanner();
                    });
                })
                .catch(() => {
                    resultConsole.innerHTML = `
                        <span style="font-size: 32px;">⚠️</span>
                        <b class="f-mono" style="font-size: 13px; color: var(--danger); text-transform: uppercase;">Koneksi Terputus!</b>
                        <p style="font-size: 12px; color: var(--color-text-muted); font-weight: 500; margin-bottom: 8px;">Gagal berkomunikasi dengan server. Periksa koneksi jaringan.</p>
                        <button id="btn_reset_scanner" class="btn-scan btn-scan-primary mt-2">🔄 Hubungkan Ulang Kamera</button>
                    `;
                    document.getElementById('btn_reset_scanner').addEventListener('click', function () {
                        location.reload();
                    });
                });
            }

            initScanner();
        });
    </script>
</body>
</html>