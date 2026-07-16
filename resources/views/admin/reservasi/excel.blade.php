<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Reservasi Futsal Mare</title>
</head>
<body>
    <!-- HEADER LAPORAN -->
    <h2>LAPORAN DATA RESERVASI LAPANGAN - FUTSAL MARE</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    @if(request('status'))
        <p>Filter Status: <strong>{{ strtoupper(request('status')) }}</strong></p>
    @endif
    
    <table border="1">
        <thead>
            <tr style="background-color: #E25E20; color: #ffffff; font-weight: bold;">
                <th>No</th>
                <th>ID Record</th>
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
                    <td style="text-align: center;">{{ $no++ }}</td>
                    <td>#{{ $reservasi->id }}</td>
                    <!-- Memastikan nomor reservasi dibaca sebagai teks agar string/karakter unik tidak rusak -->
                    <td style="font-family: monospace;">{{ $reservasi->nomor_reservasi }}</td>
                    <td>{{ strtoupper($reservasi->user->name ?? 'User Terhapus') }}</td>
                    <td>{{ $reservasi->user->email ?? '-' }}</td>
                    <td>{{ strtoupper($reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->format('d-m-Y') }}</td>
                    <!-- Menyesuaikan format jam mulai & selesai seperti di view utama -->
                    <td style="text-align: center;">
                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA
                    </td>
                    <!-- Mengirim angka mentah tanpa Rp agar Excel bisa menjumlahkannya secara otomatis dengan rumus SUM -->
                    <td style="text-align: right;">{{ $reservasi->total_harga }}</td>
                    <td style="text-align: center;">{{ strtoupper($reservasi->status) }}</td>
                    <td>{{ $reservasi->created_at->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>