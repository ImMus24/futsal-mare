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
     * Helper untuk mengambil jam yang sudah dipesan.
     * PENTING: reservasi 'Waiting Payment' yang sudah lewat expired_at TIDAK dihitung
     * sebagai terpesan, supaya slot langsung available lagi tanpa menunggu job cron.
     */
    private function getJamTerpesan($lapangan_id, $tanggal)
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

    // Menyimpan Data Reservasi Baru & Menggenerasi Token Pembayaran Instan via API Direct Hit
    public function store(StoreReservasiRequest $request)
    {
        // Validasi sudah dijalankan otomatis oleh StoreReservasiRequest,
        // termasuk pesan error informatif per field.

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $tanggal = Carbon::parse($request->tanggal_main);

        $start_hour = (int) $request->jam_mulai;
        $end_hour = $start_hour + (int) $request->durasi;

        $start_time = sprintf('%02d:00:00', $start_hour);
        $end_time = sprintf('%02d:00:00', $end_hour);

        return DB::transaction(function () use ($request, $lapangan, $tanggal, $start_hour, $end_hour, $start_time, $end_time) {

            // KUNCI BARIS LAPANGAN INI (bukan baris reservasi) selama transaksi berjalan.
            // Kalau hanya mengunci baris reservasi yang sudah ada, dua request booking
            // di slot yang SAMA-SAMA MASIH KOSONG bisa lolos cek bentrok bersamaan
            // karena tidak ada baris untuk dikunci. Mengunci baris lapangan memaksa
            // request kedua menunggu sampai transaksi pertama selesai/rollback.
            Lapangan::where('id', $request->lapangan_id)->lockForUpdate()->first();

            // 1. VALIDASI OVERLAP JADWAL (Mencegah Race Condition)
            $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
                ->where('tanggal_main', $request->tanggal_main)
                ->where(function ($q) {
                    $q->whereIn('status', ['Confirmed', 'Completed'])
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'Waiting Payment')
                             ->where('expired_at', '>', now());
                      });
                })
                ->where(function ($query) use ($start_time, $end_time) {
                    $query->where(function ($q) use ($start_time, $end_time) {
                        $q->where('jam_mulai', '>=', $start_time)
                          ->where('jam_mulai', '<', $end_time);
                    })->orWhere(function ($q) use ($start_time, $end_time) {
                        $q->where('jam_mulai', '<=', $start_time)
                          ->where('jam_selesai', '>', $start_time);
                    });
                })->exists();

            if ($bentrok) {
                return response()->json(['success' => false, 'message' => 'Slot jam tersebut baru saja dipesan orang lain, silakan pilih jam lain.'], 422);
            }

            // 2. DYNAMIC PRICING CALCULATOR
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

            // 4. SIMPAN TRANSAKSI BARU (Status awal: Waiting Payment + expired_at)
            $reservasi = Reservasi::create([
                'user_id'         => Auth::id(),
                'lapangan_id'     => $request->lapangan_id,
                'nomor_reservasi' => $nomor_reservasi,
                'tanggal_main'    => $request->tanggal_main,
                'jam_mulai'       => $start_time,
                'jam_selesai'     => $end_time,
                'total_harga'     => (int) $total_harga,
                'status'          => 'Waiting Payment',
                'expired_at'      => now()->addMinutes(self::MENIT_KEDALUARSA_PEMBAYARAN),
            ]);

            // 5. BUAT SNAP TOKEN MIDTRANS
            $params = [
                'transaction_details' => [
                    'order_id'     => (string) $nomor_reservasi,
                    'gross_amount' => (int) $total_harga,
                ],
                'customer_details' => [
                    'first_name' => (string) Auth::user()->name,
                    'email'      => (string) Auth::user()->email,
                ],
                'expiry' => [
                    'unit'     => 'minute',
                    'duration' => self::MENIT_KEDALUARSA_PEMBAYARAN,
                ],
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
                    throw new \Exception('API Midtrans menolak permintaan: ' . $response->body());
                }

                $responseData = $response->json();
                $snapToken = $responseData['token'];

                $reservasi->update(['snap_token' => $snapToken]);

                // PENTING: 'redirect' WAJIB dikirim — dipakai frontend di onSuccess/onPending/onClose
                // untuk pindah halaman setelah popup Snap selesai. Tanpa ini, frontend akan
                // mencoba redirect ke URL kosong/undefined.
                return response()->json([
                    'success'         => true,
                    'snap_token'      => $snapToken,
                    'nomor_reservasi' => $nomor_reservasi,
                    'redirect'        => route('dashboard'),
                ]);

            } catch (\Exception $e) {
                $reservasi->update(['status' => 'Cancelled']);
                Log::error('Gagal membuat Snap token Midtrans: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat sesi pembayaran, silakan coba lagi.'
                ], 500);
            }
        });
    }

    /**
     * PEMBATALAN INSTAN SAAT USER MENUTUP POPUP MIDTRANS (onClose).
     * Dipanggil via AJAX dari frontend begitu event onClose Snap.js terpicu,
     * SEBELUM user sempat menyelesaikan pembayaran.
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

            // Best-effort: minta Midtrans membatalkan transaksi supaya tidak ada pembayaran
            // yang "nyasar" masuk setelah slot ini dianggap batal di sisi kita. Kalau panggilan
            // ini gagal (misal koneksi timeout), kita tetap lanjutkan pembatalan lokal —
            // webhook settlement (kalau ternyata user terlanjur bayar) akan tetap mengoreksi
            // status ini kembali jadi Confirmed karena handleNotification() tidak membatasi
            // hanya dari status 'Waiting Payment'.
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

    // MIDTRANS WEBHOOK NOTIFICATION HANDLER + AUTOMATED TIERING SYSTEM
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

        // Lapisan tambahan di luar signature: pastikan nominal yang dilaporkan Midtrans
        // cocok dengan catatan lokal, supaya kalau suatu saat ada order_id yang "dipakai ulang"
        // dengan nominal berbeda, tidak otomatis lolos.
        $gross_amount_int = (int) round((float) $request->gross_amount);
        if ($gross_amount_int !== (int) $reservasi->total_harga) {
            Log::error("Nominal webhook tidak cocok untuk order {$orderId}: dikirim {$gross_amount_int}, tercatat {$reservasi->total_harga}");
            return response()->json(['message' => 'Nominal tidak cocok'], 422);
        }

        DB::transaction(function () use ($transactionStatus, $reservasi, $request) {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {

                // Jangan proses ulang kalau sudah Confirmed/Completed sebelumnya (idempotent)
                if (in_array($reservasi->status, ['Confirmed', 'Completed'])) {
                    return;
                }

                $reservasi->update([
                    'status'             => 'Confirmed',
                    'metode_pembayaran'  => $request->payment_type,
                    'expired_at'         => null,
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
     *
     * PERBAIKAN: sebelumnya hanya mengirim $reservasis ke view, padahal
     * dashboard.blade.php butuh $membership, $totalBooking, $lunasBooking,
     * dan $totalPengeluaran juga — itu yang menyebabkan error
     * "Undefined variable $membership".
     */
    public function dashboard()
    {
        $user = Auth::user();

        $reservasis = Reservasi::where('user_id', $user->id)
            ->with('lapangan')
            ->latest()
            ->get();

        // Data membership diambil langsung dari relasi user yang sedang login,
        // dengan fallback Bronze/0 poin untuk akun yang belum pernah bertransaksi
        // (baris membership baru dibuat otomatis di handleNotification() saat
        // pembayaran pertama sukses).
        $membership = $user->membership ?? (object) [
            'membership_type' => 'Bronze',
            'points' => 0,
        ];

        // Metrik dihitung dari koleksi yang sama dengan tabel riwayat di bawahnya,
        // supaya angka ringkasan selalu konsisten dengan data yang ditampilkan.
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
     * Cek status reservasi via polling dari frontend, dipakai setelah onSuccess/onPending
     * untuk memastikan status terbaru sebelum redirect.
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

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $base64Auth,
                'Accept'        => 'application/json',
            ])->timeout(5)->post("https://api.sandbox.midtrans.com/v2/{$reservasi->nomor_reservasi}/cancel");

            if ($response->failed()) {
                Log::warning("Gagal membatalkan order {$reservasi->nomor_reservasi} di Midtrans: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Koneksi ke Midtrans gagal saat pembatalan: ' . $e->getMessage());
        }

        $reservasi->update(['status' => 'Cancelled']);

        return redirect()->route('dashboard')->with('success', 'Jadwal reservasi berhasil dibatalkan.');
    }

    // Menghapus riwayat transaksi dari sisi pandang member secara aman
    public function destroy($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Confirmed', 'Completed', 'Cancelled'])
            ->first();

        // Ganti firstOrFail() -> pengecekan manual, supaya ID yang salah/bukan
        // milik user/masih Waiting Payment memberi notifikasi yang jelas,
        // bukan halaman 404 polos yang membingungkan pengguna.
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

<<<<<<< HEAD
        // Ganti firstOrFail() -> pengecekan manual: kalau reservasi belum lunas
        // atau bukan milik user ini, arahkan kembali ke dashboard dengan pesan
        // yang jelas, bukan halaman 404 polos.
        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Tiket tidak dapat dibuka. Pastikan reservasi sudah lunas sebelum mengunduh e-tiket.');
        }

=======
>>>>>>> main
        $nama_file = 'qr_' . $reservasi->nomor_reservasi . '.svg';
        $relative_path = 'qrcodes/' . $nama_file;

        // Pakai Storage facade (bukan public_path()/mkdir manual) — lebih portable
        // kalau suatu saat disk berubah ke S3/cloud storage.
        if (!Storage::disk('public')->exists($relative_path)) {
            $svg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($reservasi->nomor_reservasi);

            Storage::disk('public')->put($relative_path, $svg);
            $reservasi->update(['qr_code_path' => $nama_file]);
        }

        return view('reservasi.tiket', compact('reservasi'));
    }

    /**
     * TERMINAL GATE SCANNER CHECK-IN.
     * PENTING: pastikan route ini dibungkus middleware auth khusus staff/admin —
     * endpoint ini sendiri tidak memverifikasi peran pemanggil.
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
}