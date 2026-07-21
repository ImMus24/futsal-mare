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
            --turf-light: #ff8a4c;
            --turf-glow: rgba(226, 96, 31, 0.25);
            --floodlight: #f5c518;
            --line: #eef1ea;
            --line-soft: rgba(255,255,255,0.08);
            --line-strong: rgba(255,255,255,0.16);
            --muted: #94a3b8;
            --muted-2: #5b6b81;
            --success: #22C55E; --success-bg: rgba(34,197,94,0.12); --success-border: rgba(34,197,94,0.28);
            --danger:  #EF4444; --danger-bg: rgba(239,68,68,0.12); --danger-border: rgba(239,68,68,0.28);
            --info:    #3B82F6; --info-bg: rgba(59,130,246,0.12); --info-border: rgba(59,130,246,0.28);
            --radius-lg: 18px;
            --radius: 14px;
            --radius-sm: 8px;
            --ease: cubic-bezier(.22, 1, .36, 1);
            --display: 'Anton', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; }
        body {
            background: var(--ink);
            color: var(--line);
            font-family: 'Work Sans', sans-serif;
            background-image:
                radial-gradient(circle at 10% 0%, rgba(226, 96, 31, 0.07), transparent 45%),
                radial-gradient(circle at 100% 15%, rgba(59, 130, 246, 0.06), transparent 40%);
        }
        h1, h3 { font-family: var(--display); letter-spacing: .01em; text-transform: uppercase; }

        @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(0.95); } }
        @keyframes fade-up { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes corner-sweep { 0%, 100% { opacity: .55; } 50% { opacity: 1; } }

        .fade-in { animation: fade-up .5s var(--ease) both; }
        .fade-in.delay-1 { animation-delay: .08s; }
        .fade-in.delay-2 { animation-delay: .16s; }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-lg);
            transition: border-color .25s ease;
        }
        .panel:hover { border-color: var(--line-strong); }

        /* Scanner viewport frame with corner brackets instead of plain dashed border */
        .scanner-frame { position: relative; }
        .scanner-frame .corner {
            position: absolute; width: 26px; height: 26px;
            border-color: var(--turf); opacity: .7;
            animation: corner-sweep 2.4s ease-in-out infinite;
        }
        .corner-tl { top: 14px; left: 14px; border-top: 3px solid; border-left: 3px solid; border-radius: 6px 0 0 0; }
        .corner-tr { top: 14px; right: 14px; border-top: 3px solid; border-right: 3px solid; border-radius: 0 6px 0 0; }
        .corner-bl { bottom: 14px; left: 14px; border-bottom: 3px solid; border-left: 3px solid; border-radius: 0 0 0 6px; }
        .corner-br { bottom: 14px; right: 14px; border-bottom: 3px solid; border-right: 3px solid; border-radius: 0 0 6px 0; }

        /* Buttons */
        .btn-brutal {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 20px; border-radius: var(--radius-sm); font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
            letter-spacing: .05em; transition: all 0.18s var(--ease); font-family: var(--mono);
            width: 100%;
        }
        .btn-brutal-primary { background: linear-gradient(135deg, var(--turf-light), var(--turf-dark)); color: white; box-shadow: 0 6px 18px var(--turf-glow); }
        .btn-brutal-primary:hover { filter: brightness(1.08); transform: translateY(-1px); }
        .btn-brutal-secondary { background: var(--surface-3); color: var(--line); border-color: var(--line-soft); }
        .btn-brutal-secondary:hover { background: #2c3c50; border-color: var(--line-strong); }

        .nav-link {
            font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase;
            font-weight: 700; border: 1px solid var(--line-soft); padding: 9px 15px; border-radius: var(--radius-sm);
            text-decoration: none; transition: all .2s var(--ease); background: var(--surface);
        }
        .nav-link:hover { color: #fff; border-color: var(--line-strong); background: var(--surface-raised); }

        /* Status pill */
        .status-pill {
            font-family: var(--mono); font-size: 10px; font-weight: 700; letter-spacing: .06em;
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 999px;
            text-transform: uppercase; background: var(--surface-3); border: 1px solid var(--line-soft);
        }

        /* Result console */
        #scanner_result {
            background: var(--ink);
            border: 1px dashed var(--line-soft);
            border-radius: var(--radius);
            padding: 26px;
            min-height: 210px;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; gap: 12px;
            transition: border-color .25s ease;
        }

        .info-box {
            border-radius: var(--radius-sm);
            padding: 16px;
            display: flex; gap: 12px; align-items: flex-start;
            font-size: 12px; font-weight: 500;
        }

        /* Override styling elemen video bawaan HTML5-QRCode */
        #reader { background: var(--ink); }
        #reader video { object-fit: cover !important; border-radius: 10px; }

        ::selection { background: var(--turf-glow); }

        @media (max-width: 860px) {
            main { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between">

    <!-- TOP NAVIGATION STICKY BAR -->
    <header style="background: rgba(10, 15, 22, 0.85); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.07); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 80px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, var(--turf-light), var(--turf-dark)); transform: rotate(-2deg); box-shadow: 0 4px 14px var(--turf-glow);">
                    <span style="font-family: var(--display); color: white; font-size: 18px;">M</span>
                </div>
                <div>
                    <div style="font-family: var(--display); font-size: 18px; color: white; line-height: 1;">FUTSAL MARE STAFF</div>
                    <div style="font-family: var(--mono); font-size: 9px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .12em; margin-top: 3px; display:flex; align-items:center; gap:6px;">
                        <span style="width:6px; height:6px; border-radius:50%; background: var(--success); animation: pulse 1.8s infinite;"></span>
                        Gate Terminal Access
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">&larr; Dashboard Admin</a>
        </div>
    </header>

    <!-- MAIN TERMINAL SCANNER VIEWPORT -->
    <main style="max-width: 1180px; margin: 0 auto; padding: 40px 24px; width: 100%; flex: 1; display: grid; grid-template-columns: 1.2fr 1fr; gap: 28px; align-items: start;">

        <!-- LEFT PANEL: CAMERA VIEWPORT TERMINAL -->
        <div class="panel fade-in" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
                <h3 style="font-size: 16px; color: white; letter-spacing: 0.04em;">Kamera Terminal Gate Scanner</h3>
                <div class="status-pill" style="color: var(--turf); border-color: rgba(226,96,31,0.25); background: rgba(226,96,31,0.08);">
                    <span id="stream_dot" style="width: 8px; height: 8px; border-radius: 50%; background: var(--turf); animation: pulse 1.6s infinite;"></span>
                    <span id="stream_text">INITIALIZING</span>
                </div>
            </div>

            <!-- Video Reader -->
            <div class="scanner-frame" style="border-radius: 10px; overflow: hidden; border: 1px solid var(--line-soft);">
                <div id="reader" style="width: 100%; min-height: 320px; display: flex; align-items: center; justify-content: center;"></div>
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
            </div>

            <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <span style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); display:flex; align-items:center; gap:6px;">
                    🔒 STATUS: SECURE WEBCAM ACCESS
                </span>
                <button id="btn_toggle_camera" class="btn-brutal btn-brutal-secondary" style="width: auto; padding: 8px 14px; font-size: 10px;">🔄 Ganti Kamera</button>
            </div>
        </div>

        <!-- RIGHT PANEL: CHECK-IN VERIFICATION AND STATUS LOG -->
        <div style="display: flex; flex-direction: column; gap: 18px;">
            <div class="panel fade-in delay-1" style="padding: 28px;">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: white; letter-spacing: 0.04em;">Konsol Verifikasi Instan</h3>
                <p style="font-size: 13px; color: var(--muted); line-height: 1.65; font-weight: 500; margin-bottom: 20px;">
                    Arahkan Kode QR E-Tiket tim pelanggan tepat ke arah lensa kamera scanner. Sistem akan mengeksekusi mutasi check-in digital secara <em>real-time</em>.
                </p>

                <!-- Status Console Result Sheet -->
                <div id="scanner_result">
                    <span style="font-size: 36px; opacity: 0.6;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;">Menunggu Input QR Code...</span>
                </div>
            </div>

            <!-- Notice Framework Info Box -->
            <div class="info-box fade-in delay-2" style="background: rgba(245, 197, 24, 0.06); border: 1px solid rgba(245, 197, 24, 0.18); color: var(--floodlight);">
                <span style="font-size: 16px; line-height: 1;">🛡️</span>
                <div>
                    <b style="text-transform: uppercase; font-family: var(--mono); letter-spacing: 0.05em; display: block; margin-bottom: 3px; font-size: 11px;">Otoritas Validasi Petugas</b>
                    <span style="color: #e7d18c;">Halaman ini diamankan menggunakan middleware internal. Tiket hanya dapat di-scan 1 kali untuk mencegah duplicate entry.</span>
                </div>
            </div>

            <!-- Quick stats micro-card -->
            <div class="panel fade-in delay-2" style="padding: 18px 22px; display:flex; align-items:center; justify-content:space-between;">
                <div style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); text-transform: uppercase; letter-spacing:.08em;">
                    Sesi Terminal Aktif
                </div>
                <div style="font-family: var(--mono); font-size: 11px; color: var(--success); font-weight: 700;">
                    ● ONLINE
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SYSTEM CONTEXT -->
    <footer style="padding: 24px 0; border-top: 1px solid var(--line-soft); font-size: 11px; color: var(--muted-2); text-align: center; font-family: var(--mono); font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;">
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
                    resultConsole.style.borderColor = "var(--danger-border)";
                    resultConsole.innerHTML = `
                        <span style="font-size: 32px;">⚠️</span>
                        <b style="font-family: var(--mono); font-size: 13px; color: var(--danger); text-transform: uppercase;">Akses Kamera Ditolak!</b>
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

                resultConsole.style.borderColor = "var(--line-soft)";
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
                        resultConsole.style.borderColor = "var(--success-border)";
                        resultConsole.innerHTML = `
                            <span style="font-size: 36px;">✅</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: var(--success); text-transform: uppercase; letter-spacing: 0.02em;">Check-In Sukses!</b>
                            <p style="font-size: 12px; color: var(--line); font-weight: 500; margin-top: 4px;">${data.message}</p>
                            ${data.data ? `
                                <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px; width: 100%; text-align: left; font-size: 11px; font-family: var(--mono); color: var(--muted); margin-top: 6px;">
                                    <div>PEMESAN: <b style="color:white;">${data.data.nama_pemesan || '-'}</b></div>
                                    <div>LAPANGAN: <b style="color:white;">${data.data.lapangan || '-'}</b></div>
                                </div>
                            ` : ''}
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Reset & Pindai Ulang</button>
                        `;
                    } else {
                        playBeep(false);
                        resultConsole.style.borderColor = "var(--danger-border)";
                        resultConsole.innerHTML = `
                            <span style="font-size: 36px;">❌</span>
                            <b style="font-family: var(--mono); font-size: 14px; color: var(--danger); text-transform: uppercase; letter-spacing: 0.02em;">Gagal Validasi!</b>
                            <p style="font-size: 12px; color: var(--muted); font-weight: 500; margin-top: 4px;">${data.message}</p>
                            <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Coba Pindai Lagi</button>
                        `;
                    }

                    document.getElementById('btn_reset_scanner')?.addEventListener('click', resetConsoleAndRestart);
                })
                .catch(err => {
                    playBeep(false);
                    resultConsole.style.borderColor = "var(--danger-border)";
                    resultConsole.innerHTML = `
                        <span style="font-size: 36px;">⚠️</span>
                        <b style="font-family: var(--mono); font-size: 13px; color: var(--danger); text-transform: uppercase;">Koneksi Server Terputus!</b>
                        <p style="font-size: 12px; color: var(--muted); font-weight: 500;">Gagal terhubung ke API backend.</p>
                        <button id="btn_reset_scanner" class="btn-brutal btn-brutal-primary mt-3">🔄 Coba Lagi</button>
                    `;
                    document.getElementById('btn_reset_scanner')?.addEventListener('click', resetConsoleAndRestart);
                });
            }

            function resetConsoleAndRestart() {
                isProcessing = false;
                resultConsole.style.borderColor = "var(--line-soft)";
                resultConsole.innerHTML = `
                    <span style="font-size: 36px; opacity: 0.6;">📷</span>
                    <span style="font-family: var(--mono); font-size: 11px; color: var(--muted-2); font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em;">Menunggu Input QR Code...</span>
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
                    resultConsole.innerHTML = `<p style="color:var(--danger); font-size:12px;">Perangkat kamera tidak ditemukan.</p>`;
                }
            }).catch(err => {
                setCameraStatus(false, "ERROR");
                console.error("Gagal mendapatkan daftar kamera: ", err);
            });
        });
    </script>
</body>
</html>