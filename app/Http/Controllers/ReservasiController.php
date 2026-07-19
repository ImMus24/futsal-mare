<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservasiController extends Controller
{
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
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());

        $jam_terpesan = Reservasi::where('lapangan_id', $id)
            ->where('tanggal_main', $tanggal_pilihan)
            ->whereIn('status', ['Waiting Payment', 'Confirmed', 'Completed'])
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($booking) {
                $mulai = (int) substr($booking->jam_mulai, 0, 2);
                $selesai = (int) substr($booking->jam_selesai, 0, 2);
                return range($mulai, $selesai - 1);
            })->flatten()->toArray();

        return view('reservasi.create', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan'));
    }

    // Menyimpan Data Reservasi Baru & Menggenerasi Token Pembayaran Instan via API Direct Hit
    public function store(Request $request)
    {
        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $tanggal = Carbon::parse($request->tanggal_main);
        
        $start_hour = $request->jam_mulai;
        $end_hour = $start_hour + $request->durasi;

        $start_time = sprintf('%02d:00:00', $start_hour);
        $end_time = sprintf('%02d:00:00', $end_hour);

        return DB::transaction(function () use ($request, $lapangan, $tanggal, $start_hour, $end_hour, $start_time, $end_time) {

            // KUNCI BARIS LAPANGAN INI (bukan baris reservasi) selama transaksi berjalan.
            Lapangan::where('id', $request->lapangan_id)->lockForUpdate()->first();

            // 1. VALIDASI OVERLAP JADWAL (Mencegah Race Condition)
            $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
                ->where('tanggal_main', $request->tanggal_main)
                ->whereIn('status', ['Waiting Payment', 'Confirmed', 'Completed'])
                ->where(function ($query) use ($start_time, $end_time) {
                    $query->where(function ($q) use ($start_time, $end_time) {
                        $q->where('jam_mulai', '>=', $start_time)
                          ->where('jam_mulai', '<', $end_time);
                    })->orWhere(function ($q) use ($start_time, $end_time) {
                        $q->where('jam_mulai', '<=', $start_time)
                          ->where('jam_selesai', '>', $start_time);
                    });
                })->lockForUpdate()->exists();

            if ($bentrok) {
                return response()->json(['success' => false, 'message' => 'Jadwal slot jam tersebut baru saja dipesan oleh tim lain!'], 422);
            }

            // 2. PREMIUM: DYNAMIC PRICING CALCULATOR
            $biaya_dasar = $lapangan->harga_per_jam;
            $total_harga = 0;

            for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                $harga_slot = $biaya_dasar;
                if ($hour >= 16 && $hour < 22) $harga_slot += 50000;
                if ($tanggal->isWeekend()) $harga_slot += 20000;
                $total_harga += $harga_slot;
            }

            // 3. GENERATE NOMOR RESERVASI UNIK
            $nomor_reservasi = 'FM-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -5));

            // 4. SIMPAN TRANSAKSI BARU (Status awal: Waiting Payment)
            $reservasi = Reservasi::create([
                'user_id'         => Auth::id(),
                'lapangan_id'     => $request->lapangan_id,
                'nomor_reservasi' => $nomor_reservasi,
                'tanggal_main'    => $request->tanggal_main,
                'jam_mulai'       => $start_time,
                'jam_selesai'     => $end_time,
                'total_harga'     => (int) $total_harga,
                'status'          => 'Waiting Payment', 
            ]);

            // 5. BYPASS SDK MIDTRANS: PEMBUATAN STRUKTUR PAYLOAD BERSIH
            $params = [
                'transaction_details' => [
                    'order_id'     => (string) $nomor_reservasi,
                    'gross_amount' => (int) $total_harga,
                ],
                'customer_details' => [
                    'first_name' => (string) Auth::user()->name,
                    'email'      => (string) Auth::user()->email,
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
                    throw new \Exception('API Midtrans Menolak Permintaan: ' . $response->body());
                }

                $responseData = $response->json();
                $snapToken = $responseData['token'];
                
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

    /**
     * PEMBATALAN INSTAN SAAT USER MENUTUP POPUP MIDTRANS (onClose).
     */
    public function cancelPendingInstant(Request $request, $nomor_reservasi)
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return response()->json(['success' => false, 'message' => 'Reservasi tidak ditemukan.'], 404);
        }

        if ($reservasi->status === 'Waiting Payment') {
            try {
                $serverKey = config('services.midtrans.server_key');
                $base64Auth = base64_encode($serverKey . ':');
                Http::withHeaders([
                    'Authorization' => 'Basic ' . $base64Auth,
                    'Accept'        => 'application/json',
                ])->timeout(5)->post("https://api.sandbox.midtrans.com/v2/{$reservasi->nomor_reservasi}/cancel");
            } catch (\Exception $e) {
                Log::warning("Gagal cancel instant di Midtrans untuk {$reservasi->nomor_reservasi}: " . $e->getMessage());
            }

            $reservasi->update(['status' => 'Cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi dibatalkan, slot jam dilepas kembali.',
            ]);
        }

        return response()->json([
            'success'            => true,
            'already_confirmed'  => $reservasi->status === 'Confirmed',
            'status'             => $reservasi->status,
        ]);
    }

    /**
     * KONFIRMASI PEMBAYARAN INSTAN DARI SISI KLIEN (dipanggil dari onSuccess/onPending Snap.js).
     *
     * KENAPA INI DIPERLUKAN: di lingkungan development lokal (127.0.0.1/localhost),
     * webhook Midtrans (handleNotification() di bawah) TIDAK PERNAH terpanggil,
     * karena server Midtrans tidak bisa menjangkau alamat localhost dari internet.
     * Endpoint ini memberi jalur konfirmasi kedua yang aman dipanggil berkali-kali
     * (idempotent), dan TIDAK langsung percaya klaim dari browser — ia mengecek
     * status transaksi yang sebenarnya langsung ke API status Midtrans.
     */
    public function confirmPayment($nomor_reservasi)
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return response()->json(['success' => false, 'message' => 'Reservasi tidak ditemukan.'], 404);
        }

        if (in_array($reservasi->status, ['Confirmed', 'Completed'])) {
            return response()->json(['success' => true, 'status' => $reservasi->status]);
        }

        try {
            $serverKey = config('services.midtrans.server_key');
            $base64Auth = base64_encode($serverKey . ':');

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $base64Auth,
                'Accept'        => 'application/json',
            ])->timeout(8)->get("https://api.sandbox.midtrans.com/v2/{$reservasi->nomor_reservasi}/status");

            if ($response->failed()) {
                Log::warning("Gagal cek status Midtrans untuk {$reservasi->nomor_reservasi}: " . $response->body());
                return response()->json([
                    'success' => false,
                    'status'  => $reservasi->status,
                    'message' => 'Belum bisa memverifikasi status pembayaran, silakan cek lagi sebentar.',
                ], 202);
            }

            $data = $response->json();
            $transactionStatus = $data['transaction_status'] ?? null;

            $gross_amount_int = isset($data['gross_amount']) ? (int) round((float) $data['gross_amount']) : null;
            if ($gross_amount_int !== null && $gross_amount_int !== (int) $reservasi->total_harga) {
                Log::error("Nominal status API tidak cocok untuk order {$reservasi->nomor_reservasi}: dilaporkan {$gross_amount_int}, tercatat {$reservasi->total_harga}");
                return response()->json(['success' => false, 'status' => $reservasi->status, 'message' => 'Verifikasi nominal pembayaran gagal.'], 422);
            }

            DB::transaction(function () use ($transactionStatus, $reservasi, $data) {
                if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                    $this->konfirmasiPembayaranSukses($reservasi, $data['payment_type'] ?? null);
                } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                    if ($reservasi->status === 'Waiting Payment') {
                        $reservasi->update(['status' => 'Cancelled']);
                    }
                }
            });

            $statusAkhir = $reservasi->fresh()->status;

            if ($statusAkhir === 'Confirmed') {
                session()->flash('success', 'Pembayaran berhasil dikonfirmasi! Jadwal reservasi Anda sudah lunas.');
            } elseif ($statusAkhir === 'Cancelled' && $transactionStatus !== null) {
                session()->flash('error', 'Transaksi pembayaran dibatalkan/kadaluarsa di sisi Midtrans.');
            }

            return response()->json([
                'success' => true,
                'status'  => $statusAkhir,
            ]);

        } catch (\Exception $e) {
            Log::error("Gagal konfirmasi instan pembayaran {$reservasi->nomor_reservasi}: " . $e->getMessage());

            session()->flash('error', 'Pembayaran diterima, namun verifikasi instan gagal. Status akan diperbarui otomatis dalam beberapa saat — refresh halaman ini jika belum berubah.');

            return response()->json([
                'success' => false,
                'status'  => $reservasi->status,
                'message' => 'Terjadi kesalahan sistem saat memverifikasi pembayaran.',
            ], 500);
        }
    }

    /**
     * Terapkan efek pembayaran sukses: ubah status jadi Confirmed dan tambahkan
     * poin membership. Dipakai bersama oleh confirmPayment() (jalur klien/lokal)
     * dan handleNotification() (webhook resmi/produksi). IDEMPOTENT.
     */
    private function konfirmasiPembayaranSukses(Reservasi $reservasi, ?string $paymentType = null): void
    {
        if (in_array($reservasi->status, ['Confirmed', 'Completed'])) {
            return;
        }

        $reservasi->update([
            'status'            => 'Confirmed',
            'metode_pembayaran' => $paymentType,
            'expired_at'        => null,
        ]);

        $membership = Membership::firstOrCreate(
            ['user_id' => $reservasi->user_id],
            ['membership_type' => 'Bronze', 'points' => 0]
        );

        $poinBaru = floor($reservasi->total_harga / 10000);
        $totalPoinAkhir = $membership->points + $poinBaru;

        $tierEvaluasi = 'Bronze';
        if ($totalPoinAkhir >= 300) {
            $tierEvaluasi = 'Gold';
        } elseif ($totalPoinAkhir >= 100) {
            $tierEvaluasi = 'Silver';
        }

        $membership->update([
            'points'          => $totalPoinAkhir,
            'membership_type' => $tierEvaluasi,
        ]);
    }

    // MIDTRANS WEBHOOK NOTIFICATION HANDLER (produksi)
    public function handleNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
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
                
                $reservasi->update([
                    'status' => 'Confirmed',
                    'metode_pembayaran' => $request->payment_type
                ]);

                $membership = Membership::firstOrCreate(
                    ['user_id' => $reservasi->user_id],
                    ['membership_type' => 'Bronze', 'points' => 0]
                );

                $poinBaru = floor($reservasi->total_harga / 10000);
                $totalPoinAkhir = $membership->points + $poinBaru;

                $tierEvaluasi = 'Bronze';
                if ($totalPoinAkhir >= 300) {
                    $tierEvaluasi = 'Gold';
                } elseif ($totalPoinAkhir >= 100) {
                    $tierEvaluasi = 'Silver';
                }

                $membership->update([
                    'points' => $totalPoinAkhir,
                    'membership_type' => $tierEvaluasi
                ]);

            } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                $reservasi->update(['status' => 'Cancelled']);
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

    /**
     * Cek status reservasi via polling dari frontend.
     */
    public function checkStatus($nomor_reservasi)
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'status' => $reservasi->status,
        ]);
    }

    // PEMBATALAN MANUAL DARI TOMBOL DASHBOARD
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
            ])->post("https://api.sandbox.midtrans.com/v2/{$reservasi->nomor_reservasi}/cancel");

        } catch (\Exception $e) {
            Log::warning('Sinyal pembatalan ke Midtrans gagal/terbaca hangus: ' . $e->getMessage());
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
        return redirect()->route('dashboard')->with('success', 'Riwayat transaksi berhasil dibersihkan dari dashboard.');
    }

    // Menghapus banyak riwayat transaksi terpilih sekaligus dengan aman
    public function destroyMassal(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservasis,id'
        ]);

        $deleted = Reservasi::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Confirmed', 'Completed', 'Cancelled'])
            ->delete();

        if ($deleted === 0) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada riwayat transaksi valid yang dapat dihapus.');
        }

        return redirect()->route('dashboard')->with('success', 'Seluruh riwayat transaksi terpilih berhasil dibersihkan.');
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