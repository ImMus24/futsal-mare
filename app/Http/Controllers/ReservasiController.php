<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservasiController extends Controller
{
    // Berapa lama slot "Waiting Payment" boleh menggantung sebelum otomatis dianggap kadaluarsa
    const MENIT_KEDALUARSA_PEMBAYARAN = 10;

    // Menampilkan Landing Page Utama beserta daftar lapangan
    public function landingPage()
    {
        $lapangans = Lapangan::all();
        return view('welcome', compact('lapangans'));
    }

    /**
     * 🏟️ NEW METHOD: DETAIL INTERAKTIF LAPANGAN (GOLA BLUEPRINT)
     * Mengelola parameter data master, kalkulasi kalender, dan isolasi slot waktu terpesan.
     */
    public function showLapangan(Request $request, $id)
    {
        // 1. Ambil data atau return fiksasi error 404 jika token ID manipulatif
        $lapangan = Lapangan::findOrFail($id);
        
        // 2. Tentukan penanggalan dinamis (default hari ini)
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());

        // 3. Ambil data jam operasional yang terpesan pada tanggal tersebut untuk isolasi status 'off'
        $jam_terpesan = Reservasi::where('lapangan_id', $id)
            ->where('tanggal_main', $tanggal_pilihan)
            ->whereIn('status', ['Waiting Payment', 'Confirmed', 'Completed'])
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($booking) {
                $mulai = (int) substr($booking->jam_mulai, 0, 2);
                $selesai = (int) substr($booking->jam_selesai, 0, 2);
                return range($mulai, $selesai - 1);
            })->flatten()->toArray();

        // 4. Kirim data ke view detail dengan data ringkas kompensasi
        return view('admin.lapangan.detail', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan'));
    }

    // Menampilkan Form Booking & Cek Ketersediaan Slot Jam
    public function create(Request $request, $id)
    {
        return Reservasi::where('lapangan_id', $lapangan_id)
            ->where('tanggal_main', $tanggal)
            ->where(function ($q) {
                $q->whereIn('status', ['Confirmed', 'Completed'])
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Waiting Payment')
                         ->where('expired_at', '>', now());
                  });
            })
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($booking) {
                $mulai = (int) substr($booking->jam_mulai, 0, 2);
                $selesai = (int) substr($booking->jam_selesai, 0, 2);
                return range($mulai, $selesai - 1);
            })->flatten()->toArray();
    }

    public function showLapangan(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());

        $jam_terpesan = $this->getJamTerpesan($id, $tanggal_pilihan);

        return view('lapangan.detail', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan'));
    }

    // Menampilkan Form Booking & Cek Ketersediaan Slot Jam
    public function create(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());

        $jam_terpesan = $this->getJamTerpesan($id, $tanggal_pilihan);

        return view('reservasi.create', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan'));
    }

    public function store(Request $request)
{
    $request->validate([
        'lapangan_id'  => 'required|exists:lapangans,id',
        'tanggal_main' => 'required|date|after_or_equal:today',
        'jam_mulai'    => 'required|integer|between:8,21',
        'durasi'       => 'required|integer|between:1,3',
    ]);

    $lapangan = Lapangan::findOrFail($request->lapangan_id);
    $user = Auth::user();
    $tanggal = Carbon::parse($request->tanggal_main);
    
    $start_hour = $request->jam_mulai;
    $end_hour = $start_hour + $request->durasi;
    $start_time = sprintf('%02d:00:00', $start_hour);
    $end_time = sprintf('%02d:00:00', $end_hour);

    return DB::transaction(function () use ($request, $lapangan, $user, $tanggal, $start_hour, $end_hour, $start_time, $end_time) {
        
        // 1. Kalkulasi Harga Dasar (Dynamic Pricing)
        $biaya_dasar = $lapangan->harga_per_jam;
        $total_harga = 0;
        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            $harga_slot = $biaya_dasar;
            if ($hour >= 16 && $hour < 22) $harga_slot += 50000;
            if ($tanggal->isWeekend()) $harga_slot += 20000;
            $total_harga += $harga_slot;
        }

        // 2. Terapkan Diskon Membership
        $membership = $user->membership; // Menggunakan relasi dari model User
        $diskon = $membership ? ($total_harga * $membership->discount_percent) : 0;
        $total_final = $total_harga - $diskon;

        // 3. Generate Nomor Reservasi
        $nomor_reservasi = 'FM-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -5));

        // 4. Simpan Data Reservasi (HANYA SATU KALI)
        $reservasi = Reservasi::create([
            'user_id'         => $user->id,
            'lapangan_id'     => $request->lapangan_id,
            'nomor_reservasi' => $nomor_reservasi,
            'tanggal_main'    => $request->tanggal_main,
            'jam_mulai'       => $start_time,
            'jam_selesai'     => $end_time,
            'total_harga'     => (int) $total_final, // Total akhir setelah diskon
            'status'          => 'Waiting Payment', 
        ]);

        // 5. Integrasi Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => (string) $nomor_reservasi,
                'gross_amount' => (int) $total_final,
            ],
            'customer_details' => [
                'first_name' => (string) $user->name,
                'email'      => (string) $user->email,
            ]
        ];

        try {
            $serverKey = config('services.midtrans.server_key');
            $base64Auth = base64_encode($serverKey . ':');
            
            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . $base64Auth,
            ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            if ($response->failed()) {
                throw new \Exception('API Midtrans Menolak: ' . $response->body());
            }

            $snapToken = $response->json()['token'];
            $reservasi->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'redirect'   => route('dashboard')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal jabat tangan API gateway: ' . $e->getMessage()
            ], 500);
        }
    }); 
}

    // MIDTRANS WEBHOOK NOTIFICATION HANDLER (produksi)
    public function handleNotification(Request $request)
    {
        $request->validate([
            'order_id'            => 'required|string',
            'status_code'         => 'required|string',
            'gross_amount'        => 'required|string',
            'signature_key'       => 'required|string',
            'transaction_status'  => 'required|string',
        ]);

        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Signature Midtrans tidak valid untuk order ' . $request->order_id);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;

        $reservasi = Reservasi::where('nomor_reservasi', $orderId)->first();

        if (!$reservasi) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $gross_amount_int = (int) round((float) $request->gross_amount);
        if ($gross_amount_int !== (int) $reservasi->total_harga) {
            Log::error("Nominal webhook tidak cocok untuk order {$orderId}: dikirim {$gross_amount_int}, tercatat {$reservasi->total_harga}");
            return response()->json(['message' => 'Nominal tidak cocok'], 422);
        }

        DB::transaction(function () use ($transactionStatus, $reservasi, $request) {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $this->konfirmasiPembayaranSukses($reservasi, $request->payment_type);
            } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                if ($reservasi->status === 'Waiting Payment') {
                    $reservasi->update(['status' => 'Cancelled']);
                }
            }
        });

        return response()->json(['message' => 'Callback diproses sukses']);
    }

    /**
     * Menampilkan Halaman Dashboard Riwayat Reservasi Member.
     * Mengirim semua variabel yang dibutuhkan dashboard.blade.php:
     * membership, totalBooking, lunasBooking, totalPengeluaran — dihitung
     * dari koleksi $reservasis yang sama supaya angkanya selalu konsisten.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $reservasis = Reservasi::where('user_id', $user->id)
            ->with('lapangan')
            ->latest()
            ->get();

        $membership = $user->membership ?? (object) [
            'membership_type' => 'Bronze',
            'points' => 0,
        ];

        $totalBooking = $reservasis->count();
        $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
        $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');

        return view('dashboard', compact(
            'reservasis',
            'membership',
            'totalBooking',
            'lunasBooking',
            'totalPengeluaran'
        ));
    }

    // PEMBATALAN INSTAN DENGAN TOLERANSI KASUS STATUS
    public function batalkanReservasi($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Data reservasi tidak ditemukan.');
        }

        if (strtolower($reservasi->status) !== 'waiting payment') {
            return redirect()->route('dashboard')->with('error', 'Reservasi tidak dapat dibatalkan karena statusnya sudah: ' . $reservasi->status);
        }

        try {
            $serverKey = config('services.midtrans.server_key');
            $base64Auth = base64_encode($serverKey . ':');

            Http::withHeaders([
                'Authorization' => 'Basic ' . $base64Auth,
                'Accept'        => 'application/json',
            ])->timeout(5)->post("https://api.sandbox.midtrans.com/v2/{$reservasi->nomor_reservasi}/cancel");

            if ($response->failed()) {
                Log::warning("Gagal membatalkan order {$reservasi->nomor_reservasi} di Midtrans: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Koneksi ke Midtrans gagal saat pembatalan: ' . $e->getMessage());
        }

        $reservasi->update([
            'status' => 'Cancelled'
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Jadwal reservasi match Anda telah berhasil dibatalkan.');
    }

    // Menghapus riwayat transaksi dari sisi pandang member secara aman
    public function destroy($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Confirmed', 'Completed', 'Cancelled'])
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Riwayat transaksi tidak ditemukan, atau belum bisa dihapus karena masih menunggu pembayaran.');
        }

        $reservasi->delete();
        return redirect()->route('dashboard')->with('success', 'Riwayat transaksi berhasil dihapus.');
    }

    // Menghapus banyak riwayat transaksi terpilih sekaligus dengan aman
    public function destroyMassal(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:reservasis,id',
        ], [
            'ids.required' => 'Pilih minimal satu riwayat transaksi untuk dihapus.',
            'ids.array'    => 'Data yang dikirim tidak valid.',
            'ids.*.exists' => 'Salah satu transaksi yang dipilih tidak ditemukan.',
        ]);

        $deleted = Reservasi::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Confirmed', 'Completed', 'Cancelled'])
            ->delete();

        if ($deleted === 0) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada riwayat transaksi valid yang dapat dihapus.');
        }

        return redirect()->route('dashboard')->with('success', 'Seluruh riwayat transaksi terpilih berhasil dihapus.');
    }

    // MENCETAK E-TIKET QR CODE (SVG)
    public function cetakTiket($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Confirmed', 'Completed'])
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Tiket tidak dapat dibuka. Pastikan reservasi sudah lunas sebelum mengunduh e-tiket.');
        }

        $nama_file = 'qr_' . $reservasi->nomor_reservasi . '.svg';
        $relative_path = 'qrcodes/' . $nama_file;

        if (!Storage::disk('public')->exists($relative_path)) {
            $svg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($reservasi->nomor_reservasi);

            Storage::disk('public')->put($relative_path, $svg);
            $reservasi->update(['qr_code_path' => $nama_file]);
        }

        // PENTING: $qrUrl dihitung dari disk YANG SAMA dengan tempat file
        // benar-benar disimpan (Storage::disk('public')), bukan ditebak ulang
        // di Blade dengan public_path() folder yang berbeda — itu bug lama
        // yang membuat QR code di tiket tidak pernah tampil sama sekali.
        // Butuh symlink aktif: jalankan `php artisan storage:link` kalau
        // belum pernah, atau URL ini akan 404 walau file-nya benar-benar ada.
        $qrUrl = Storage::disk('public')->url($relative_path);

        return view('reservasi.tiket', compact('reservasi', 'qrUrl'));
    }

    /**
     * TERMINAL GATE SCANNER CHECK-IN.
     */
    public function processStaffCheckIn(Request $request)
    {
        $request->validate([
            'nomor_reservasi' => 'required|string',
        ], [
            'nomor_reservasi.required' => 'Kode QR tidak terbaca, silakan pindai ulang.',
        ]);

        try {
            $reservasi = Reservasi::where('nomor_reservasi', $request->nomor_reservasi)->first();

            if (!$reservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode QR tidak valid / data reservasi tidak ditemukan.'
                ], 404);
            }

            if ($reservasi->status === 'Cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ditolak, jadwal ini telah dibatalkan.'
                ], 422);
            }

            if ($reservasi->status === 'Waiting Payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ditolak, pembayaran belum diselesaikan.'
                ], 422);
            }

            if ($reservasi->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ini sudah pernah digunakan untuk check-in.'
                ], 422);
            }

            if ($reservasi->status === 'Confirmed') {
                $reservasi->update(['status' => 'Completed']);

                return response()->json([
                    'success' => true,
                    'message' => 'Verifikasi berhasil! Selamat bertanding untuk tim ' . ($reservasi->user->name ?? 'Pelanggan') . '.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Status reservasi tidak dikenali: ' . $reservasi->status
            ], 400);

        } catch (\Exception $e) {
            Log::error('Gagal memproses check-in: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem, silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * 📷 REAL-TIME TERMINAL GATE SCANNER CHECK-IN
     * Memproses verifikasi QR Code E-Tiket dari sisi Staff/Pengawas Lapangan via AJAX.
     */
    public function processStaffCheckIn(Request $request)
    {
        $request->validate([
            'nomor_reservasi' => 'required|string',
        ]);

        try {
            $reservasi = Reservasi::where('nomor_reservasi', $request->nomor_reservasi)->first();

            if (!$reservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode QR tidak valid / data reservasi tidak ditemukan!'
                ], 404);
            }

            if ($reservasi->status === 'Cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ditolak! Jadwal match ini telah dibatalkan.'
                ], 422);
            }

            if ($reservasi->status === 'Waiting Payment') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket ditolak! Tim belum menyelesaikan tagihan transaksi di Midtrans.'
                ], 422);
            }

            if ($reservasi->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peringatan! Tiket ini sudah pernah digunakan untuk check-in.'
                ], 422);
            }

            if ($reservasi->status === 'Confirmed') {
                $reservasi->update([
                    'status' => 'Completed'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Verifikasi berhasil! Selamat bertanding untuk tim ' . ($reservasi->user->name ?? 'Pelanggan') . '.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi anomali status data: ' . $reservasi->status
            ], 400);

        } catch (\Exception $e) {
            Log::error('Gagal memproses Gate Check-In: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kegagalan sistem internal server hq.'
            ], 500);
        }
    }
}