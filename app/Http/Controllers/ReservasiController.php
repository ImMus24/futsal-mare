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
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required|integer|between:8,21',
            'durasi'       => 'required|integer|between:1,3',
        ]);

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $tanggal = Carbon::parse($request->tanggal_main);
        
        $start_hour = $request->jam_mulai;
        $end_hour = $start_hour + $request->durasi;

        $start_time = sprintf('%02d:00:00', $start_hour);
        $end_time = sprintf('%02d:00:00', $end_hour);

        return DB::transaction(function () use ($request, $lapangan, $tanggal, $start_hour, $end_hour, $start_time, $end_time) {
            
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

    // MIDTRANS WEBHOOK NOTIFICATION HANDLER + AUTOMATED TIERING SYSTEM
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

    // Menampilkan Halaman Dashboard Riwayat Reservasi Member
    public function dashboard()
    {
        $reservasis = Reservasi::where('user_id', Auth::id())
            ->with(['lapangan', 'user.membership'])
            ->latest()
            ->get();

        return view('dashboard', compact('reservasis'));
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
            ->firstOrFail();

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
            ->firstOrFail();

        $folder_path = public_path('images/qrcodes');
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }

        $nama_file = 'qr_' . $reservasi->nomor_reservasi . '.svg';
        $file_path = $folder_path . '/' . $nama_file;

        if (!file_exists($file_path)) {
            QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($reservasi->nomor_reservasi, $file_path);
            
            $reservasi->update(['qr_code_path' => $nama_file]);
        }

        return view('reservasi.tiket', compact('reservasi'));
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