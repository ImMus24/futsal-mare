<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Gate Scanner — Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- HTML5 Qrcode Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>

    <style>
        :root {
            --ink: #0a0f16;
            --surface: #121a24;
            --surface-raised: #1a2432;
            --surface-3: #212d3c;
            --turf: #e2601f;
            --turf-dark: #b8481a;
            --turf-glow: rgba(226, 96, 31, 0.25);
            --floodlight: #f5c518;
            --line: rgba(255, 255, 255, 0.08);
            --line-strong: rgba(255, 255, 255, 0.16);
            --text-main: #ffffff;
            --muted: #94a3b8;
            --muted-2: #5b6b81;
            --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.12); --success-border: rgba(34, 197, 94, 0.25);
            --danger: #EF4444; --danger-bg: rgba(239, 68, 68, 0.12); --danger-border: rgba(239, 68, 68, 0.25);
            --radius-lg: 18px;
            --radius-md: 12px;
            --ease: cubic-bezier(.22, 1, .36, 1);
            --display: 'Anton', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--ink);
            color: var(--text-main);
            font-family: 'Work Sans', sans-serif;
            background-image:
                radial-gradient(circle at 10% 0%, rgba(226, 96, 31, 0.07), transparent 45%),
                radial-gradient(circle at 100% 15%, rgba(59, 130, 246, 0.05), transparent 40%),
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: auto, auto, 42px 42px, 42px 42px;
            background-position: 0 0, 0 0, -1px -1px, -1px -1px;
        }

        h1, h3 { font-family: var(--display); letter-spacing: .02em; text-transform: uppercase; }

        .eyebrow {
            font-family: var(--mono); font-size: 11px; letter-spacing: .15em;
            text-transform: uppercase; color: var(--turf); font-weight: 600;
            display: inline-flex; align-items: center; gap: 8px;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            transition: border-color .25s ease;
        }
        .card:hover { border-color: var(--line-strong); }

        .scanner-frame { position: relative; }
        .scanner-frame::after {
            content: ""; position: absolute; inset: 16px;
            border: 2px dashed rgba(255, 255, 255, 0.14);
            border-radius: 10px; pointer-events: none;
        }

        /* Buttons */
        .btn-brutal {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 12px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
            letter-spacing: .06em; transition: all 0.2s var(--ease); font-family: var(--mono);
            width: 100%;
        }
        .btn-brutal-primary { background: linear-gradient(135deg, var(--turf), var(--turf-dark)); color: white; box-shadow: 0 6px 16px var(--turf-glow); }
        .btn-brutal-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 22px var(--turf-glow); }
        .btn-brutal-secondary { background: var(--surface-3); color: var(--text-main); border-color: var(--line); }
        .btn-brutal-secondary:hover { background: var(--surface-raised); border-color: var(--line-strong); }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.92); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fade-up .5s var(--ease) both; }

        /* HTML5-QRCode video override */
        #reader video {
            object-fit: cover !important;
            border-radius: 10px;
        }
        #reader { position: relative; }
        #reader::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.15), transparent 20%, transparent 80%, rgba(0,0,0,0.15));
            pointer-events: none; z-index: 2; border-radius: 10px;
        }

        .result-console {
            background: var(--ink);
            border: 1px dashed rgba(255, 255, 255, 0.14);
            border-radius: var(--radius-md);
            transition: border-color .2s ease, background .2s ease;
        }

        .info-box {
            background: rgba(245, 197, 24, 0.06);
            border: 1px solid rgba(245, 197, 24, 0.18);
            border-radius: var(--radius-md);
        }

        .status-pip { width: 8px; height: 8px; border-radius: 50%; background: var(--turf); animation: pulse 1.6s infinite; }

        @media (max-width: 860px) {
            main { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP NAVIGATION STICKY BAR -->
    <header style="background: rgba(10, 15, 22, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 78px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-family: var(--display); font-size: 18px; color: white; background: linear-gradient(135deg, var(--turf), var(--turf-dark)); transform: rotate(-2deg); box-shadow: 0 4px 14px var(--turf-glow);">M</div>
                <div>
                    <div style="font-family: var(--display); font-size: 17px; color: white; line-height: 1;">FUTSAL MARE STAFF</div>
                    <div style="font-family: var(--mono); font-size: 9px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .12em; margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--success);" class="status-pip"></span>
                        Gate Terminal Access
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               style="font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700; border: 1px solid var(--line); padding: 9px 16px; border-radius: 10px; text-decoration: none; transition: all .2s ease; display: inline-flex; align-items: center; gap: 6px;"
               onmouseover="this.style.color='#fff'; this.style.borderColor='var(--line-strong)'"
               onmouseout="this.style.color='var(--muted)'; this.style.borderColor='var(--line)'">
                &larr; Dashboard Admin
            </a>
        </div>
    </header>

    <!-- MAIN TERMINAL SCANNER VIEWPORT -->
    <main style="max-width: 1180px; margin: 0 auto; padding: 40px 24px; width: 100%; flex: 1; display: grid; grid-template-columns: 1.2fr 1fr; gap: 28px; align-items: start;">

        <!-- LEFT PANEL: CAMERA VIEWPORT TERMINAL -->
        <div class="card scanner-frame fade-in" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <span class="eyebrow" style="margin-bottom: 4px; display: inline-flex;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--turf);"></span>
                        Live Camera Feed
                    </span>
                    <h3 style="font-size: 16px; color: white; margin-top: 4px;">Kamera Terminal Gate Scanner</h3>
                </div>
                <div style="font-family: var(--mono); font-size: 11px; color: var(--turf); font-weight: 700; display: flex; align-items: center; gap: 6px;">
                    <span id="stream_dot" class="status-pip"></span>
                    <span id="stream_text">INITIALIZING</span>
                </div>
            </div>

            <!-- Video Reader Placeholder -->
            <div id="reader" style="width: 100%; min-height: 320px; background: var(--ink); border-radius: 10px; overflow: hidden; border: 1px solid var(--line); display: flex; align-items: center; justify-content: center;"></div>

            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <span style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); display: inline-flex; align-items: center; gap: 6px;">
                    🔒 STATUS: SECURE WEBCAM ACCESS
                </span>
                <button id="btn_toggle_camera" class="btn-brutal btn-brutal-secondary" style="width: auto; padding: 8px 14px; font-size: 10px;">🔄 Ganti Kamera</button>
            </div>
        </div>

        <!-- RIGHT PANEL: CHECK-IN VERIFICATION AND STATUS LOG -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card fade-in" style="padding: 28px;">
                <span class="eyebrow" style="margin-bottom: 6px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--turf);"></span>
                    Verifikasi Real-Time
                </span>
                <h3 style="font-size: 16px; margin: 4px 0 12px; color: white;">Konsol Verifikasi Instan</h3>
                <p style="font-size: 13px; color: var(--muted); line-height: 1.65; font-weight: 500; margin-bottom: 20px;">
                    Arahkan Kode QR E-Tiket tim pelanggan tepat ke arah lensa kamera scanner. Sistem akan mengeksekusi mutasi check-in digital secara real-time.
                </p>

                <!-- Status Console Result Sheet -->
                <div id="scanner_result" class="result-console" style="padding: 24px; min-height: 200px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 12px;">
                    <span style="font-size: 36px; opacity: 0.6;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">Menunggu Input QR Code...</span>
                </div>
            </div>

            <!-- Notice Framework Info Box -->
            <div class="info-box fade-in" style="padding: 18px; display: flex; gap: 12px; align-items: flex-start; font-size: 12px; color: var(--floodlight); font-weight: 500;">
                <span style="font-size: 18px; line-height: 1;">🛡️</span>
                <div>
                    <b style="text-transform: uppercase; font-family: var(--mono); letter-spacing: 0.06em; display: block; margin-bottom: 4px; font-size: 11px;">Otoritas Validasi Petugas</b>
                    <span style="color: var(--muted); font-weight: 500;">Halaman ini diamankan menggunakan middleware internal. Tiket hanya dapat di-scan 1 kali untuk mencegah duplicate entry.</span>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SYSTEM CONTEXT -->
    <footer style="padding: 24px 0; border-top: 1px solid var(--line); font-size: 11px; color: var(--muted-2); text-align: center; font-family: var(--mono); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">
        &copy; {{ date('Y') }} Futsal Mare HQ Terminal Secure Tunnel System.
    </footer>

    <!-- SCANNING SCRIPT CONFIGURATION ENGINE -->
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const resultConsole = document.getElementById('scanner_result');
            const streamDot = document.getElementById('stream_dot');
            const streamText = document.getElementById('stream_text');
            const btnToggleCam = document.getElementById('btn_toggle_camera');

            let html5QrCode;
            let currentCameraIndex = 0;
            let availableCameras = [];
            let isProcessing = false;

            // Simple Web Audio API Beep Generator untuk respon suara
            function playBeep(isSuccess = true) {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.type = isSuccess ? 'sine' : 'sawtooth';
                    osc.frequency.value = isSuccess ? 880 : 300; // Pitch tinggi untuk sukses, rendah untuk gagal
                    gain.gain.value = 0.1;

                    osc.start();
                    osc.stop(ctx.currentTime + (isSuccess ? 0.15 : 0.3));
                } catch (e) {
                    // Audio context mungkin diblokir browser jika pengguna belum interaksi
                }
            }

            function startScanner(cameraId) {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                setCameraStatus(true, "STREAMING");

                const config = {
                    fps: 10,
                    qrbox: { width: 220, height: 220 },
                    aspectRatio: 1.0
                };

                html5QrCode.start(cameraId, config, onScanSuccess)
                .catch(err => {
                    console.error("Gagal membuka kamera: ", err);
                    setCameraStatus(false, "ERROR KAMERA");
                    resultConsole.innerHTML = `
                        <span style="font-size: 32px;">⚠️</span>
                        <b style="font-family: var(--mono); font-size: 13px; color: #EF4444; text-transform: uppercase;">Akses Kamera Ditolak!</b>
                        <p style="font-size: 12px; color: var(--muted); margin-bottom: 8px;">Mohon izinkan akses kamera pada peramban Anda.</p>
                    `;
                });
            }

            function setCameraStatus(active, text) {
                if (streamDot && streamText) {
                    streamDot.style.background = active ? "var(--turf)" : "var(--muted-2)";
                    streamDot.style.animation = active ? "pulse 1.6s infinite" : "none";
                    streamText.innerText = text;
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                if (isProcessing) return; // Mencegah multiple request sekaligus
                isProcessing = true;

                // Hentikan scanner sementara saat data dikirim ke backend
                html5QrCode.pause();
                setCameraStatus(false, "STANDBY / VERIFYING");

                resultConsole.innerHTML = `
                    <div style="font-family: var(--mono); font-size: 12px; color: var(--floodlight); font-weight: 700; text-transform: uppercase;">
                        ⏳ Memproses Kode: <br><span style="color: white;">${decodedText}</span>...
                    </div>
                `;

                // Kirim request ke backend
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
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        playBeep(true);
                        resultConsole.style.borderColor = 'var(--success-border)';
                        resultConsole.style.background = 'var(--success-bg)';
                        resultConsole.innerHTML = `
                            <span style="font-size: 36px;">✅</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: #22C55E; text-transform: uppercase; letter-spacing: 0.02em;">Check-In Sukses!</b>
                            <p style="font-size: 12px; color: var(--text-main); font-weight: 500; margin-top: 4px;">${data.message}</p>
                            ${data.data ? `
                                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; width: 100%; text-align: left; font-size: 11px; font-family: var(--mono); color: var(--muted); margin-top: 8px;">
                                    <div>PEMESAN: <b style="color:white;">${data.data.nama_pemesan || '-'}</b></div>
                                    <div>LAPANGAN: <b style="color:white;">${data.data.lapangan || '-'}</b></div>
                                </div>
                            ` : ''}
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Reset & Pindai Ulang</button>
                        `;
                    } else {
                        playBeep(false);
                        resultConsole.style.borderColor = 'var(--danger-border)';
                        resultConsole.style.background = 'var(--danger-bg)';
                        resultConsole.innerHTML = `
                            <span style="font-size: 36px;">❌</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: #EF4444; text-transform: uppercase; letter-spacing: 0.02em;">Gagal Validasi!</b>
                            <p style="font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 4px;">${data.message}</p>
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Coba Pindai Lagi</button>
                        `;
                    }

                    document.getElementById('btn_reset_scanner')?.addEventListener('click', resetConsoleAndRestart);
                })
                .catch(err => {
                    playBeep(false);
                    resultConsole.style.borderColor = 'var(--danger-border)';
                    resultConsole.style.background = 'var(--danger-bg)';
                    resultConsole.innerHTML = `
                        <span style="font-size: 36px;">⚠️</span>
                        <b style="font-family: var(--mono); font-size: 13px; color: #EF4444; text-transform: uppercase;">Koneksi Server Terputus!</b>
                        <p style="font-size: 12px; color: var(--muted); font-weight: 500;">Gagal terhubung ke API backend.</p>
                        <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Coba Lagi</button>
                    `;
                    document.getElementById('btn_reset_scanner')?.addEventListener('click', resetConsoleAndRestart);
                });
            }

            function resetConsoleAndRestart() {
                isProcessing = false;
                resultConsole.style.borderColor = '';
                resultConsole.style.background = '';
                resultConsole.innerHTML = `
                    <span style="font-size: 36px; opacity: 0.6;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">Menunggu Input QR Code...</span>
                `;

                if (html5QrCode) {
                    html5QrCode.resume();
                    setCameraStatus(true, "STREAMING");
                }
            }

            // Inisialisasi daftar kamera perangkat
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    availableCameras = devices;
                    // Preferensi kamera belakang jika ada
                    const backCam = devices.find(cam => cam.label.toLowerCase().includes('back') || cam.label.toLowerCase().includes('rear'));
                    const selectedCam = backCam ? backCam.id : devices[0].id;

                    startScanner(selectedCam);

                    // Sembunyikan/tampilkan tombol ganti kamera tergantung jumlah kamera
                    if (devices.length > 1) {
                        btnToggleCam.addEventListener('click', function () {
                            currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
                            html5QrCode.stop().then(() => {
                                startScanner(availableCameras[currentCameraIndex].id);
                            });
                        });
                    } else {
                        btnToggleCam.style.display = 'none';
                    }
                } else {
                    setCameraStatus(false, "NO CAMERA");
                    resultConsole.innerHTML = `<p style="color:#EF4444; font-size:12px;">Perangkat kamera tidak ditemukan.</p>`;
                }
            }).catch(err => {
                setCameraStatus(false, "ERROR");
                console.error("Gagal mendapatkan daftar kamera: ", err);
            });
        });
    </script>
</body>
</html>