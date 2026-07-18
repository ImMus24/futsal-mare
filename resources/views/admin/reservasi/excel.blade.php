<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Reservasi Futsal Mare</title>
</head>
<body>
    <h2>LAPORAN DATA RESERVASI LAPANGAN - FUTSAL MARE</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    
    <table border="1">
        <thead>
            <tr style="background-color: #E25E20; color: #ffffff; font-weight: bold;">
                <th>No</th>
                <th>ID Reservasi</th>
                <th>Order ID</th>
                <th>Nama Pelanggan</th>
                <th>Email</th>
                <th>Nama Lapangan</th>
                <th>Tanggal Main</th>
                <th>Jam Mulai</th>
                <th>Durasi</th>
                <th>Total Harga</th>
                <th>Status Pembayaran</th>
                <th>Waktu Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($reservasis as $reservasi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>#{{ $reservasi->id }}</td>
                    <td>{{ $reservasi->order_id }}</td>
                    <td>{{ strtoupper($reservasi->user->name ?? 'N/A') }}</td>
                    <td>{{ $reservasi->user->email ?? 'N/A' }}</td>
                    <td>{{ $reservasi->lapangan->nama_lapangan ?? 'N/A' }}</td>
                    <td>{{ $reservasi->tanggal_main }}</td>
                    <td>{{ sprintf('%02d:00', $reservasi->jam_mulai) }} WIB</td>
                    <td>{{ $reservasi->durasi }} Jam</td>
                    <td>{{ $reservasi->total_harga }}</td>
                    <td>{{ strtoupper($reservasi->status_pembayaran) }}</td>
                    <td>{{ $reservasi->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>