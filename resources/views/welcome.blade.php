<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Mare — Reservasi Lapangan Premium Kota Baubau</title>
    <meta name="description" content="Sistem reservasi digital lapangan futsal premium di Kota Baubau. Cek slot kosong, booking, dan bayar dalam hitungan detik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #0a0e13;
            --surface: #131a22;
            --surface-2: #1a222c;
            --surface-3: #212a35;
            --turf: #e2601f;
            --turf-dark: #b8481a;
            --turf-glow: rgba(226, 96, 31, 0.22);
            --floodlight: #f5c518;
            --floodlight-glow: rgba(245, 197, 24, 0.16);
            --chalk: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --line-soft: rgba(238, 241, 234, 0.08);
            --line-soft-2: rgba(238, 241, 234, 0.14);
            --radius: 16px;
            --radius-sm: 10px;
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --ease: cubic-bezier(.22,1,.36,1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; scroll-behavior: auto !important; }
        }
        body {
            background: var(--ink);
            color: var(--chalk);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        img { display: block; max-width: 100%; }
        .wrap { max-width: 1180px; margin: 0 auto; padding: 0 24px; }
        a { color: inherit; text-decoration: none; }

        ::selection { background: var(--turf); color: var(--ink); }

        :focus-visible {
            outline: 2px solid var(--floodlight);
            outline-offset: 3px;
            border-radius: 4px;
        }

        .eyebrow {
            font-family: var(--mono); font-size: 12px; letter-spacing: .14em; text-transform: uppercase;
            color: var(--turf); display: flex; align-items: center; gap: 8px; font-weight: 500;
        }
        .eyebrow::before { content: ""; width: 16px; height: 2px; background: var(--turf); display: inline-block; }
        h1, h2, h3 { font-family: var(--display); font-weight: 400; letter-spacing: .01em; text-transform: uppercase; }

        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 15px 28px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; transition: transform .18s var(--ease), background .18s ease, border-color .18s ease, box-shadow .18s ease;
            font-family: var(--body); text-transform: uppercase; letter-spacing: .05em; white-space: nowrap;
        }
        .btn-ui:active { transform: scale(.96); }
        .btn-ui-primary { background: var(--turf); color: white; box-shadow: 0 8px 24px -8px var(--turf-glow); }
        .btn-ui-primary:hover { background: var(--turf-dark); transform: translateY(-1px); }
        .btn-ui-ghost { background: transparent; border-color: var(--line-soft-2); color: var(--chalk); }
        .btn-ui-ghost:hover { border-color: var(--chalk); background: rgba(238,241,234,0.04); }
        .btn-ui-sm { padding: 10px 18px; font-size: 12px; border-radius: 6px; }
        .btn-ui-block { width: 100%; }

        /* ---------- HEADER ---------- */
        header {
            position: sticky; top: 0; z-index: 50;
            background: rgba(10, 14, 19, 0.82); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--line-soft);
        }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; }
        .logo { display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 22px; letter-spacing: .03em; color: white; }
        .logo .dot { width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg); flex-shrink: 0; }
        .logo .sub { color: var(--muted-2); font-family: var(--body); font-weight: 700; font-size: 11px; margin-left: 2px; letter-spacing: .08em; }
        .nav-links { display: flex; gap: 30px; font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
        .nav-links a { transition: color .15s ease; }
        .nav-links a:hover { color: var(--chalk); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .admin-port { color: var(--muted-2); font-family: var(--mono); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; transition: color .15s ease; }
        .admin-port:hover { color: var(--floodlight); }
        @media (max-width: 860px) { .nav-links { display: none; } }

        /* ---------- HERO ---------- */
        .hero {
            position: relative; overflow: hidden;
            background:
                radial-gradient(ellipse 900px 480px at 88% -8%, var(--turf-glow), transparent 62%),
                radial-gradient(ellipse 620px 420px at 6% 105%, var(--floodlight-glow), transparent 60%),
                var(--ink);
            padding: 76px 0 64px;
            border-bottom: 1px solid var(--line-soft);
        }
        .pitch-lines {
            position: absolute; inset: 0; z-index: 0; opacity: .5; pointer-events: none;
        }
        .hero-grid { position: relative; z-index: 1; display: grid; grid-template-columns: 1.05fr .95fr; gap: 56px; align-items: center; }
        @media (max-width: 920px) { .hero-grid { grid-template-columns: 1fr; } }

        .hero h1 { font-size: clamp(38px, 5.4vw, 62px); line-height: .96; margin: 18px 0 20px; }
        .hero h1 span { color: var(--turf); }
        .hero p.lead { color: var(--muted); font-size: 16px; max-width: 500px; margin-bottom: 32px; font-weight: 500; }
        .hero-cta { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 44px; }
        .stat-row { display: flex; gap: 34px; flex-wrap: wrap; }
        .stat b { display: block; font-family: var(--mono); font-size: 25px; color: var(--floodlight); }
        .stat span { font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; }

        /* ---------- HERO BANNER (FOTO PNG) ---------- */
        .hero-visual { display: flex; flex-direction: column; gap: 20px; }
        .hero-banner {
            position: relative; border-radius: var(--radius); overflow: hidden;
            border: 1px solid var(--line-soft-2); box-shadow: 0 26px 60px -22px rgba(0,0,0,0.6);
            background: linear-gradient(180deg, #101a26 0%, var(--ink) 100%);
            aspect-ratio: 4 / 3;
        }
        .hero-banner img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        /* Gradient tipis di bawah foto supaya chip informasi tetap terbaca di atas foto apa pun */
        .hero-banner::after {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(180deg, rgba(10,14,19,0) 45%, rgba(10,14,19,0.62) 100%);
        }
        /* Fallback rapi kalau file gambar belum diunggah ke public/images/ */
        .hero-banner-fallback {
            width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 10px; text-align: center; padding: 24px;
            background: radial-gradient(ellipse 70% 60% at 50% 30%, rgba(226,96,31,0.14), transparent 65%);
        }
        .hero-banner-fallback .glyph { font-size: 34px; opacity: .9; }
        .hero-banner-fallback p { font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; max-width: 260px; line-height: 1.6; }
        .hero-banner-fallback code { color: var(--floodlight); font-weight: 700; }

        .float-chip {
            position: absolute; z-index: 2; display: flex; align-items: center; gap: 10px;
            background: rgba(19, 26, 34, 0.85); backdrop-filter: blur(8px);
            border: 1px solid var(--line-soft-2); border-radius: 12px; padding: 10px 14px;
            box-shadow: 0 14px 30px -12px rgba(0,0,0,0.6); animation: float-y 4.5s ease-in-out infinite;
        }
        .float-chip .ico { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .float-chip .ttl { font-size: 11px; font-weight: 700; color: var(--chalk); line-height: 1.3; }
        .float-chip .sub { font-size: 10px; color: var(--muted-2); font-family: var(--mono); }
        .chip-status { top: 16px; left: 16px; animation-delay: 0s; }
        .chip-status .ico { background: rgba(47,158,88,0.18); color: #2f9e58; }
        .chip-price { bottom: 16px; right: 16px; animation-delay: .6s; }
        .chip-price .ico { background: var(--floodlight-glow); color: var(--floodlight); }
        @keyframes float-y { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        @media (max-width: 480px) { .float-chip { padding: 8px 10px; } .float-chip .ttl { font-size: 10px; } }
        @media (prefers-reduced-motion: reduce) { .float-chip { animation: none; } }

        /* Signature widget: digital e-ticket stub (elemen kedua, di bawah banner) */
        .ticket {
            position: relative; background: var(--surface);
            border-radius: var(--radius); box-shadow: 0 24px 55px -22px rgba(0,0,0,0.6);
            border: 1px solid var(--line-soft-2);
        }
        .ticket-top { padding: 22px 24px 18px; }
        .ticket-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .ticket-head span.tag { font-family: var(--mono); font-size: 11px; letter-spacing: .1em; color: var(--muted); text-transform: uppercase; }
        .ticket-head .live { font-family: var(--mono); font-size: 11px; color: var(--turf); display: flex; align-items: center; gap: 6px; font-weight: 700; }
        .live .pip { width: 7px; height: 7px; border-radius: 50%; background: var(--turf); animation: pulse 1.6s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .25; } }
        @media (prefers-reduced-motion: reduce) { .live .pip { animation: none; } }

        .ticket-title { font-family: var(--display); font-size: 22px; color: white; margin-bottom: 4px; }
        .ticket-sub { font-size: 12.5px; color: var(--muted); font-weight: 500; margin-bottom: 20px; }

        .ticket-rows { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 4px; }
        .ticket-rows .cell span.k { display: block; font-family: var(--mono); font-size: 10px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
        .ticket-rows .cell span.v { font-size: 13px; font-weight: 700; color: var(--chalk); }

        .ticket-perf {
            position: relative; height: 0; border-top: 1.5px dashed var(--line-soft-2); margin: 20px 0 0;
        }
        .ticket-perf::before, .ticket-perf::after {
            content: ""; position: absolute; top: -10px; width: 20px; height: 20px; background: var(--ink); border-radius: 50%;
        }
        .ticket-perf::before { left: -30px; }
        .ticket-perf::after { right: -30px; }

        .ticket-bottom { padding: 16px 24px 20px; display: flex; justify-content: space-between; align-items: center; }
        .ticket-barcode { display: flex; gap: 3px; align-items: flex-end; height: 24px; }
        .ticket-barcode i { display: block; width: 3px; background: var(--muted-2); border-radius: 1px; opacity: .8; }
        .ticket-id { font-family: var(--mono); font-size: 11px; color: var(--muted); letter-spacing: .05em; }

        /* ---------- SECTIONS ---------- */
        section { padding: 92px 0; border-bottom: 1px solid var(--line-soft); }
        .sec-head { max-width: 600px; margin-bottom: 48px; }
        .sec-head h2 { font-size: clamp(28px, 3.8vw, 42px); margin-top: 14px; }
        .sec-head p { color: var(--muted); margin-top: 12px; font-size: 15px; font-weight: 500; }

        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .6s var(--ease), transform .6s var(--ease); }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        /* ---------- COURT CATALOG ---------- */
        .courts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        @media (max-width: 900px) { .courts-grid { grid-template-columns: 1fr; } }

        .court-card {
            background: var(--surface); border-radius: var(--radius); overflow: hidden; border: 1px solid var(--line-soft);
            transition: border-color .25s ease, transform .25s var(--ease), box-shadow .25s ease;
            display: flex; flex-direction: column; justify-content: space-between;
        }
        .court-card:hover { border-color: var(--turf); transform: translateY(-5px); box-shadow: 0 18px 40px -20px var(--turf-glow); }
        .court-media { height: 180px; position: relative; background: #0b131c; overflow: hidden; }
        .court-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s var(--ease); }
        .court-card:hover .court-media img { transform: scale(1.05); }
        .court-media .price-tag {
            position: absolute; bottom: 12px; right: 12px; background: rgba(10,14,19,.9); border: 1px solid var(--turf);
            color: var(--turf); font-family: var(--mono); font-size: 13px; padding: 5px 12px; border-radius: 6px; font-weight: 700;
        }
        .court-media .empty-state {
            height: 100%; display: flex; align-items: center; justify-content: center; color: var(--muted-2);
            font-family: var(--mono); font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
        }
        .court-body { padding: 24px; }
        .court-body h3 { font-family: var(--body); font-weight: 700; font-size: 18px; text-transform: none; margin-bottom: 6px; color: white; }
        .court-meta { display: flex; gap: 14px; font-size: 11px; color: var(--muted); margin-bottom: 14px; font-family: var(--mono); font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .court-desc { color: var(--muted); font-size: 13px; margin-bottom: 4px; font-weight: 500; }
        .court-cta { padding: 0 24px 24px; }

        .empty-catalog {
            grid-column: 1 / -1; text-align: center; padding: 56px 24px; background: var(--surface);
            border-radius: var(--radius); color: var(--muted); font-size: 14px; font-weight: 600; border: 1px dashed var(--line-soft-2);
        }
        .empty-catalog .glyph { font-size: 28px; margin-bottom: 12px; }

        /* ---------- MEMBERSHIP TIER BENTO ---------- */
        .tier-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        @media (max-width: 820px) { .tier-grid { grid-template-columns: 1fr; } }
        .tier-card {
            background: var(--surface); border-radius: var(--radius); padding: 28px;
            border: 1px solid var(--line-soft); position: relative; overflow: hidden; transition: border-color .25s ease, transform .25s var(--ease);
        }
        .tier-card:hover { transform: translateY(-4px); }
        .tier-card.is-featured { border-color: var(--turf); }
        .tier-card.is-featured::before {
            content: "PALING DIMINATI"; position: absolute; top: 14px; right: -34px; transform: rotate(38deg);
            background: var(--turf); color: white; font-family: var(--mono); font-size: 9px; font-weight: 700;
            letter-spacing: .08em; padding: 4px 40px;
        }
        .tier-card h3 { font-size: 17px; margin-bottom: 8px; font-family: var(--body); font-weight: 800; text-transform: none; color: white; }
        .tier-points { font-family: var(--mono); font-size: 12px; color: var(--turf); margin-bottom: 20px; display: block; font-weight: 700; }
        .tier-card ul { display: flex; flex-direction: column; gap: 12px; }
        .tier-card li { font-size: 13px; color: var(--muted-2); display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .tier-card li.active { color: var(--chalk); font-weight: 600; }
        .tier-card li .mk { flex-shrink: 0; width: 16px; text-align: center; }

        /* ---------- STEPS ---------- */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; position: relative; }
        @media (max-width: 820px) { .steps { grid-template-columns: 1fr; } }
        .step { position: relative; padding-top: 8px; }
        .step .num {
            font-family: var(--mono); font-size: 13px; color: var(--floodlight); border: 1px solid var(--floodlight);
            width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px; font-weight: 700; background: var(--floodlight-glow);
        }
        .step h3 { font-family: var(--body); font-weight: 700; text-transform: none; font-size: 18px; margin-bottom: 8px; color: white; }
        .step p { color: var(--muted); font-size: 13px; font-weight: 500; }

        /* ---------- CTA BAND ---------- */
        .cta-band {
            position: relative; overflow: hidden;
            background: linear-gradient(120deg, var(--turf-dark), var(--turf));
            border-radius: var(--radius); padding: 60px 40px; text-align: center;
        }
        .cta-band h2 { color: var(--ink); font-size: clamp(26px, 4vw, 40px); position: relative; z-index: 1; }
        .cta-band p { color: rgba(10,14,19,0.78); margin: 14px 0 28px; font-size: 15px; font-weight: 600; position: relative; z-index: 1; }
        .cta-band .btn-ui-primary { background: var(--ink); color: white; box-shadow: none; position: relative; z-index: 1; }
        .cta-band .btn-ui-primary:hover { background: #17232f; }

        /* ---------- FOOTER ---------- */
        footer { padding: 76px 0 36px; background: #070a0f; }
        .foot-grid { display: grid; grid-template-columns: 1.4fr repeat(3, 1fr); gap: 40px; margin-bottom: 48px; }
        @media (max-width: 820px) { .foot-grid { grid-template-columns: 1fr 1fr; } }
        .foot-grid h4 { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: var(--muted-2); margin-bottom: 16px; font-weight: 700; }
        .foot-grid ul { list-style: none; }
        .foot-grid li { margin-bottom: 10px; font-size: 13px; color: var(--muted); font-weight: 500; }
        .foot-grid li a { transition: color .15s ease; }
        .foot-grid li a:hover { color: var(--chalk); }
        .foot-bottom { display: flex; justify-content: space-between; padding-top: 24px; border-top: 1px solid var(--line-soft); font-size: 12px; color: var(--muted-2); flex-wrap: wrap; gap: 12px; font-weight: 500; }
    </style>
</head>
<body>

<header>
  <div class="nav wrap">
    <div class="logo"><span class="dot"></span>FUTSAL<span class="sub">MARE</span></div>
    <nav class="nav-links">
      <a href="#" class="nav-link">Beranda</a>
      <a href="#lapangan" class="nav-link">Arena</a>
      <a href="#membership" class="nav-link">Membership</a>
      <a href="#cara-booking" class="nav-link">Alur Prosedur</a>
    </nav>
    <div class="nav-actions">
        <a href="{{ route('admin.login') }}" class="admin-port">🛡️ Admin</a>

        @if (Route::has('login'))
            @auth
                <a href="{{ route('dashboard') }}" class="btn-ui btn-ui-primary btn-ui-sm">Dashboard Member</a>
            @else
                <a href="{{ route('login') }}" class="btn-ui btn-ui-ghost btn-ui-sm">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-ui btn-ui-primary btn-ui-sm">Daftar</a>
                @endif
            @endauth
        @endif
    </div>
  </div>
</header>

<section class="hero">
  <svg class="pitch-lines" viewBox="0 0 1180 520" preserveAspectRatio="none" aria-hidden="true">
    <rect x="700" y="40" width="440" height="440" rx="4" fill="none" stroke="#eef1ea" stroke-opacity="0.06" stroke-width="2"/>
    <circle cx="920" cy="260" r="70" fill="none" stroke="#eef1ea" stroke-opacity="0.06" stroke-width="2"/>
    <line x1="920" y1="40" x2="920" y2="480" stroke="#eef1ea" stroke-opacity="0.06" stroke-width="2"/>
    <rect x="700" y="170" width="60" height="180" fill="none" stroke="#eef1ea" stroke-opacity="0.06" stroke-width="2"/>
    <rect x="1080" y="170" width="60" height="180" fill="none" stroke="#eef1ea" stroke-opacity="0.06" stroke-width="2"/>
  </svg>
  <div class="wrap hero-grid">
    <div>
      <div class="eyebrow">Sistem Informasi Reservasi Digital Kota Baubau</div>
      <h1>MAIN FUTSAL<br>TANPA <span>RIBET</span> ANTRI</h1>
      <p class="lead">Cek ketersediaan slot kosong secara real-time, amankan waktu bertanding tim kelompokmu, dan selesaikan pembayaran instan dalam hitungan detik.</p>
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

    <!-- KOLOM VISUAL: hero banner foto PNG + widget tiket digital -->
    <div class="hero-visual">

      <!-- ============ HERO BANNER: FOTO ARENA (PNG) ============ -->
      {{--
          PENTING: letakkan file gambar di public/images/hero-banner.png
          (bukan di storage/), supaya bisa diakses langsung lewat asset().
          Rasio disarankan 4:3 (mis. 1200x900px), fokus subjek di tengah/atas
          karena bagian bawah foto tertutup gradient tipis untuk keterbacaan chip.
      --}}
      <div class="hero-banner">
        @if(file_exists(public_path('images/hero-banner.png')))
            <img src="{{ asset('images/hero-banner.png') }}" alt="Suasana malam hari lapangan futsal Futsal Mare dengan lampu sorot menyala">
        @else
            <div class="hero-banner-fallback">
                <span class="glyph">🏟️</span>
                <p>Letakkan foto arena di<br><code>public/images/hero-banner.png</code></p>
            </div>
        @endif

        <span class="float-chip chip-status">
          <span class="ico">✔</span>
          <span>
            <span class="ttl">Slot Malam Tersedia</span><br>
            <span class="sub">3 arena · live</span>
          </span>
        </span>
        <span class="float-chip chip-price">
          <span class="ico">💳</span>
          <span>
            <span class="ttl">Bayar via Midtrans</span><br>
            <span class="sub">aman &amp; instan</span>
          </span>
        </span>
      </div>

      <!-- Widget tiket digital (elemen kedua) -->
      <div class="ticket">
        <div class="ticket-top">
          <div class="ticket-head">
            <span class="tag">E-Tiket Pratinjau</span>
            <div class="live"><span class="pip"></span>Live Monitoring</div>
          </div>
          <div class="ticket-title">Malam Ini, Arena Siap.</div>
          <div class="ticket-sub">Login ke akun member untuk melihat slot kosong &amp; booking langsung.</div>
          <div class="ticket-rows">
            <div class="cell"><span class="k">Lapangan</span><span class="v">Rumput Sintetis</span></div>
            <div class="cell"><span class="k">Pencahayaan</span><span class="v">Full LED Night</span></div>
            <div class="cell"><span class="k">Durasi</span><span class="v">Per 60 Menit</span></div>
            <div class="cell"><span class="k">Pembayaran</span><span class="v">Midtrans Gateway</span></div>
          </div>
        </div>
        <div class="ticket-perf"></div>
        <div class="ticket-bottom">
          <div class="ticket-barcode" aria-hidden="true">
            <i style="height:70%"></i><i style="height:100%"></i><i style="height:50%"></i><i style="height:85%"></i>
            <i style="height:60%"></i><i style="height:95%"></i><i style="height:40%"></i><i style="height:75%"></i>
            <i style="height:55%"></i><i style="height:90%"></i><i style="height:65%"></i><i style="height:100%"></i>
          </div>
          <span class="ticket-id">FM · BAUBAU</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CATALOG ARENA -->
<section id="lapangan">
  <div class="wrap">
    <div class="sec-head reveal">
      <div class="eyebrow">Fasilitas Kompleks</div>
      <h2>Pilihan Arena Terbaik Baubau</h2>
      <p>Setiap arena memiliki kualifikasi standardisasi tinggi demi menjaga keamanan serta kenyamanan bertanding tim Anda.</p>
    </div>
    <div class="courts-grid">
      @forelse ($lapangans as $lapangan)
          <div class="court-card reveal">
            <div>
                <div class="court-media">
                    @if($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                        <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                    @elseif($lapangan->foto_lapangan)
                        <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                    @else
                        <div class="empty-state">Foto Belum Tersedia</div>
                    @endif
                    <span class="price-tag">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / Jam</span>
                </div>
                <div class="court-body">
                  <h3>{{ $lapangan->nama_lapangan }}</h3>
                  <div class="court-meta"><span>🌱 {{ strtoupper($lapangan->jenis_rumput) }}</span><span>·</span><span>LED Light System</span></div>
                  <p class="court-desc">Dilengkapi scoring board digital, ruang ganti eksklusif, serta pencahayaan LED murni untuk match malam hari.</p>
                </div>
            </div>
            <div class="court-cta">
                <a href="{{ route('reservasi.create', $lapangan->id) }}" class="btn-ui btn-ui-primary btn-ui-sm btn-ui-block">Amankan Slot Waktu</a>
            </div>
          </div>
      @empty
          <div class="empty-catalog reveal">
              <div class="glyph">⚽</div>
              Saat ini belum ada data katalog lapangan yang terdaftar dalam sistem data master.
          </div>
      @endforelse
    </div>
  </div>
</section>

<!-- MEMBERSHIP TIER SYSTEM -->
<section id="membership">
    <div class="wrap">
      <div class="sec-head reveal">
        <div class="eyebrow">Loyalty Program</div>
        <h2>Sistem Kasta Loyalitas Member</h2>
        <p>Setiap transaksi pemesanan lapangan sukses bernilai akumulasi murni <span style="color:white; font-family:var(--mono); font-weight:700;">+10 Poin</span>. Tingkatkan kasta akun kelompok Anda!</p>
      </div>
      <div class="tier-grid">
          <div class="tier-card reveal">
              <h3>🥉 Tier Bronze</h3>
              <span class="tier-points">0 Poin (Default)</span>
              <ul>
                  <li class="active"><span class="mk">✔</span> Akumulasi Poin Aktif</li>
                  <li><span class="mk">✕</span> Tanpa Potongan Diskon Harga</li>
                  <li><span class="mk">✕</span> Tanpa Hak Akses Prioritas</li>
              </ul>
          </div>
          <div class="tier-card reveal">
              <h3>🥈 Tier Silver</h3>
              <span class="tier-points">100 Loyalty Points</span>
              <ul>
                  <li class="active"><span class="mk">✔</span> Akumulasi Poin Aktif</li>
                  <li class="active" style="color:#2f9e58;"><span class="mk">✔</span> Diskon Otomatis 5% / Match</li>
                  <li><span class="mk">✕</span> Tanpa Hak Akses Prioritas</li>
              </ul>
          </div>
          <div class="tier-card is-featured reveal">
              <h3>🏆 Tier Gold</h3>
              <span class="tier-points" style="color:var(--floodlight)">300 Loyalty Points</span>
              <ul>
                  <li class="active"><span class="mk">✔</span> Akumulasi Poin Aktif</li>
                  <li class="active" style="color:#2f9e58;"><span class="mk">✔</span> Diskon Otomatis 10% / Match</li>
                  <li class="active" style="color:var(--floodlight);"><span class="mk">⚡</span> Akses Sistem Prioritas Booking 24/7</li>
              </ul>
          </div>
      </div>
    </div>
</section>

<!-- ALUR PROSEDUR -->
<section id="cara-booking">
  <div class="wrap">
    <div class="sec-head reveal">
      <div class="eyebrow">Alur Prosedur</div>
      <h2>Pemesanan Selesai Dalam 3 Langkah</h2>
    </div>
    <div class="steps">
      <div class="step reveal">
        <div class="num">1</div>
        <h3>Registrasi &amp; Pilih Lapangan</h3>
        <p>Daftarkan akun tim Anda, kemudian pilih salah satu arena lapangan premium dari papan katalog data master kami.</p>
      </div>
      <div class="step reveal">
        <div class="num">2</div>
        <h3>Isi Data &amp; Transaksi</h3>
        <p>Tentukan tanggal pertandingan, tentukan jam main, lalu selesaikan tagihan melalui integrasi Midtrans Payment Gateway.</p>
      </div>
      <div class="step reveal">
        <div class="num">3</div>
        <h3>Terima Tiket &amp; Main</h3>
        <p>Sistem akan menerbitkan e-tiket transaksi secara real-time. Tunjukkan barcode/ID tiket kepada petugas administrasi di lapangan.</p>
      </div>
    </div>
  </div>
</section>

<!-- ACTION PROMPT BAND -->
<section style="border-bottom:none;">
  <div class="wrap">
    <div class="cta-band reveal">
      <h2>Amankan Slot Jadwal Tim Anda</h2>
      <p>Slot waktu malam hari sangat terbatas. Daftarkan grup kelompokmu sekarang sebelum kehabisan.</p>
      <a href="#lapangan" class="btn-ui btn-ui-primary">Booking Sekarang</a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="logo" style="margin-bottom:14px;"><span class="dot"></span>FUTSAL<span class="sub">MARE</span></div>
        <p style="color:var(--muted); font-size:13px; max-width:260px; font-weight:500;">Sistem informasi layanan reservasi penyewaan lapangan futsal digital terintegrasi Kota Baubau.</p>
      </div>
      <ul>
        <h4>Navigasi Internal</h4>
        <li><a href="#lapangan">Katalog Arena</a></li>
        <li><a href="#membership">Sistem Membership</a></li>
        <li><a href="#cara-booking">Alur Prosedur</a></li>
      </ul>
      <ul>
        <h4>Pranala Bantuan</h4>
        <li><a href="#">Syarat &amp; Ketentuan</a></li>
        <li><a href="#">Kebijakan Privasi</a></li>
        <li><a href="#">Pusat Pengaduan</a></li>
      </ul>
      <ul>
        <h4>Informasi Kontak</h4>
        <li>Kota Baubau, Sulawesi Tenggara</li>
        <li>+62 812-3456-7890</li>
        <li>support@futsalmare.id</li>
      </ul>
    </div>
    <div class="foot-bottom">
      <span>&copy; {{ date('Y') }} Futsal Mare. Seluruh hak cipta dilindungi undang-undang.</span>
      <span>Dikembangkan sebagai proyek tugas mata kuliah Web Programming.</span>
    </div>
  </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Smooth scroll compensated for sticky header
        document.querySelectorAll('.nav-link, a[href^="#"]').forEach(function (anchor) {
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

        // Scroll-reveal for sections, cards, and steps
        const revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window && revealEls.length) {
            const io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            revealEls.forEach(function (el) { io.observe(el); });
        } else {
            revealEls.forEach(function (el) { el.classList.add('is-visible'); });
        }
    });
</script>
</body>
</html>