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

        /* ---------- STEPS ---------- */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
        @media (max-width: 820px) { .steps { grid-template-columns: 1fr; } }
        .step { position: relative; padding-top: 8px; }
        .step .num { font-family: var(--mono); font-size: 13px; color: var(--floodlight); border: 1px solid var(--floodlight); width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; font-weight: 700; }
        .step h3 { font-family: var(--body); font-weight: 700; text-transform: none; font-size: 18px; margin-bottom: 8px; color: white; }
        .step p { color: var(--muted); font-size: 13px; font-weight: 500; }

        /* ---------- CTA BAND ---------- */
        .cta-band { background: linear-gradient(120deg, var(--turf-dark), var(--turf)); border-radius: var(--radius); padding: 56px; text-align: center; }
        .cta-band h2 { color: var(--ink); font-size: clamp(26px, 4vw, 40px); }
        .cta-band p { color: rgba(10,15,20,0.8); margin: 14px 0 28px; font-size: 15px; font-weight: 600; }
        .cta-band .btn-ui-primary { background: var(--ink); color: white; }
        .cta-band .btn-ui-primary:hover { background: #17232f; }

        /* ---------- FOOTER ---------- */
        footer { padding: 80px 0 40px; background: #070c12; }
        .foot-grid { display: grid; grid-template-columns: 1.4fr repeat(3, 1fr); gap: 40px; margin-bottom: 48px; }
        @media (max-width: 820px) { .foot-grid { grid-template-columns: 1fr 1fr; } }
        .foot-grid h4 { font-size: 12px; text-transform: uppercase; letter-spacing: .08em; color: var(--muted-2); margin-bottom: 16px; font-weight: 700; }
        .foot-grid li { margin-bottom: 10px; font-size: 13px; color: var(--muted); font-weight: 500; }
        .foot-grid li a:hover { color: var(--line); }
        .foot-bottom { display: flex; justify-content: space-between; padding-top: 24px; border-top: 1px solid rgba(238,241,234,0.05); font-size: 12px; color: var(--muted-2); flex-wrap: wrap; gap: 12px; font-weight: 500; }
    </style>
</head>
<body>

<header>
  <div class="nav wrap">
    <div class="logo"><span class="dot"></span>FUTSAL<span style="color:var(--muted-2); font-family:var(--body); font-weight:400; font-size:12px; margin-left:4px;">MARE</span></div>
    <nav class="nav-links">
      <a href="#" class="nav-link">Beranda</a>
      <a href="#lapangan" class="nav-link">Arena</a>
      <a href="#membership" class="nav-link">Membership</a>
      <a href="#cara-booking" class="nav-link">Alur Prosedur</a>
    </nav>
    <div class="nav-actions">
        <!-- 🛡️ PORTAL ACCESS PORT -->
        <a href="{{ route('admin.login') }}" style="color:var(--muted-2); font-family:var(--mono); font-size:10px; font-weight:700; text-transform:uppercase; margin-right:8px;">🛡️ Admin</a>

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
                <div class="court-media">
                    @if($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                        <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                    @elseif($lapangan->foto_lapangan)
                        <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                    @else
                        <div style="height:100%; display:flex; align-items:center; justify-content:center; color:var(--muted-2); font-family:var(--mono); font-size:11px; text-transform:uppercase;">No Image Asset</div>
                    @endif
                    <span class="price-tag">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / Jam</span>
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
      @empty
          <div style="grid-column: span 3; text-align:center; padding:48px; background:var(--surface); border-radius:var(--radius); color:var(--muted); font-size:14px; font-weight:600;">
              ⚽ Saat ini belum ada data katalog lapangan yang terdaftar dalam sistem data master.
          </div>
      @endforelse
    </div>
  </div>
</section>

<!-- MEMBERSHIP TIER SYSTEM BENTO -->
<section id="membership">
    <div class="wrap">
      <div class="sec-head">
        <div class="eyebrow">Loyalty Program</div>
        <h2>Sistem Kasta Loyalitas Member</h2>
        <p>Setiap transaksi pemesanan lapangan sukses bernilai akumulasi murni <span style="color:white; font-family:var(--mono); font-weight:700;">+10 Poin</span>. Tingkatkan kasta akun kelompok Anda!</p>
      </div>
      <div class="tier-grid">
          <div class="tier-card">
              <h3>🥉 Tier Bronze</h3>
              <span class="tier-points">0 Poin (Default)</span>
              <ul>
                  <li class="active">✔ Akumulasi Poin Aktif</li>
                  <li>❌ Tanpa Potongan Diskon Harga</li>
                  <li>❌ Tanpa Hak Akses Prioritas</li>
              </ul>
          </div>
          <div class="tier-card">
              <h3>🥈 Tier Silver</h3>
              <span class="tier-points">100 Loyalty Points</span>
              <ul>
                  <li class="active">✔ Akumulasi Poin Aktif</li>
                  <li class="active" style="color:#2f9e58;">✔ Diskon Otomatis 5% / Match</li>
                  <li>❌ Tanpa Hak Akses Prioritas</li>
              </ul>
          </div>
          <div class="tier-card" style="border-color: var(--turf);">
              <h3>🏆 Tier Gold</h3>
              <span class="tier-points" style="color:var(--floodlight)">300 Loyalty Points</span>
              <ul>
                  <li class="active">✔ Akumulasi Poin Aktif</li>
                  <li class="active" style="color:#2f9e58;">✔ Diskon Otomatis 10% / Match</li>
                  <li class="active" style="color:var(--floodlight);">⚡ Akses Sistem Prioritas Booking 24/7</li>
              </ul>
          </div>
      </div>
    </div>
</section>

<!-- STEPS PROCEDURE -->
<section id="cara-booking">
  <div class="wrap">
    <div class="sec-head">
      <div class="eyebrow">Alur Prosedur</div>
      <h2>Pemesanan Selesai Dalam 3 Langkah</h2>
    </div>
    <div class="steps">
      <div class="step">
        <div class="num">1</div>
        <h3>Registrasi & Pilih Lapangan</h3>
        <p>Daftarkan akun tim Anda, kemudian pilih salah satu arena lapangan premium dari papan katalog data master kami.</p>
      </div>
      <div class="step">
        <div class="num">2</div>
        <h3>Isi Data & Transaksi</h3>
        <p>Tentukan tanggal pertandingan, tentukan jam main, lalu selesaikan tagihan melalui integrasi Midtrans Payment Gateway.</p>
      </div>
      <div class="step">
        <div class="num">3</div>
        <h3>Terima Tiket & Main</h3>
        <p>Sistem akan menerbitkan e-tiket transaksi secara real-time. Tunjukkan barcode/ID tiket kepada petugas administrasi di lapangan.</p>
      </div>
    </div>
  </div>
</section>

<!-- ACTION PROMPT BAND -->
<section style="border-bottom:none;">
  <div class="wrap">
    <div class="cta-band">
      <h2>Amankan Slot Jadwal Tim Anda</h2>
      <p>Slot waktu malam hari sangat terbatas. Daftarkan grup kelompokmu sekarang sebelum kehabisan.</p>
      <a href="#lapangan" class="btn-ui btn-ui-primary">Booking Sekarang</a>
    </div>
  </div>
</section>

<!-- FOOTER CONTEXT -->
<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div>
        <div class="logo" style="margin-bottom:14px;"><span class="dot"></span>FUTSAL MARE</div>
        <p style="color:var(--muted); font-size:13px; max-width:260px; font-weight:500;">Sistem Informasi Layanan Reservasi Penyewaan Lapangan Futsal Digital Terintegrasi Kota Baubau.</p>
      </div>
      <ul>
        <h4>Navigasi Internal</h4>
        <li><a href="#lapangan">Katalog Arena</a></li>
        <li><a href="#membership">Sistem Membership</a></li>
        <li><a href="#cara-booking">Alur Prosedur</a></li>
      </ul>
      <ul>
        <h4>Pranala Bantuan</h4>
        <li><a href="#">Syarat & Ketentuan</a></li>
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
      <span>&copy; {{ date('Y') }} Futsal Mare. Seluruh Hak Cipta Dilindungi Undang-Undang.</span>
      <span>Dikembangkan Sebagai Proyek Tugas Mata Kuliah Web Programming.</span>
    </div>
  </div>
</footer>

<!-- SMOOTH SCROLL COMPENSATED ENGINE -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.nav-link, a[href^="#"]').forEach(anchor => {
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
                    const navbarHeight = 80; // Mengimbangi sticky header
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
                    
                    window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                }
            });
        });
    });
</script>
</body>
</html>