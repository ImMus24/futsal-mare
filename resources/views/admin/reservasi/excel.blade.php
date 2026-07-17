<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Reservasi Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
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
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        body {
            background: #ffffff;
            color: var(--ink);
            font-family: var(--body);
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER CONTROL DECK BLOCK */
        .report-header-brutal {
            border: 2px solid var(--ink);
            padding: 24px;
            margin-bottom: 24px;
            background: #f8fafc;
            position: relative;
        }
        .report-header-brutal h2 {
            font-family: var(--display);
            font-size: 26px;
            letter-spacing: .02em;
            text-transform: uppercase;
            margin: 0 0 8px 0;
            color: var(--ink);
            line-height: 1;
        }
        .meta-tag-brutal {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--muted-2);
            text-transform: uppercase;
            font-weight: 700;
            display: inline-flex;
            gap: 16px;
        }
        .filter-badge-brutal {
            margin-top: 12px;
            display: inline-block;
            font-family: var(--mono);
            font-size: 10px;
            font-weight: 700;
            background: var(--ink);
            color: #ffffff;
            padding: 4px 8px;
            text-transform: uppercase;
        }

        /* BRUTALIST DATA SHEET TABLE */
        .table-brutal {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            font-weight: 500;
        }
        .table-brutal th {
            background: var(--ink) !important;
            color: #ffffff !important;
            font-family: var(--mono);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 10px;
            border: 2px solid var(--ink);
        }
        .table-brutal td {
            padding: 12px 10px;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .table-brutal tr:nth-child(even) {
            background: #f8fafc;
        }
        .table-brutal tr:hover {
            background: #f1f5f9;
        }

        /* TYPOGRAPHY UTILITIES */
        .font-mono-brutal {
            font-family: var(--mono);
            font-weight: 700;
            color: var(--ink);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <!-- HEADER LAPORAN BOX DECK -->
    <div class="report-header-brutal">
        <h2>LAPORAN DATA RESERVASI LAPANGAN - FUTSAL MARE</h2>
        <div class="meta-tag-brutal">
            <span>TANGGAL CETAK: {{ date('d-m-Y H:i:s') }}</span>
        </div>
        @if(request('status'))
            <br>
            <div class="filter-badge-brutal">
                ⚡ FILTER STATUS: {{ request('status') }}
            </div>
        @endif
    </div>
    
    <!-- MAIN BRUTALIST DATA TABLE -->
    <table class="table-brutal" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nomor Reservasi</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Nama Lapangan</th>
                <th>Tanggal Main</th>
                <th>Slot Waktu</th>
                <th>Total Harga (IDR)</th>
                <th>Status Transaksi</th>
                <th>Waktu Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($reservasis as $reservasi)
                <tr>
                    <td class="text-center font-mono-brutal">{{ $no++ }}</td>
                    <td class="font-mono-brutal">#{{ $reservasi->id }}</td>
                    <td class="font-mono-brutal" style="color: var(--turf);">{{ $reservasi->nomor_reservasi }}</td>
                    <td style="font-weight: 700;">{{ strtoupper($reservasi->user->name ?? 'User Terhapus') }}</td>
                    <td>{{ $reservasi->user->email ?? '-' }}</td>
                    <td style="font-weight: 600;">{{ strtoupper($reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->format('d-m-Y') }}</td>
                    <td class="text-center font-mono-brutal">
                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                    </td>
                    <!-- Angka dikirim mentah tanpa Rp agar rumus SUM Excel berjalan sempurna -->
                    <td class="text-right font-mono-brutal" style="color: #16a34a;">{{ $reservasi->total_harga }}</td>
                    <td class="text-center font-mono-brutal">
                        <span style="font-size: 11px;">{{ strtoupper($reservasi->status) }}</span>
                    </td>
                    <td class="text-center">{{ $reservasi->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>