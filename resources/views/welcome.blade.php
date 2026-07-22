<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Mare - Reservasi Lapangan Premium Kota Baubau</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        img { display: block; max-width: 100%; }
        .wrap { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        .eyebrow {
            font-family: var(--mono); font-size: 12px; letter-spacing: .14em; text-transform: uppercase;
            color: var(--turf); display: flex; align-items: center; gap: 8px; font-weight: 500;
        }
        .eyebrow::before { content: ""; width: 16px; height: 2px; background: var(--turf); display: inline-block; }
        h1, h2, h3 { font-family: var(--display); font-weight: 400; letter-spacing: .01em; text-transform: uppercase; }
        
        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 600; font-size: 15px;
            cursor: pointer; border: 1px solid transparent; transition: transform .15s ease, background .15s ease, border-color .15s ease;
            font-family: var(--body); text-transform: uppercase; letter-spacing: .03em;
        }
        .btn-ui:active { transform: scale(.97); }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-ghost { background: transparent; border-color: rgba(238, 241, 234, 0.25); color: var(--line); }
        .btn-ui-ghost:hover { border-color: var(--line); }
        .btn-ui-sm { padding: 9px 16px; font-size: 13px; border-radius: 6px; }

        /* ---------- HEADER ---------- */
        header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(238, 241, 234, 0.08);
        }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; }
        .logo { display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 24px; letter-spacing: .03em; color: white;}
        .logo .dot { width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform:rotate(45deg); }
        .nav-links { display: flex; gap: 32px; font-size: 13px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .nav-links a:hover { color: var(--line); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }

        /* ---------- HERO ---------- */
        .hero {
            position: relative; overflow: hidden;
            background: radial-gradient(ellipse 900px 500px at 85% -10%, var(--turf-glow), transparent 60%), var(--ink);
            padding: 88px 0 60px;
            border-bottom: 1px solid rgba(238, 241, 234, 0.08);
        }
        .hero-grid { display: grid; grid-template-columns: 1.05fr .95fr; gap: 56px; align-items: center; }
        @media (max-width: 920px) { .hero-grid { grid-template-columns: 1fr; } }
        .hero h1 { font-size: clamp(38px, 5.5vw, 64px); line-height: .98; margin: 18px 0 20px; }
        .hero h1 span { color: var(--turf); }
        .hero p.lead { color: var(--muted); font-size: 16px; max-width: 520px; margin-bottom: 32px; font-weight: 500; }
        .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 44px; }
        .stat-row { display: flex; gap: 36px; flex-wrap: wrap; }
        .stat b { display: block; font-family: var(--mono); font-size: 26px; color: var(--floodlight); }
        .stat span { font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; }

        /* Board Card Widget */
        .board-card {
            background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.1); border-radius: var(--radius);
            padding: 24px; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6);
        }
        .board-head { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px; }
        .board-head h3 { font-size: 14px; letter-spacing: .05em; color: var(--muted); }
        .board-head .live { font-family: var(--mono); font-size: 11px; color: var(--turf); display: flex; align-items: center; gap: 6px; font-weight: 700; }
        .live .pip { width: 7px; height: 7px; border-radius: 50%; background: var(--turf); animation: pulse 1.6s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
        
        /* ---------- COURTS ---------- */
        section { padding: 96px 0; border-bottom: 1px solid rgba(238, 241, 234, 0.08); }
        .sec-head { max-width: 600px; margin-bottom: 48px; }
        .sec-head h2 { font-size: clamp(28px, 4vw, 42px); margin-top: 14px; }
        .sec-head p { color: var(--muted); margin-top: 12px; font-size: 15px; font-weight: 500; }
        .courts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        @media (max-width: 900px) { .courts-grid { grid-template-columns: 1fr; } }
        
        .court-card {
            background: var(--surface); border-radius: var(--radius); overflow: hidden; border: 1px solid rgba(238, 241, 234, 0.08);
            transition: border-color .2s ease, transform .2s ease; display: flex; flex-col; justify-content: space-between;
        }
        .court-card:hover { border-color: var(--turf); transform: translateY(-4px); }
        .court-media { height: 180px; position: relative; background: #0B131F; overflow: hidden; }
        .court-media img { width: 100%; height: 100%; object-cover: cover; }
        .court-media .price-tag {
            position: absolute; bottom: 12px; right: 12px; background: var(--ink); border: 1px solid var(--turf);
            color: var(--turf); font-family: var(--mono); font-size: 13px; padding: 5px 12px; border-radius: 6px; font-weight: 700;
        }
        .court-body { padding: 24px; }
        .court-body h3 { font-family: var(--body); font-weight: 700; font-size: 18px; text-transform: none; margin-bottom: 6px; color: white; }
        .court-meta { display: flex; gap: 14px; font-size: 11px; color: var(--muted); margin-bottom: 14px; font-family: var(--mono); font-weight: 700; }
        .court-desc { color: var(--muted); font-size: 13px; margin-bottom: 20px; font-weight: 500; }

        /* ---------- MEMBERSHIP SYSTEM TIER BENTO ---------- */
        .tier-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        @media (max-width: 820px) { .tier-grid { grid-template-columns: 1fr; } }
        .tier-card { background: var(--surface); border-radius: var(--radius); padding: 28px; border: 1px solid rgba(238, 241, 234, 0.08); position: relative; overflow: hidden; }
        .tier-card h3 { font-size: 16px; margin-bottom: 8px; font-family: var(--body); font-weight: 700; }
        .tier-points { font-family: var(--mono); font-size: 12px; color: var(--turf); margin-bottom: 18px; display: block; font-weight: 700; }
        .tier-card ul { space-y: 12px; }
        .tier-card li { font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 8px; font-weight: 500; }
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
        .foot-bottom { display: flex; justify-content: space-between; padding-top: 24px; border-top: 1px solid rgba(238,241,234,0.05); font-size: 12px; color: var(--muted-2); flex-wrap: wrap; gap: 12px; font-weight: 500; }
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
            @endauth
        @endif
    </div>
  </div>
</header>

<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <div class="eyebrow">Sistem Informasi Reservasi Digital Kota Baubau</div>
      <h1>MAIN FUTSAL<br>TANPA <span>RIBET</span> ANTRI</h1>
      <p class="lead">Cek ketersediaan slot kosong secara real-time, amankan waktu bertanding tim kelompokmu, dan lakukan penyelesaian pembayaran instan dalam hitungan detik.</p>
      <div class="hero-cta">
        <a href="#lapangan" class="btn-ui btn-ui-primary">Booking Arena Sekarang</a>
        <a href="#membership" class="btn-ui btn-ui-ghost">Pelajari Benefit Member</a>
      </div>
      <div class="stat-row">
        <div class="stat"><b>3</b><span>Lapangan Premium</span></div>
        <div class="stat"><b>06–24</b><span>Jam Operasional</span></div>
        <div class="stat"><b>WITA</b><span>Zona Waktu Lokal</span></div>
      </div>
    </div>

    <!-- Live Board Overview Component Widget -->
    <div class="board-card">
      <div class="board-head">
        <h3>REAL-TIME DASHBOARD ARENA</h3>
        <div class="live"><span class="pip"></span>LIVE MONITORING</div>
      </div>
      <div style="font-size:13px; color:var(--muted); font-weight:600; line-height:1.7;">
        Selamat datang di gerbang utama Futsal Mare. Silakan telusuri katalog di bawah ini untuk melihat jadwal operasional, ketersediaan rumput sintetis, serta detail tarif sewa per jam secara transparan.
      </div>
      <div style="margin-top:24px; padding-top:16px; border-top:1px dashed rgba(238,241,234,0.1); font-family:var(--mono); font-size:11px; color:var(--muted-2);">
        * Silakan login ke akun member untuk melakukan booking interaktif.
      </div>
    </div>
  </div>
</section>

<!-- CATALOG MODUL ARENA SECTION -->
<section id="lapangan">
  <div class="wrap">
    <div class="sec-head">
      <div class="eyebrow">Fasilitas Kompleks</div>
      <h2>Pilihan Arena Terbaik Baubau</h2>
      <p>Setiap arena memiliki kualifikasi standardisasi tinggi demi menjaga keamanan serta kenyamanan bertanding tim Anda.</p>
    </div>
    <div class="courts-grid">
      @forelse ($lapangans as $lapangan)
          <div class="court-card">
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

            <div id="faqList" style="max-w: 800px;">
                <!-- FAQ Item 1 -->
                <div class="faq-item">
                    <button type="button" class="faq-btn" onclick="toggleFaq(this)">
                        <span>Bagaimana cara melakukan pemesanan lapangan di Futsal Mare?</span>
                        <svg class="faq-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="faq-answer">
                        Pilih arena yang Anda inginkan di katalog, klik "Amankan Slot Waktu", lalu tentukan tanggal tanding serta jam operasional yang masih terbuka. Selesaikan transaksi via Midtrans sebelum batas batas pembayaran expired.
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

                <!-- Empty State Search Result -->
                <div id="faqEmpty" style="display: none; padding: 24px; text-align: center; color: var(--muted); font-size: 14px; font-family: var(--mono);">
                    🔍 Pertanyaan tidak ditemukan. Coba gunakan kata kunci lain.
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BAND SECTION -->
    <section style="border-bottom:none;">
        <div class="wrap">
            <div class="cta-band">
                <h2>Siap Mengamankan Slot Pertandingan?</h2>
                <p>Slot waktu jam favorit (Malam & Weekend) sangat terbatas. Amankan jadwal tim Anda sekarang sebelum terisi oleh tim lain!</p>
                <a href="#lapangan" class="btn-ui btn-ui-primary" style="position:relative; z-index:1;">Booking Arena Sekarang &rarr;</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="wrap">
            <div class="foot-grid">
                <div>
                    <a href="#" class="logo" style="margin-bottom:16px;">
                        <span class="dot"></span>FUTSAL MARE
                    </a>
                    <p style="color:var(--muted); font-size:13px; max-width:280px; font-weight:500;">
                        Sistem Informasi Layanan Reservasi Penyewaan Lapangan Futsal Digital Terintegrasi Kota Baubau.
                    </p>
                </div>

                <div>
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#lapangan">Katalog Arena</a></li>
                        <li><a href="#membership">Sistem Membership</a></li>
                        <li><a href="#cara-booking">Alur Prosedur</a></li>
                        <li><a href="#faq">Pusat FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Bantuan</h4>
                    <ul>
                        <li><a href="#faq">Syarat & Ketentuan</a></li>
                        <li><a href="#faq">Kebijakan Privasi</a></li>
                        <li><a href="#faq">Pusat Pengaduan</a></li>
                    </ul>
                </div>
                <div class="court-body">
                  <h3>{{ $lapangan->nama_lapangan }}</h3>
                  <div class="court-meta"><span>🌱 {{ strtoupper($lapangan->jenis_rumput) }}</span><span>·</span><span>LED LIGHT SYSTEM</span></div>
                  <p class="court-desc">Dilengkapi dengan scoring board digital, ruang ganti eksklusif, serta pencahayaan lampu LED murni untuk match malam hari.</p>
                </div>
            </div>
            <div style="padding: 0 24px 24px 24px;">
                <a href="{{ route('reservasi.create', $lapangan->id) }}" class="btn-ui btn-ui-primary btn-ui-sm" style="width:100%;">Amankan Slot Waktu</a>
            </div>
        </div>
    </footer>

    <!-- INTERACTION SCRIPTS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Smooth Scroll with Navbar Offset
            document.querySelectorAll('.nav-links a, a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') {
                        e.preventDefault();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        const navbarHeight = 80;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
                        
                        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                    }
                });
            });

            // Live Search FAQ
            const faqSearch = document.getElementById('faqSearch');
            if (faqSearch) {
                faqSearch.addEventListener('input', function (e) {
                    const query = e.target.value.toLowerCase().trim();
                    const faqItems = document.querySelectorAll('.faq-item');
                    let visibleCount = 0;

                    faqItems.forEach(item => {
                        const question = item.querySelector('.faq-btn span').innerText.toLowerCase();
                        const answer = item.querySelector('.faq-answer').innerText.toLowerCase();

                        if (question.includes(query) || answer.includes(query)) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    const emptyState = document.getElementById('faqEmpty');
                    if (emptyState) {
                        emptyState.style.display = (visibleCount === 0 && query !== '') ? 'block' : 'none';
                    }
                });
            }
        });

        // FAQ Toggle Accordion Function
        function toggleFaq(btn) {
            const answer = btn.nextElementSibling;
            const isOpen = btn.classList.contains('active');

            // Close all open answers
            document.querySelectorAll('.faq-btn').forEach(otherBtn => {
                otherBtn.classList.remove('active');
                if (otherBtn.nextElementSibling) {
                    otherBtn.nextElementSibling.classList.remove('show');
                }
            });

            // Toggle current answer
            if (!isOpen) {
                btn.classList.add('active');
                answer.classList.add('show');
            }
        }
    </script>
</body>
</html>