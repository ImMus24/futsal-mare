<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail {{ $lapangan->nama_lapangan }} - Futsal Mare</title>
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

        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        
        h1, h2, h3 { 
            font-family: var(--display); 
            font-weight: 400; 
            letter-spacing: .01em; 
            text-transform: uppercase; 
        }

        .wrap { max-width: 1180px; margin: 0 auto; padding: 40px 24px; }
        
        .breadcrumb-brutal { 
            font-family: var(--mono); 
            font-size: 12px; 
            color: var(--muted-2); 
            margin-bottom: 20px; 
            text-transform: uppercase; 
        }
        .breadcrumb-brutal a:hover { color: var(--line); }
        
        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; letter-spacing: .05em; width: 100%; transition: all 0.15s ease;
        }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }

        .detail-hero-brutal {
            height: 320px; border-radius: var(--radius); margin-bottom: 32px; position: relative; overflow: hidden;
            background: repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(238,241,234,.05) 40px, rgba(238,241,234,.05) 42px), linear-gradient(160deg, var(--turf-dark), #0f3320);
            border: 1px solid rgba(238, 241, 234, 0.08);
        }
        .detail-hero-brutal img { width: 100%; height: 100%; object-fit: cover; opacity: 0.85; filter: brightness(90%); }
        .detail-hero-brutal::after { content: ""; position: absolute; inset: 18px; border: 2px solid rgba(238, 241, 234, 0.25); border-radius: 8px; pointer-events: none; }
        
        .detail-grid-brutal { display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; align-items: start; }
        @media (max-width: 900px) { .detail-grid-brutal { grid-template-columns: 1fr; } }
        
        .detail-grid-brutal h1 { font-size: 42px; margin-bottom: 10px; line-height: 1; }
        
        .eyebrow-brutal {
            font-family: var(--mono); font-size: 11px; letter-spacing: .14em; text-transform: uppercase; color: var(--turf); 
            display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 8px;
        }
        .eyebrow-brutal::before { content: ""; width: 14px; height: 2px; background: var(--turf); display: inline-block; }
        
        .detail-meta-brutal { display: flex; gap: 16px; font-family: var(--mono); font-size: 11px; color: var(--muted); margin-bottom: 24px; font-weight: 700; }
        .detail-desc-brutal { color: var(--muted); font-size: 15px; margin-bottom: 28px; max-width: 540px; font-weight: 500; line-height: 1.6; }
        
        .facility-list-brutal { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 32px; }
        .facility-list-brutal li { font-size: 13px; color: var(--line); display: flex; align-items: center; gap: 8px; font-weight: 600; }
        .facility-list-brutal li::before { content: "✓"; color: var(--turf); font-weight: 900; }
        
        .price-table-brutal { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .price-table-brutal td { padding: 14px 0; border-bottom: 1px solid rgba(238, 241, 234, 0.08); font-size: 14px; font-weight: 600; }
        .price-table-brutal td:last-child { text-align: right; font-family: var(--mono); color: var(--floodlight); font-weight: 700; }

        .book-widget-brutal { background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.1); border-radius: var(--radius); padding: 28px; position: sticky; top: 100px; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6); }
        .book-widget-brutal h3 { font-size: 16px; margin-bottom: 16px; letter-spacing: .05em; color: white; }
        
        .mini-slots-brutal { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-bottom: 20px; }
        .mini-slot-brutal { padding: 12px 0; text-align: center; border-radius: 6px; background: var(--surface-3); font-family: var(--mono); font-size: 12px; color: var(--muted-2); font-weight: 700; border: 1px solid transparent; }
        .mini-slot-brutal.open { background: var(--floodlight-dim); border: 1px solid rgba(245, 197, 24, 0.35); color: var(--floodlight); }
        .mini-slot-brutal.booked { background: repeating-linear-gradient(135deg, var(--surface-3), var(--surface-3) 4px, #171f28 4px, #171f28 8px); color: var(--muted-2); opacity: 0.35; cursor: not-allowed; line-through; }

        .price-total-brutal { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 20px; padding-top: 16px; border-top: 1px solid rgba(238, 241, 234, 0.08); }
        .price-total-brutal span { font-size: 13px; color: var(--muted); font-weight: 600; }
        .price-total-brutal b { font-family: var(--mono); font-size: 24px; color: var(--floodlight); }
    </style>
</head>
<body>

    <!-- STICKY TOP HEADER -->
    <header style="background: rgba(10, 15, 20, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(238, 241, 234, 0.08); position: sticky; top: 0; z-index: 50;">
        <div style="max-width: 1180px; margin: 0 auto; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; height: 80px;">
            <a href="{{ route('landingPage') }}" style="display: flex; align-items: center; gap: 10px; font-family: var(--display); font-size: 22px; color: white;">
                <span style="width: 10px; height: 10px; background: var(--turf); border-radius: 2px; transform: rotate(45deg);"></span>FUTSAL MARE
            </a>
            <a href="{{ route('landingPage') }}" style="font-family: var(--mono); font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700;">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </header>

    <div class="brutal-wrap">
        <!-- BREADCRUMB ROUTING -->
        <div class="breadcrumb-brutal">
            <a href="{{ route('landingPage') }}">Beranda</a> / 
            <a href="{{ route('landingPage') }}#daftar-lapangan">Katalog Arena</a> / 
            <span style="color: var(--line);">{{ $lapangan->nama_lapangan }}</span>
        </div>

        <!-- HERO BRAND MEDIA BANNER -->
        <div class="detail-hero-brutal">
            @if($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
            @elseif($lapangan->foto_lapangan)
                <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
            @else
                <div style="height:100%; display:flex; align-items:center; justify-content:center; color:var(--muted-2); font-family:var(--mono); font-size:12px; text-transform:uppercase;">No Image Framework Asset</div>
            @endif
        </div>

        <!-- SPECIFICATION GRID MASHUP -->
        <div class="detail-grid-brutal">
            <!-- Left Column: Field Overview -->
            <div>
                <div class="eyebrow-brutal">Premium Arena</div>
                <h1>{{ $lapangan->nama_lapangan }}</h1>
                
                <div class="detail-meta-brutal">
                    <span>UKURAN STANDAR NASIONAL</span>
                    <span>·</span>
                    <span>🌱 Permukaan: {{ strtoupper($lapangan->jenis_rumput ?? 'Sintetis') }}</span>
                    <span>·</span>
                    <span>KAPASITAS MAX 14 ORANG</span>
                </div>
                
                <p class="detail-desc-brutal">
                    Nikmati kenyamanan bermain futsal di arena berkelas premium Kota Baubau. Sistem struktur drainase serta material rumput sintetis pilihan kami dirancang khusus untuk meminimalkan risiko cedera fatal dan memaksimalkan akurasi sirkulasi umpan taktis tim sepak bola Anda.
                </p>
                
                <ul class="facility-list-brutal">
                    <li>Sistem Lampu Sorot LED Bebas Silau</li>
                    <li>Fasilitas Bola Pertandingan Disediakan</li>
                    <li>Ruang Ganti Pemain Eksklusif & Loker</li>
                    <li>Akses Toilet Steril & Mushola Bersih</li>
                    <li>Area Parkir Kendaraan Luas Terpantau</li>
                    <li>Tribun Penonton Pasif & Kantin Area</li>
                </ul>
                
                <table class="price-table-brutal">
                    <thead>
                        <tr style="color: var(--muted-2); font-family: var(--mono); font-size: 11px; text-transform: uppercase;">
                            <td style="border-bottom: 1px solid rgba(238, 241, 234, .1); padding-bottom: 8px;">Skema Kalender</td>
                            <td style="text-align: right; border-bottom: 1px solid rgba(238, 241, 234, .1); padding-bottom: 8px;">Tarif Sewa Base</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Senin – Minggu (Setiap Hari)</td>
                            <td>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / Jam</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Right Column: Interactive Scheduling Board Block -->
            <div class="book-widget-brutal">
                <h3>KETERSEDIAAN HARI INI</h3>
                <div style="font-size: 12px; color: var(--muted); font-family: var(--mono); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.05em;">
                    Status Slot Waktu Operasional ({{ \Carbon\Carbon::parse($tanggal_pilihan)->translatedFormat('d F Y') }}):
                </div>
                
                <div class="mini-slots-brutal">
                    @for ($jam = 8; $jam <= 21; $jam++)
                        @php
                            $isBooked = in_array($jam, $jam_terpesan);
                            $jam_format = sprintf('%02d:00', $jam);
                        @endphp
                        
                        <div class="mini-slot-brutal {{ $isBooked ? 'booked' : 'open' }}">
                            {{ $jam_format }}
                        </div>
                    @endfor
                </div>

                <div style="display: flex; gap: 14px; margin-bottom: 24px; font-size: 11px; font-family: var(--mono); color: var(--muted-2); font-weight: 700; text-transform: uppercase;">
                    <span style="display: flex; align-items: center; gap: 6px;"><i style="width: 10px; height: 10px; background: var(--floodlight-dim); border: 1px solid var(--floodlight); border-radius: 2px;"></i> Kosong</span>
                    <span style="display: flex; align-items: center; gap: 6px;"><i style="width: 10px; height: 10px; background: var(--surface-3); border-radius: 2px; opacity: 0.4;"></i> Terisi</span>
                </div>
                
                <div class="price-total-brutal">
                    <span>Tarif Dasar Per Jam</span>
                    <b>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</b>
                </div>
                
                <a href="{{ route('reservasi.create', $lapangan->id) }}" class="btn-ui btn-ui-primary" style="text-align: center;">
                    Lanjut Pengisian Form &rarr;
                </a>
            </div>
        </div>
    </div>

</body>
</html>