<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Mare - Reservasi Lapangan Premium Kota Baubau</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=JetBrains+Mono:ital,wght@0,400..800;1,400..800&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --ink: #080c10;
            --surface: #0f1620;
            --surface-2: #16202c;
            --surface-3: #1f2c3c;
            --turf: #e25e20;
            --turf-dark: #b84513;
            --turf-glow: rgba(226, 94, 32, 0.25);
            --floodlight: #f5c518;
            --floodlight-dim: rgba(245, 197, 24, 0.12);
            --line: #f1f5f9;
            --muted: #94a3b8;
            --muted-2: #64748b;
            --radius-lg: 16px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --display: 'Anton', sans-serif;
            --body: 'Plus Jakarta Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            background-color: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        img { display: block; max-width: 100%; height: auto; }

        .wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px; }

        /* TYPOGRAPHY UTILS */
        h1, h2, h3, .font-display { font-family: var(--display); font-weight: 400; letter-spacing: .02em; text-transform: uppercase; }
        .font-mono { font-family: var(--mono); }
        
        .eyebrow {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--turf);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            background: rgba(226, 94, 32, 0.1);
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid rgba(226, 94, 32, 0.2);
        }

        /* BUTTON SYSTEM */
        .btn-ui {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all .2s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: var(--body);
            text-transform: uppercase;
            letter-spacing: .04em;
            text-decoration: none;
        }
        .btn-ui:hover { transform: translateY(-2px); }
        .btn-ui:active { transform: translateY(0); }
        .btn-ui-primary {
            background: var(--turf);
            color: white;
            box-shadow: 0 4px 20px var(--turf-glow);
        }
        .btn-ui-primary:hover { background: var(--turf-dark); box-shadow: 0 6px 24px rgba(226, 94, 32, 0.4); }
        .btn-ui-ghost {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(241, 245, 249, 0.15);
            color: var(--line);
            backdrop-filter: blur(8px);
        }
        .btn-ui-ghost:hover { border-color: var(--line); background: rgba(255, 255, 255, 0.08); }
        .btn-ui-sm { padding: 9px 18px; font-size: 12px; border-radius: var(--radius-sm); }

        /* GLASS & CARDS */
        .glass-panel {
            background: var(--surface);
            border: 1px solid rgba(241, 245, 249, 0.08);
            border-radius: var(--radius-lg);
        }
        .glass-card {
            background: linear-gradient(180deg, var(--surface-2) 0%, var(--surface) 100%);
            border: 1px solid rgba(241, 245, 249, 0.08);
            border-radius: var(--radius-lg);
            transition: all .25s ease;
        }
        .glass-card:hover {
            border-color: rgba(226, 94, 32, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(0,0,0,0.5);
        }

        /* HEADER */
        header {
            position: sticky; top: 0; z-index: 100;
            background: rgba(8, 12, 16, 0.82);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(241, 245, 249, 0.06);
        }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 18px 24px; }
        .logo { display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 26px; color: white; text-decoration: none; }
        .logo .dot { width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg); }
        .nav-links { display: flex; gap: 32px; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; }
        .nav-links a { color: var(--muted); text-decoration: none; transition: color .2s ease; }
        .nav-links a:hover { color: var(--line); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }

        /* HERO */
        .hero {
            position: relative;
            padding: 96px 0 72px;
            background: radial-gradient(circle at 80% 20%, var(--turf-glow) 0%, transparent 45%),
                        radial-gradient(circle at 20% 80%, rgba(245, 197, 24, 0.05) 0%, transparent 40%),
                        var(--ink);
            border-bottom: 1px solid rgba(241, 245, 249, 0.06);
        }
        .hero-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 48px; align-items: center; }
        @media (max-width: 960px) { .hero-grid { grid-template-columns: 1fr; } }
        .hero h1 { font-size: clamp(40px, 5.5vw, 68px); line-height: 0.95; margin: 20px 0; }
        .hero h1 span { color: var(--turf); }
        .hero p.lead { color: var(--muted); font-size: 16px; max-width: 540px; margin-bottom: 36px; font-weight: 500; }
        .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 48px; }
        .stat-row { display: flex; gap: 40px; border-top: 1px dashed rgba(241, 245, 249, 0.1); padding-top: 24px; }
        .stat b { display: block; font-family: var(--mono); font-size: 28px; color: var(--floodlight); line-height: 1; margin-bottom: 4px; }
        .stat span { font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; }

        /* LIVE BOARD WIDGET */
        .board-card {
            background: var(--surface);
            border: 1px solid rgba(241, 245, 249, 0.1);
            border-radius: var(--radius-lg);
            padding: 28px;
            position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.6);
            overflow: hidden;
        }
        .board-card::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--turf), var(--floodlight));
        }
        .board-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .board-head h3 { font-size: 13px; letter-spacing: .08em; color: var(--muted); font-family: var(--mono); }
        .live-tag {
            font-family: var(--mono); font-size: 11px; color: #2f9e58; background: rgba(47, 158, 88, 0.1);
            padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(47, 158, 88, 0.2);
            display: flex; align-items: center; gap: 6px; font-weight: 700;
        }
        .live-tag .pip { width: 6px; height: 6px; border-radius: 50%; background: #2f9e58; animation: pulse 1.6s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .4; transform: scale(0.8); } }

        /* SECTIONS */
        section { padding: 96px 0; border-bottom: 1px solid rgba(241, 245, 249, 0.06); }
        .sec-head { max-width: 620px; margin-bottom: 56px; }
        .sec-head h2 { font-size: clamp(32px, 4vw, 46px); margin-top: 14px; line-height: 1; }
        .sec-head p { color: var(--muted); margin-top: 14px; font-size: 15px; font-weight: 500; }

        /* COURTS GRID */
        .courts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
        @media (max-width: 920px) { .courts-grid { grid-template-columns: 1fr; } }
        .court-card { display: flex; flex-direction: column; justify-content: space-between; height: 100%; overflow: hidden; }
        .court-media { height: 210px; position: relative; background: var(--surface-3); overflow: hidden; }
        .court-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
        .court-card:hover .court-media img { transform: scale(1.05); }
        .court-media .price-tag {
            position: absolute; bottom: 12px; right: 12px;
            background: rgba(8, 12, 16, 0.88); backdrop-filter: blur(8px);
            border: 1px solid var(--turf); color: var(--turf);
            font-family: var(--mono); font-size: 12px; padding: 6px 12px; border-radius: var(--radius-sm); font-weight: 700;
        }
        .court-body { padding: 24px; flex-grow: 1; }
        .court-body h3 { font-family: var(--body); font-weight: 800; font-size: 20px; text-transform: none; margin-bottom: 8px; color: white; }
        .court-meta { display: flex; gap: 10px; font-size: 11px; color: var(--muted); margin-bottom: 14px; font-family: var(--mono); font-weight: 700; }
        .court-desc { color: var(--muted); font-size: 13px; line-height: 1.6; font-weight: 500; }

        /* MEMBERSHIP BENTO */
        .tier-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
        @media (max-width: 860px) { .tier-grid { grid-template-columns: 1fr; } }
        .tier-card { padding: 32px; position: relative; }
        .tier-card.gold { border-color: var(--turf); background: linear-gradient(180deg, rgba(226, 94, 32, 0.08) 0%, var(--surface) 100%); }
        .tier-badge {
            position: absolute; top: 16px; right: 16px; font-family: var(--mono); font-size: 10px;
            background: var(--turf); color: white; padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;
        }
        .tier-card h3 { font-size: 20px; margin-bottom: 6px; font-family: var(--body); font-weight: 800; text-transform: none; }
        .tier-points { font-family: var(--mono); font-size: 12px; color: var(--turf); margin-bottom: 24px; display: block; font-weight: 700; }
        .tier-card ul { list-style: none; display: flex; flex-direction: column; gap: 14px; }
        .tier-card li { font-size: 13px; color: var(--muted-2); display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .tier-card li.active { color: var(--line); font-weight: 600; }

        /* STEPS */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
        @media (max-width: 860px) { .steps { grid-template-columns: 1fr; } }
        .step-num {
            font-family: var(--mono); font-size: 14px; color: var(--floodlight);
            border: 1px solid var(--floodlight); background: var(--floodlight-dim);
            width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-weight: 700;
        }
        .step h3 { font-family: var(--body); font-weight: 700; text-transform: none; font-size: 18px; margin-bottom: 10px; color: white; }
        .step p { color: var(--muted); font-size: 14px; line-height: 1.6; font-weight: 500; }

        /* FAQ STYLES */
        .faq-search-box {
            position: relative;
            margin-top: 24px;
            max-width: 480px;
        }
        .faq-search-input {
            width: 100%;
            background: var(--surface-2);
            border: 1px solid rgba(241, 245, 249, 0.12);
            color: var(--line);
            padding: 14px 18px 14px 44px;
            border-radius: var(--radius-md);
            font-family: var(--body);
            font-size: 14px;
            outline: none;
            transition: all .2s ease;
        }
        .faq-search-input:focus {
            border-color: var(--turf);
            box-shadow: 0 0 15px var(--turf-glow);
        }
        .faq-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-2);
            width: 18px;
            height: 18px;
        }
        .faq-item {
            background: var(--surface);
            border: 1px solid rgba(241, 245, 249, 0.08);
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            overflow: hidden;
            transition: border-color .2s ease;
        }
        .faq-item:hover {
            border-color: rgba(226, 94, 32, 0.3);
        }
        .faq-btn {
            width: 100%;
            padding: 18px 20px;
            background: none;
            border: none;
            color: white;
            font-family: var(--body);
            font-size: 15px;
            font-weight: 700;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            gap: 16px;
        }
        .faq-chevron {
            width: 18px;
            height: 18px;
            color: var(--turf);
            transition: transform .25s ease;
            flex-shrink: 0;
        }
        .faq-btn.active .faq-chevron {
            transform: rotate(180deg);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s cubic-bezier(0, 1, 0, 1), padding .3s ease;
            padding: 0 20px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            background: rgba(8, 12, 16, 0.3);
        }
        .faq-answer.show {
            max-height: 300px;
            padding: 0 20px 20px 20px;
            border-top: 1px solid rgba(241, 245, 249, 0.04);
            margin-top: 4px;
            padding-top: 14px;
        }

        /* CTA BAND */
        .cta-band {
            background: linear-gradient(135deg, #182535 0%, var(--surface-2) 50%, var(--ink) 100%);
            border: 1px solid rgba(226, 94, 32, 0.3);
            border-radius: var(--radius-lg);
            padding: 64px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-band::after {
            content: ""; position: absolute; inset: 0;
            background: radial-gradient(circle at 50% 0%, var(--turf-glow), transparent 70%);
            pointer-events: none;
        }
        .cta-band h2 { font-size: clamp(28px, 4vw, 44px); color: white; position: relative; z-index: 1; }
        .cta-band p { color: var(--muted); margin: 16px auto 32px; max-width: 520px; font-size: 15px; position: relative; z-index: 1; }

        /* FOOTER */
        footer { padding: 80px 0 40px; background: #05080b; border-top: 1px solid rgba(241, 245, 249, 0.06); }
        .foot-grid { display: grid; grid-template-columns: 1.5fr repeat(3, 1fr); gap: 48px; margin-bottom: 60px; }
        @media (max-width: 860px) { .foot-grid { grid-template-columns: 1fr 1fr; } }
        .foot-grid h4 { font-family: var(--mono); font-size: 11px; text-transform: uppercase; letter-spacing: .12em; color: var(--muted-2); margin-bottom: 20px; font-weight: 700; }
        .foot-grid ul { list-style: none; }
        .foot-grid li { margin-bottom: 12px; font-size: 13px; color: var(--muted); font-weight: 500; }
        .foot-grid li a { color: var(--muted); text-decoration: none; transition: color .2s ease; }
        .foot-grid li a:hover { color: var(--line); }
        .foot-bottom { display: flex; justify-content: space-between; padding-top: 28px; border-top: 1px solid rgba(241,245,249,0.05); font-size: 12px; color: var(--muted-2); flex-wrap: wrap; gap: 16px; font-weight: 500; }
    </style>
</head>
<body>

    <!-- HEADER NAVIGATION -->
    <header>
        <div class="nav wrap">
            <a href="#" class="logo">
                <span class="dot"></span>FUTSAL<span style="color:var(--muted-2); font-family:var(--body); font-weight:400; font-size:12px; margin-left:2px;">MARE</span>
            </a>
            
            <nav class="nav-links">
                <a href="#">Beranda</a>
                <a href="#lapangan">Arena</a>
                <a href="#membership">Membership</a>
                <a href="#cara-booking">Alur Prosedur</a>
                <a href="#faq">FAQ</a>
            </nav>
            
            <div class="nav-actions">
                <a href="{{ route('admin.login') }}" style="color:var(--muted-2); font-family:var(--mono); font-size:10px; font-weight:700; text-transform:uppercase; text-decoration:none; margin-right:8px; display:flex; align-items:center; gap:4px;">
                    <span>🛡️</span> Admin Portal
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-ui btn-ui-primary btn-ui-sm">MEMBER DASHBOARD</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ui btn-ui-ghost btn-ui-sm">MASUK</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-ui btn-ui-primary btn-ui-sm">DAFTAR</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="wrap hero-grid">
            <div>
                <span class="eyebrow">⚡ Sistem Reservasi Digital Kota Baubau</span>
                <h1>MAIN FUTSAL<br>TANPA <span>RIBET</span> ANTRI</h1>
                <p class="lead">Cek ketersediaan slot kosong secara real-time, amankan jadwal bertanding tim Anda, dan lakukan pembayaran otomatis dalam hitungan detik.</p>
                
                <div class="hero-cta">
                    <a href="#lapangan" class="btn-ui btn-ui-primary">Booking Arena Sekarang &rarr;</a>
                    <a href="#membership" class="btn-ui btn-ui-ghost">Pelajari Membership</a>
                </div>
                
                <div class="stat-row">
                    <div class="stat">
                        <b>3 ARENA</b>
                        <span>Rumput Sintetis Premium</span>
                    </div>
                    <div class="stat">
                        <b>06:00 – 24:00</b>
                        <span>Jam Operasional</span>
                    </div>
                    <div class="stat">
                        <b>WITA</b>
                        <span>Zona Waktu Lokal</span>
                    </div>
                </div>
            </div>

            <!-- LIVE DASHBOARD BOARD CARD -->
            <div class="board-card">
                <div class="board-head">
                    <h3>MONITORING ARENA REAL-TIME</h3>
                    <div class="live-tag"><span class="pip"></span>LIVE</div>
                </div>
                
                <div style="font-size:14px; color:var(--muted); font-weight:500; line-height:1.7; margin-bottom:20px;">
                    Selamat datang di gerbang utama Futsal Mare. Telusuri katalog di bawah ini untuk melihat jadwal operasional, tipe permukaan lapangan, serta estimasi tarif sewa per jam secara transparan.
                </div>

                <div style="background: var(--surface-2); border: 1px solid rgba(241,245,249,0.06); padding: 16px; border-radius: var(--radius-md); font-family: var(--mono); font-size: 11px; color: var(--muted-2); display: flex; justify-content: space-between; align-items: center;">
                    <span>STATUS SISTEM:</span>
                    <span style="color: #2f9e58; font-weight: 700;">● METRIC ONLINE</span>
                </div>
            </div>
        </div>
    </section>

    <!-- COURTS CATALOG SECTION -->
    <section id="lapangan">
        <div class="wrap">
            <div class="sec-head">
                <span class="eyebrow">Fasilitas Standar Internasional</span>
                <h2>Pilihan Arena Terbaik</h2>
                <p>Setiap arena disiapkan dengan kualitas terbaik untuk menjamin kenyamanan, kestabilan pergerakan, dan keamanan pertandingan tim Anda.</p>
            </div>

            <div class="courts-grid">
                @forelse ($lapangans as $lapangan)
                    <div class="glass-card court-card">
                        <div>
                            <div class="court-media">
                                @if($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                    <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                                @elseif($lapangan->foto_lapangan)
                                    <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                                @else
                                    <div style="height:100%; display:flex; align-items:center; justify-content:center; color:var(--muted-2); font-family:var(--mono); font-size:11px;">NO IMAGE ASSET</div>
                                @endif
                                <span class="price-tag">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / Jam</span>
                            </div>

                            <div class="court-body">
                                <h3>{{ $lapangan->nama_lapangan }}</h3>
                                <div class="court-meta">
                                    <span>🌱 {{ strtoupper($lapangan->jenis_rumput) }}</span>
                                    <span>•</span>
                                    <span>LED LIGHTING</span>
                                </div>
                                <p class="court-desc">Dilengkapi dengan papan skor digital, ruang ganti eksklusif, serta pencahayaan sorot LED terarah bebas silau.</p>
                            </div>
                        </div>

                        <div style="padding: 0 24px 24px 24px;">
                            <a href="{{ route('reservasi.create', $lapangan->id) }}" class="btn-ui btn-ui-primary btn-ui-sm" style="width:100%;">Amankan Slot Waktu</a>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 3; text-align:center; padding:64px 24px; background:var(--surface); border-radius:var(--radius-lg); border: 1px dashed rgba(241,245,249,0.1); color:var(--muted); font-size:14px; font-weight:600;">
                        ⚽ Saat ini belum ada data katalog lapangan yang terdaftar dalam sistem.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- MEMBERSHIP TIER SECTION -->
    <section id="membership">
        <div class="wrap">
            <div class="sec-head">
                <span class="eyebrow">Loyalty Program</span>
                <h2>Sistem Tingkatan Member</h2>
                <p>Setiap transaksi pemesanan lapangan yang sukses memberikan tambahan <span style="color:white; font-family:var(--mono); font-weight:700;">+10 Poin Loyalty</span>. Tingkatkan kasta tim Anda untuk memperoleh benefit diskon bertingkat!</p>
            </div>

            <div class="tier-grid">
                <!-- BRONZE -->
                <div class="glass-card tier-card">
                    <h3>🥉 Tier Bronze</h3>
                    <span class="tier-points">0 Poin (Default Baru)</span>
                    <ul>
                        <li class="active">✔ Akumulasi Poin Aktif</li>
                        <li>❌ Diskon Harga Sewa</li>
                        <li>❌ Akses Prioritas Sistem</li>
                    </ul>
                </div>

                <!-- SILVER -->
                <div class="glass-card tier-card">
                    <h3>🥈 Tier Silver</h3>
                    <span class="tier-points">100 Loyalty Points</span>
                    <ul>
                        <li class="active">✔ Akumulasi Poin Aktif</li>
                        <li class="active" style="color:#2f9e58;">✔ Diskon Otomatis 5% / Match</li>
                        <li>❌ Akses Prioritas Sistem</li>
                    </ul>
                </div>

                <!-- GOLD -->
                <div class="glass-card tier-card gold">
                    <span class="tier-badge">PALING POPULER</span>
                    <h3>🏆 Tier Gold</h3>
                    <span class="tier-points" style="color:var(--floodlight)">300 Loyalty Points</span>
                    <ul>
                        <li class="active">✔ Akumulasi Poin Aktif</li>
                        <li class="active" style="color:#2f9e58;">✔ Diskon Otomatis 10% / Match</li>
                        <li class="active" style="color:var(--floodlight);">⚡ Layanan Prioritas Booking 24/7</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- STEPS SECTION -->
    <section id="cara-booking">
        <div class="wrap">
            <div class="sec-head">
                <span class="eyebrow">Alur Prosedur</span>
                <h2>Pemesanan Mudah Dalam 3 Langkah</h2>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-num">01</div>
                    <h3>Pilih Arena & Waktu</h3>
                    <p>Pilih arena favorit Anda dari katalog dan tentukan tanggal serta jam tanding sesuai ketersediaan slot waktu.</p>
                </div>

                <div class="step">
                    <div class="step-num">02</div>
                    <h3>Selesaikan Pembayaran</h3>
                    <p>Sistem terintegrasi dengan Payment Gateway Midtrans. Bayar dengan QRIS, Transfer Bank, atau E-Wallet secara instan.</p>
                </div>

                <div class="step">
                    <div class="step-num">03</div>
                    <h3>Terima Tiket & Tanding</h3>
                    <p>Sistem akan menerbitkan e-tiket resmi. Tunjukkan kode/ID reservasi kepada petugas saat tiba di lapangan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq">
        <div class="wrap">
            <div class="sec-head">
                <span class="eyebrow">Pusat Bantuan</span>
                <h2>Pertanyaan Sering Diajukan</h2>
                <p>Punya kebingungan seputar pembayaran, reschedule, e-tiket, atau loyalty point? Temukan jawaban lengkapnya di bawah ini.</p>
                
                <!-- Live Search -->
                <div class="faq-search-box">
                    <svg class="faq-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="faqSearch" placeholder="Cari pertanyaan (misal: bayar, tiket, reschedule)..." class="faq-search-input">
                </div>
            </div>

            <div id="faqList" style="max-width: 800px;">
                <!-- FAQ Item 1 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Bagaimana cara melakukan pemesanan lapangan di Futsal Mare?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Pilih arena yang Anda inginkan di katalog, klik "Amankan Slot Waktu", lalu tentukan tanggal tanding serta jam operasional yang masih terbuka. Selesaikan transaksi via Midtrans sebelum batas pembayaran expired.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Metode pembayaran apa saja yang bisa digunakan?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Sistem mendukung pembayaran otomatis melalui Midtrans yang mencakup QRIS (GoPay, DANA, OVO, ShopeePay), Transfer Bank Virtual Account (BCA, Mandiri, BNI, BRI), serta Kartu Kredit.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Apakah jadwal tanding bisa diubah (Reschedule)?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Permintaan Ubah Jadwal (Reschedule) dapat dikonfirmasikan kepada admin operasional minimal H-1 (24 Jam) sebelum jadwal pertandingan awal, selama ketersediaan slot pengganti masih ada.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Bagaimana cara melakukan check-in saat tiba di arena?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Tunjukkan e-tiket yang tersedia di Member Dashboard atau bukti transaksi reservasi lunas kepada petugas kasir/gate Futsal Mare untuk diverifikasi sebelum memasuki arena.
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Bagaimana cara menghitung dan menggunakan Loyalty Point?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Setiap kali transaksi reservasi sukses, akun Anda otomatis terisi +10 Poin. Saat mencapai 100 Poin (Silver), Anda memperoleh Diskon 5%. Jika mencapai 300 Poin (Gold), Anda memperoleh Diskon 10% otomatis yang langsung terpotong di checkout.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION (CTA) BAND -->
    <section style="border-bottom: none;">
        <div class="wrap">
            <div class="cta-band">
                <h2>SIAP AMANKAN JADWAL TANDING TIM ANDA?</h2>
                <p>Jangan sampai slot waktu favorit Anda direbut tim lain. Cek ketersediaan arena sekarang dan bertanding dengan fasilitas kualitas premium.</p>
                <a href="#lapangan" class="btn-ui btn-ui-primary" style="position: relative; z-index: 1;">
                    Cari & Reservasi Arena Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="wrap">
            <div class="foot-grid">
                <div>
                    <a href="#" class="logo" style="margin-bottom: 16px;">
                        <span class="dot"></span>FUTSAL<span style="color:var(--muted-2); font-family:var(--body); font-weight:400; font-size:12px; margin-left:2px;">MARE</span>
                    </a>
                    <p style="font-size: 13px; color: var(--muted); max-width: 300px; line-height: 1.6;">
                        Penyedia layanan arena futsal dengan rumput sintetis premium, sistem pencahayaan LED terarah, dan reservasi digital terintegrasi di Kota Baubau.
                    </p>
                </div>

                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#lapangan">Katalog Arena</a></li>
                        <li><a href="#membership">Membership Tier</a></li>
                        <li><a href="#cara-booking">Alur Prosedur</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Bantuan</h4>
                    <ul>
                        <li><a href="#faq">Pusat Informasi FAQ</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Konfirmasi Reschedule</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Lokasi & Jam</h4>
                    <ul>
                        <li>📍 Kota Baubau, Sulawesi Tenggara</li>
                        <li>⏰ 06:00 – 24:00 WITA</li>
                        <li>⚡ Sistem Online 24/7</li>
                    </ul>
                </div>
            </div>

            <div class="foot-bottom">
                <div>&copy; {{ date('Y') }} Futsal Mare Baubau. All Rights Reserved.</div>
                <div style="font-family: var(--mono); font-size: 11px;">SYSTEM STATUS: <span style="color:#2f9e58;">ONLINE</span></div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FOR FAQ TOGGLE & LIVE SEARCH -->
    <script>
        // Function untuk Toggle Accordion FAQ
        function toggleFaq(button) {
            const answer = button.nextElementSibling;
            const isExpanded = button.classList.contains('active');

            // Tutup item FAQ lainnya (accordion murni)
            document.querySelectorAll('.faq-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.nextElementSibling) {
                    btn.nextElementSibling.classList.remove('show');
                }
            });

            // Buka item jika sebelumnya tertutup
            if (!isExpanded) {
                button.classList.add('active');
                answer.classList.add('show');
            }
        }

        // Live Search untuk FAQ
        document.getElementById('faqSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>

</body>
</html>