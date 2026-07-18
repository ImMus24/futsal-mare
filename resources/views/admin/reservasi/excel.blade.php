<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Reservasi Futsal Mare</title>
    {{--
        CATATAN PENTING (Excel compatibility):
        File ini dibuka lewat Microsoft Excel (Content-Type application/vnd.ms-excel),
        yang me-render HTML dengan mesin render terbatas — TIDAK mendukung CSS custom
        properties (var(--x)), @import font Google, gradient, box-shadow, atau flex/grid.
        Semua nilai di bawah sengaja ditulis literal (hex/rgb) dan layout memakai
        table murni agar warna & struktur tetap tampil benar saat dibuka di Excel,
        sekaligus tetap rapi kalau file ini dibuka langsung di browser.
    --}}
    <style>
        body {
            background: #ffffff;
            color: #0a0f14;
            font-family: Calibri, Arial, sans-serif;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER / LETTERHEAD */
        .report-header {
            border: 2px solid #0a0f14;
            padding: 20px 24px;
            margin-bottom: 20px;
            background: #f8fafc;
        }
        .brand-mark {
            display: inline-block;
            width: 14px; height: 14px;
            background: #e25e20;
            margin-right: 8px;
            vertical-align: middle;
        }
        .report-header h2 {
            font-size: 22px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0 0 10px 0;
            color: #0a0f14;
            line-height: 1.1;
            display: inline-block;
            vertical-align: middle;
        }
        .meta-tag {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #5c6979;
            text-transform: uppercase;
            font-weight: bold;
        }
        .filter-badge {
            margin-top: 10px;
            display: inline-block;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            font-weight: bold;
            background: #0a0f14;
            color: #ffffff;
            padding: 4px 10px;
            text-transform: uppercase;
        }

        /* DATA TABLE */
        .table-report {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .table-report th {
            background: #0a0f14;
            color: #ffffff;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 10px;
            border: 1px solid #0a0f14;
        }
        .table-report td {
            padding: 10px;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .table-report tr.even-row { background: #f8fafc; }

        /* SUMMARY / TOTAL ROW */
        .table-report tfoot td {
            border: 2px solid #0a0f14;
            background: #fff7ea;
            font-weight: bold;
            color: #0a0f14;
            padding: 12px 10px;
        }

        .mono-strong { font-family: 'Courier New', monospace; font-weight: bold; color: #0a0f14; }
        .accent-text { color: #e25e20; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }

        /* Status colors (literal, safe for Excel) */
        .status-confirmed { color: #16a34a; font-weight: bold; }
        .status-waiting    { color: #b45309; font-weight: bold; }
        .status-completed  { color: #1d4ed8; font-weight: bold; }
        .status-cancelled  { color: #dc2626; font-weight: bold; }

        .report-footer {
            margin-top: 18px;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #5c6979;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <!-- HEADER LAPORAN -->
    <div class="report-header">
        <span class="brand-mark"></span><h2>Laporan Data Reservasi Lapangan — Futsal Mare</h2>
        <br>
        <span class="meta-tag">Tanggal Cetak: {{ date('d-m-Y H:i:s') }} WITA</span>
        <span class="meta-tag" style="margin-left: 16px;">Total Baris: {{ $reservasis->count() }}</span>
        @if(request('status'))
            <br>
            <div class="filter-badge">⚡ Filter Status: {{ request('status') }}</div>
        @endif
    </div>

    <!-- TABEL DATA -->
    <table class="table-report" border="1">
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
            @php $no = 1; $grandTotal = 0; @endphp
            @foreach($reservasis as $reservasi)
                @php $grandTotal += $reservasi->total_harga; @endphp
                <tr class="{{ $no % 2 === 0 ? 'even-row' : '' }}">
                    <td class="text-center mono-strong">{{ $no++ }}</td>
                    <td class="mono-strong">#{{ $reservasi->id }}</td>
                    <td class="accent-text">{{ $reservasi->nomor_reservasi }}</td>
                    <td style="font-weight: bold;">{{ strtoupper($reservasi->user->name ?? 'User Terhapus') }}</td>
                    <td>{{ $reservasi->user->email ?? '-' }}</td>
                    <td style="font-weight: 600;">{{ strtoupper($reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->format('d-m-Y') }}</td>
                    <td class="text-center mono-strong">
                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                    </td>
                    <!-- Angka dikirim mentah tanpa Rp agar rumus SUM Excel berjalan sempurna -->
                    <td class="text-right mono-strong" style="color: #16a34a;">{{ $reservasi->total_harga }}</td>
                    <td class="text-center">
                        @php
                            $statusClass = match($reservasi->status) {
                                'Confirmed' => 'status-confirmed',
                                'Waiting Payment' => 'status-waiting',
                                'Completed' => 'status-completed',
                                default => 'status-cancelled',
                            };
                        @endphp
                        <span class="{{ $statusClass }}" style="font-size: 11px;">{{ strtoupper($reservasi->status) }}</span>
                    </td>
                    <td class="text-center">{{ $reservasi->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right" style="color: #16a34a;">{{ $grandTotal }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="report-footer">
        Dokumen dihasilkan otomatis oleh Sistem Informasi Futsal Mare · Kota Baubau, Sulawesi Tenggara
    </div>

</body>
</html>