<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Gate Scanner — Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- HTML5 Qrcode Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --floodlight: #f5c518;
            --line: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }
        body { background: var(--ink); color: var(--line); font-family: 'Work Sans', sans-serif; }
        h1 { font-family: var(--display); letter-spacing: .01em; text-transform: uppercase; }
        .scanner-frame::after { content: ""; position: absolute; inset: 16px; border: 2px solid rgba(238, 241, 234, 0.2); border-radius: 8px; pointer-events: none; }
        
        /* Custom styling override untuk tombol brutal Futsal Mare */
        .btn-brutal {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; 
            letter-spacing: .05em; transition: all 0.15s ease; font-family: inherit;
        }
        .btn-brutal-primary { background: var(--turf); color: white; }
        .btn-brutal-primary:hover { background: #cb5119; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP NAVIGATION STICKY BAR -->
    <header style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(238, 241, 234, 0.08); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 80px;">
            <div style="display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 22px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE STAFF
            </div>
            <a href="{{ route('admin.dashboard') }}" style="font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700; border: 1px solid rgba(238,241,234,0.15); padding: 8px 14px; border-radius: 6px;">
                &larr; Dashboard Admin
            </a>
        </div>
    </header>

    <!-- MAIN TERMINAL SCANNER VIEWPORT -->
    <main style="max-width: 1180px; margin: 0 auto; padding: 40px 24px; width: 100%; flex: 1; display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start;" class="grid-cols-1 md:grid-cols-2">
        
        <!-- LEFT PANEL: CAMERA VIEWPORT TERMINAL -->
        <div style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: var(--radius); padding: 24px; position: relative;" class="scanner-frame">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px;">
                <h3 style="font-family: var(--display); font-size: 16px; color: white; letter-spacing: 0.05em;">KAMERA TERMINAL GATE SCANNER</h3>
                <div style="font-family: var(--mono); font-size: 11px; color: var(--turf); font-weight: 700; display: flex; align-items: center; gap: 6px;">
                    <span id="stream_dot" style="width: 7px; height: 7px; border-radius: 50%; background: var(--turf); animation: pulse 1.6s infinite;" class="inline-block"></span>
                    <span id="stream_text">STREAMING</span>
                </div>
            </div>
            
            <!-- Video Reader Placeholder -->
            <div id="reader" style="width: 100%; background: var(--ink); border-radius: 8px; overflow: hidden; border: 1px solid rgba(238,241,234,0.06);"></div>
        </div>

        <!-- RIGHT PANEL: CHECK-IN VERIFICATION AND STATUS LOG -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.1); border-radius: var(--radius); padding: 28px;">
                <h3 style="font-family: var(--display); font-size: 16px; margin-bottom: 12px; color: white; letter-spacing: 0.05em;">KONSOL VERIFIKASI INSTAN</h3>
                <p style="font-size: 13px; color: var(--muted); line-height: 1.6; font-weight: 500; margin-bottom: 20px;">
                    Arahkan Kode QR E-Tiket tim pelanggan tepat ke arah lensa kamera scanner. Sistem akan membaca token parameter dan mengeksekusi mutasi check-in digital.
                </p>

                <!-- Status Console Result Sheet -->
                <div id="scanner_result" style="background: var(--ink); border: 1px dashed rgba(238, 241, 234, 0.12); border-radius: 8px; padding: 24px; min-height: 160px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 12px;">
                    <span style="font-size: 32px;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">Menunggu Input Scanner...</span>
                </div>
            </div>

            <!-- Notice Framework Info Box -->
            <div style="background: rgba(245, 197, 24, 0.05); border: 1px solid rgba(245, 197, 24, 0.15); border-radius: 8px; padding: 16px; display: flex; gap: 12px; align-items: flex-start; font-size: 12px; color: var(--floodlight); font-weight: 500;">
                <span style="font-size: 14px; line-height: 1;">🛡️</span>
                <div>
                    <b style="text-transform: uppercase; font-family: var(--mono); letter-spacing: 0.05em; display: block; margin-bottom: 2px;">Otoritas Validasi Petugas</b>
                    Halaman ini diamankan menggunakan middleware enkripsi rute internal. Jangan membagikan tautan endpoint scanner ini kepada pihak luar.
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SYSTEM CONTEXT -->
    <footer style="padding: 24px 0; border-top: 1px solid rgba(238, 241, 234, 0.08); font-size: 11px; color: var(--muted-2); text-align: center; font-family: var(--mono); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">
        &copy; {{ date('Y') }} Futsal Mare HQ Terminal Secure Tunnel System.
    </footer>

    <!-- SCANNING SCRIPT CONFIGURATION ENGINE -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const resultConsole = document.getElementById('scanner_result');
            const streamDot = document.getElementById('stream_dot');
            const streamText = document.getElementById('stream_text');
            let html5QrcodeScanner;

            function initScanner() {
                // Kembalikan status indikator kamera ke mode aktif
                if (streamDot && streamText) {
                    streamDot.style.background = "var(--turf)";
                    streamDot.style.animation = "pulse 1.6s infinite";
                    streamText.innerText = "STREAMING";
                }

                // Render ulang pembaca QR Code html5-qrcode
                html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
                html5QrcodeScanner.render(onScanSuccess);
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Matikan scanner agar tidak menerima input QR lain selama pemrosesan data
                html5QrcodeScanner.clear();
                
                // Ubah status indikator kamera menjadi pause/berhenti sementara
                if (streamDot && streamText) {
                    streamDot.style.background = "var(--muted-2)";
                    streamDot.style.animation = "none";
                    streamText.innerText = "STANDBY";
                }
                
                resultConsole.innerHTML = `
                    <div style="font-family: var(--mono); font-size: 12px; color: var(--floodlight); font-weight: 700; text-transform: uppercase;">
                        ⏳ Memproses Token Match: ${decodedText}...
                    </div>
                `;

                // ✅ FIX: Memanggil route dengan prefix 'admin.' yang sesuai dengan routes/web.php
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        resultConsole.innerHTML = `
                            <span style="font-size: 32px;">✅</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: #2f9e58; text-transform: uppercase; letter-spacing: 0.02em;">Check-In Sukses!</b>
                            <p style="font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 8px;">${data.message}</p>
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-2">🔄 Reset & Pindai Ulang</button>
                        `;
                    } else {
                        resultConsole.innerHTML = `
                            <span style="font-size: 32px;">❌</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: #e2574c; text-transform: uppercase; letter-spacing: 0.02em;">Gagal Validasi!</b>
                            <p style="font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 8px;">${data.message}</p>
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-2">🔄 Coba Pindai Lagi</button>
                        `;
                    }
                    
                    // Daftarkan event listener interaktif pada tombol reset manual baru
                    document.getElementById('btn_reset_scanner')?.addEventListener('click', resetConsoleAndRestart);
                })
                .catch(err => {
                    resultConsole.innerHTML = `
                        <span style="font-size: 32px;">⚠️</span>
                        <b style="font-family: var(--mono); font-size: 13px; color: #e2574c; text-transform: uppercase;">Tunnel API Terputus!</b>
                        <p style="font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 8px;">Gagal berkomunikasi dengan server master.</p>
                        <button id="btn_reload_scanner" class="btn-brutal btn-brutal-primary mt-2">🔄 Hubungkan Ulang Kamera</button>
                    `;
                    document.getElementById('btn_reload_scanner')?.addEventListener('click', function() {
                        location.reload();
                    });
                });
            }

            function resetConsoleAndRestart() {
                resultConsole.innerHTML = `
                    <span style="font-size: 32px;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">Menunggu Input Scanner...</span>
                `;
                initScanner();
            }

            // Jalankan inisialisasi scanner pertama saat DOM siap dimuat
            initScanner();
        });
    </script>
</body>
</html>