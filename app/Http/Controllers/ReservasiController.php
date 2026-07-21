<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Reservasi;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;

class ReservasiController extends Controller
{
    // === KONSTANTA ===
    public const MENIT_KEDALUARSA_PEMBAYARAN = 10;
    public const JAM_BUKA = 8;
    public const JAM_TUTUP = 22;

    public const STATUS_WAITING   = 'Waiting Payment';
    public const STATUS_CONFIRMED = 'Confirmed';
    public const STATUS_COMPLETED = 'Completed';
    public const STATUS_CANCELLED = 'Cancelled';

    // ==========================================
    // 1. PUBLIC VIEWS
    // ==========================================

    public function landingPage(): View
    {
        $lapangans = Lapangan::all();
        return view('welcome', compact('lapangans'));
    }

    public function showLapangan(Request $request, int $id): View
    {
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());
        $jam_terpesan = $this->getJamTerpesan($id, $tanggal_pilihan);

        return view('admin.lapangan.detail', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan'));
    }

    public function create(Request $request, int $id): View
    {
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());
        $jam_terpesan = $this->getJamTerpesan($id, $tanggal_pilihan);

        $user = Auth::user();
        $membershipType = optional($user->membership)->membership_type ?? 'Bronze';
        $diskonPersen = $this->getDiskonPersen($membershipType);

        return view('reservasi.create', compact('lapangan', 'tanggal_pilihan', 'jam_terpesan', 'membershipType', 'diskonPersen'));
    }

    public function dashboard(): View
    {
        $user = Auth::user();

        $reservasis = Reservasi::where('user_id', $user->id)
            ->with('lapangan')
            ->latest()
            ->get();

        $membership = $user->membership ?? (object) [
            'membership_type' => 'Bronze',
            'points'          => 0,
        ];

        $totalBooking     = $reservasis->count();
        $lunasBooking     = $reservasis->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])->count();
        $totalPengeluaran = $reservasis->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])->sum('total_harga');

        return view('dashboard', compact(
            'reservasis', 'membership', 'totalBooking', 'lunasBooking', 'totalPengeluaran'
        ));
    }

    // ==========================================
    // 2. PROSES PEMBUATAN RESERVASI & MIDTRANS
    // ==========================================

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'lapangan_id'  => 'required|exists:lapangans,id',
            'tanggal_main' => 'required|date|after_or_equal:today',
            'jam_mulai'    => 'required|integer|between:' . self::JAM_BUKA . ',' . (self::JAM_TUTUP - 1),
            'durasi'       => 'required|integer|between:1,3',
        ], [
            'lapangan_id.required'        => 'Lapangan tidak valid.',
            'lapangan_id.exists'          => 'Data lapangan tidak ditemukan.',
            'tanggal_main.required'       => 'Tanggal main wajib diisi.',
            'tanggal_main.date'           => 'Format tanggal tidak valid.',
            'tanggal_main.after_or_equal' => 'Tanggal main tidak boleh sebelum hari ini.',
            'jam_mulai.required'          => 'Jam mulai wajib dipilih.',
            'jam_mulai.between'           => 'Jam mulai harus antara pukul ' . self::JAM_BUKA . '.00 - ' . (self::JAM_TUTUP - 1) . '.00.',
            'durasi.required'             => 'Durasi wajib dipilih.',
            'durasi.between'              => 'Durasi hanya boleh 1-3 jam.',
        ]);

        $start_hour = (int) $request->jam_mulai;
        $end_hour = $start_hour + (int) $request->durasi;
        $tanggal = Carbon::parse($request->tanggal_main);

        // Validasi waktu berlalu hari ini
        if ($tanggal->isToday() && $start_hour <= (int) now()->format('H')) {
            return response()->json([
                'success' => false,
                'message' => 'Jam yang Anda pilih sudah lewat untuk hari ini.'
            ], 422);
        }

        // Validasi batas jam tutup operasional
        if ($end_hour > self::JAM_TUTUP) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal bermain melebihi jam operasional (maksimal pukul ' . self::JAM_TUTUP . ':00).'
            ], 422);
        }

        $start_time = sprintf('%02d:00:00', $start_hour);
        $end_time = sprintf('%02d:00:00', $end_hour);
        $user = Auth::user();

        try {
            $reservasi = DB::transaction(function () use ($request, $tanggal, $start_hour, $end_hour, $start_time, $end_time, $user) {
                // Lock data lapangan & reservasi terkait untuk hindari Race Condition
                $lapangan = Lapangan::where('id', $request->lapangan_id)->lockForUpdate()->firstOrFail();

                $bentrok = Reservasi::where('lapangan_id', $request->lapangan_id)
                    ->where('tanggal_main', $request->tanggal_main)
                    ->where(function ($q) {
                        $q->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])
                          ->orWhere(function ($q2) {
                              $q2->where('status', self::STATUS_WAITING)
                                 ->where('expired_at', '>', now());
                          });
                    })
                    // Formula Overlap Jam: (Start_DB < End_Input) AND (End_DB > Start_Input)
                    ->where('jam_mulai', '<', $end_time)
                    ->where('jam_selesai', '>', $start_time)
                    ->lockForUpdate()
                    ->exists();

                if ($bentrok) {
                    throw new Exception('SLOT_BENTROK');
                }

                // Hitung total biaya & diskon
                $subtotal = $this->hitungSubtotal($lapangan->harga_per_jam, $start_hour, $end_hour, $tanggal);
                $membershipType = optional($user->membership)->membership_type ?? 'Bronze';
                $diskonPersen = $this->getDiskonPersen($membershipType);
                $nominalDiskon = (int) round($subtotal * $diskonPersen / 100);
                $total_harga = max(0, $subtotal - $nominalDiskon);

                $nomor_reservasi = $this->generateNomorReservasiUnik();

                return Reservasi::create([
                    'user_id'                 => $user->id,
                    'lapangan_id'             => $request->lapangan_id,
                    'nomor_reservasi'          => $nomor_reservasi,
                    'tanggal_main'            => $request->tanggal_main,
                    'jam_mulai'               => $start_time,
                    'jam_selesai'             => $end_time,
                    'subtotal_sebelum_diskon' => $subtotal,
                    'diskon_persen'           => $diskonPersen,
                    'total_harga'             => $total_harga,
                    'status'                  => self::STATUS_WAITING,
                    'expired_at'              => now()->addMinutes(self::MENIT_KEDALUARSA_PEMBAYARAN),
                ]);
            });

            // Sesi Snap Midtrans
            $snapToken = $this->requestMidtransSnapToken($reservasi, $user);

            $reservasi->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'         => true,
                'snap_token'      => $snapToken,
                'nomor_reservasi' => $reservasi->nomor_reservasi,
                'redirect'        => route('dashboard'),
            ]);

        } catch (Exception $e) {
            if ($e->getMessage() === 'SLOT_BENTROK') {
                return response()->json([
                    'success' => false,
                    'message' => 'Slot jam tersebut baru saja dipesan orang lain, silakan pilih jam lain.'
                ], 422);
            }

            Log::error('Gagal memproses pembuatan reservasi: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat sesi pembayaran, silakan coba beberapa saat lagi.'
            ], 500);
        }
    }

    // ==========================================
    // 3. MIDTRANS CALLBACK & STATUS SYNC
    // ==========================================

    public function handleNotification(Request $request): JsonResponse
    {
        $request->validate([
            'order_id'           => 'required|string',
            'status_code'        => 'required|string',
            'gross_amount'       => 'required|string',
            'signature_key'      => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if (!hash_equals($hashed, $request->signature_key)) {
            Log::warning('Signature Midtrans tidak valid untuk order ' . $request->order_id);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $reservasi = Reservasi::where('nomor_reservasi', $request->order_id)->first();

        if (!$reservasi) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $gross_amount_int = (int) round((float) $request->gross_amount);
        if ($gross_amount_int !== (int) $reservasi->total_harga) {
            Log::error("Nominal webhook mismatch order {$request->order_id}: dikirim {$gross_amount_int}, tercatat {$reservasi->total_harga}");
            return response()->json(['message' => 'Nominal tidak cocok'], 422);
        }

        DB::transaction(function () use ($request, $reservasi) {
            // Re-lock reservasi dalam transaksi
            $reservasiLocked = Reservasi::where('id', $reservasi->id)->lockForUpdate()->first();
            $status = $request->transaction_status;

            if (in_array($status, ['settlement', 'capture'])) {
                $this->konfirmasiPembayaranSukses($reservasiLocked, $request->payment_type);
            } elseif (in_array($status, ['cancel', 'expire', 'deny'])) {
                if ($reservasiLocked->status === self::STATUS_WAITING) {
                    $reservasiLocked->update(['status' => self::STATUS_CANCELLED]);
                }
            }
        });

        return response()->json(['message' => 'Callback berhasil diproses']);
    }

    public function confirmPayment(string $nomor_reservasi): JsonResponse
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return response()->json(['success' => false, 'message' => 'Reservasi tidak ditemukan.'], 404);
        }

        if (in_array($reservasi->status, [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])) {
            return response()->json(['success' => true, 'status' => $reservasi->status]);
        }

        try {
            $baseUrl = config('services.midtrans.is_production', false) 
                ? 'https://api.midtrans.com/v2/' 
                : 'https://api.sandbox.midtrans.com/v2/';

            $response = Http::withHeaders($this->midtransHeaders())
                ->timeout(8)
                ->get($baseUrl . "{$reservasi->nomor_reservasi}/status");

            if ($response->failed()) {
                Log::warning("Gagal cek status Midtrans untuk {$reservasi->nomor_reservasi}: " . $response->body());
                return response()->json([
                    'success' => false,
                    'status'  => $reservasi->status,
                    'message' => 'Status pembayaran sedang diverifikasi.',
                ], 202);
            }

            $data = $response->json();
            $transactionStatus = $data['transaction_status'] ?? null;

            $gross_amount_int = isset($data['gross_amount']) ? (int) round((float) $data['gross_amount']) : null;
            if ($gross_amount_int !== null && $gross_amount_int !== (int) $reservasi->total_harga) {
                Log::error("Mismatch nominal pada order {$reservasi->nomor_reservasi}: dilaporkan {$gross_amount_int}, tercatat {$reservasi->total_harga}");
                return response()->json(['success' => false, 'status' => $reservasi->status, 'message' => 'Verifikasi nominal pembayaran gagal.'], 422);
            }

            DB::transaction(function () use ($transactionStatus, $reservasi, $data) {
                $reservasiLocked = Reservasi::where('id', $reservasi->id)->lockForUpdate()->first();

                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    $this->konfirmasiPembayaranSukses($reservasiLocked, $data['payment_type'] ?? null);
                } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                    if ($reservasiLocked->status === self::STATUS_WAITING) {
                        $reservasiLocked->update(['status' => self::STATUS_CANCELLED]);
                    }
                }
            });

            $statusAkhir = $reservasi->fresh()->status;

            if ($statusAkhir === self::STATUS_CONFIRMED) {
                session()->flash('success', 'Pembayaran berhasil dikonfirmasi! Jadwal Anda telah terkonfirmasi.');
            } elseif ($statusAkhir === self::STATUS_CANCELLED && $transactionStatus !== null) {
                session()->flash('error', 'Transaksi dibatalkan atau kadaluarsa.');
            }

            return response()->json(['success' => true, 'status' => $statusAkhir]);

        } catch (Exception $e) {
            Log::error("Gagal konfirmasi instan pembayaran {$reservasi->nomor_reservasi}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'status'  => $reservasi->status,
                'message' => 'Terjadi kesalahan sistem saat memverifikasi pembayaran.',
            ], 500);
        }
    }

    // ==========================================
    // 4. PEMBATALAN, HAPUS RIWAYAT & CHECK-IN
    // ==========================================

    public function cancelPendingInstant(Request $request, string $nomor_reservasi): JsonResponse
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return response()->json(['success' => false, 'message' => 'Reservasi tidak ditemukan.'], 404);
        }

        if ($reservasi->status === self::STATUS_WAITING) {
            $this->cancelMidtransOrder($reservasi->nomor_reservasi);
            $reservasi->update(['status' => self::STATUS_CANCELLED]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi dibatalkan, slot jam telah dilepas.',
            ]);
        }

        return response()->json([
            'success'           => true,
            'already_confirmed' => in_array($reservasi->status, [self::STATUS_CONFIRMED, self::STATUS_COMPLETED]),
            'status'            => $reservasi->status,
        ]);
    }

    public function batalkanReservasi(int $id): RedirectResponse
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Data reservasi tidak ditemukan.');
        }

        if ($reservasi->status !== self::STATUS_WAITING) {
            return redirect()->route('dashboard')->with('error', 'Reservasi tidak dapat dibatalkan karena statusnya sudah: ' . $reservasi->status);
        }

        $this->cancelMidtransOrder($reservasi->nomor_reservasi);
        $reservasi->update(['status' => self::STATUS_CANCELLED]);

        return redirect()->route('dashboard')->with('success', 'Jadwal reservasi Anda berhasil dibatalkan.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED, self::STATUS_CANCELLED])
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Riwayat transaksi tidak ditemukan atau masih menunggu pembayaran.');
        }

        $reservasi->delete();
        return redirect()->route('dashboard')->with('success', 'Riwayat transaksi berhasil dihapus.');
    }

    public function destroyMassal(Request $request): RedirectResponse
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:reservasis,id',
        ], [
            'ids.required' => 'Pilih minimal satu riwayat transaksi untuk dihapus.',
            'ids.*.exists' => 'Salah satu transaksi yang dipilih tidak ditemukan.',
        ]);

        $deleted = Reservasi::whereIn('id', $request->ids)
            ->where('user_id', Auth::id())
            ->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED, self::STATUS_CANCELLED])
            ->delete();

        if ($deleted === 0) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada riwayat transaksi valid yang dapat dihapus.');
        }

        return redirect()->route('dashboard')->with('success', 'Riwayat transaksi terpilih berhasil dihapus.');
    }

    public function cetakTiket(int $id): View|RedirectResponse
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])
            ->first();

        if (!$reservasi) {
            return redirect()->route('dashboard')->with('error', 'Tiket tidak ditemukan atau belum lunas.');
        }

        // Render QR SVG inline tanpa menulis ke disk
        $qrCodeSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($reservasi->nomor_reservasi);

            Storage::disk('public')->put($relative_path, $svg);
            $reservasi->update(['qr_code_path' => $nama_file]);
        }

        $qrUrl = Storage::disk('public')->url($relative_path);
        $qrCodeSvg = Storage::disk('public')->get($relative_path);

        return view('reservasi.tiket', compact('reservasi', 'qrUrl', 'qrCodeSvg'));
    }

    public function processStaffCheckIn(Request $request): JsonResponse
    {
        $request->validate([
            'nomor_reservasi' => 'required|string',
        ], [
            'nomor_reservasi.required' => 'Kode QR tidak terbaca, silakan pindai ulang.',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $reservasi = Reservasi::with('user')
                    ->where('nomor_reservasi', $request->nomor_reservasi)
                    ->lockForUpdate()
                    ->first();

                if (!$reservasi) {
                    return response()->json(['success' => false, 'message' => 'Kode QR tidak valid!'], 404);
                }

                if ($reservasi->status === self::STATUS_CANCELLED) {
                    return response()->json(['success' => false, 'message' => 'Tiket ditolak! Reservasi ini telah dibatalkan.'], 422);
                }

                if ($reservasi->status === self::STATUS_WAITING) {
                    return response()->json(['success' => false, 'message' => 'Tiket ditolak! Tagihan belum dilunasi.'], 422);
                }

                if ($reservasi->status === self::STATUS_COMPLETED) {
                    return response()->json(['success' => false, 'message' => 'Peringatan! Tiket ini sudah pernah digunakan.'], 422);
                }

                if ($reservasi->status === self::STATUS_CONFIRMED) {
                    $reservasi->update(['status' => self::STATUS_COMPLETED]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Verifikasi berhasil! Selamat bertanding tim ' . ($reservasi->user->name ?? 'Pelanggan') . '.'
                    ], 200);
                }

                return response()->json(['success' => false, 'message' => 'Status transaksi tidak valid: ' . $reservasi->status], 400);
            });

        } catch (Exception $e) {
            Log::error('Gagal memproses Gate Check-In: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem internal.'], 500);
        }
    }

    public function checkStatus(string $nomor_reservasi): JsonResponse
    {
        $reservasi = Reservasi::where('nomor_reservasi', $nomor_reservasi)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json(['status' => $reservasi->status]);
    }

    // ==========================================
    // 5. HELPER METHODS (PRIVATE)
    // ==========================================

    private function getDiskonPersen(?string $membershipType): int
    {
        return match ($membershipType) {
            'Gold'   => 10,
            'Silver' => 5,
            default  => 0,
        };
    }

    private function getJamTerpesan(int $lapangan_id, string $tanggal): array
    {
        $jam_terpesan = Reservasi::where('lapangan_id', $lapangan_id)
            ->where('tanggal_main', $tanggal)
            ->where(function ($q) {
                $q->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])
                  ->orWhere(function ($q2) {
                      $q2->where('status', self::STATUS_WAITING)
                         ->where('expired_at', '>', now());
                  });
            })
            ->get(['jam_mulai', 'jam_selesai'])
            ->flatMap(function ($booking) {
                $mulai = (int) substr($booking->jam_mulai, 0, 2);
                $selesai = (int) substr($booking->jam_selesai, 0, 2);
                return range($mulai, $selesai - 1);
            })
            ->unique()
            ->toArray();

        if (Carbon::parse($tanggal)->isToday()) {
            $jamSekarang = (int) now()->format('H');
            for ($jam = self::JAM_BUKA; $jam <= $jamSekarang; $jam++) {
                if (!in_array($jam, $jam_terpesan)) {
                    $jam_terpesan[] = $jam;
                }
            }
        }

        sort($jam_terpesan);
        return array_values($jam_terpesan);
    }

    private function hitungSubtotal(int $biayaDasar, int $startHour, int $endHour, Carbon $tanggal): int
    {
        $subtotal = 0;
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $hargaSlot = $biayaDasar;
            if ($hour >= 16 && $hour < self::JAM_TUTUP) {
                $hargaSlot += 50000; // Peak hours / jam malam
            }
            if ($tanggal->isWeekend()) {
                $hargaSlot += 20000; // Surcharge weekend
            }
            $subtotal += $hargaSlot;
        }
        return $subtotal;
    }

    private function requestMidtransSnapToken(Reservasi $reservasi, $user): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => (string) $reservasi->nomor_reservasi,
                'gross_amount' => (int) $reservasi->total_harga,
            ],
            'customer_details' => [
                'first_name' => (string) $user->name,
                'email'      => (string) $user->email,
            ],
            'expiry' => [
                'unit'     => 'minute',
                'duration' => self::MENIT_KEDALUARSA_PEMBAYARAN,
            ],
        ];

        $baseUrl = config('services.midtrans.is_production', false) 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $response = Http::withHeaders($this->midtransHeaders())
            ->timeout(10)
            ->post($baseUrl, $params);

        if ($response->failed()) {
            $reservasi->update(['status' => self::STATUS_CANCELLED]);
            throw new Exception('Midtrans HTTP Error: ' . $response->body());
        }

        $snapToken = $response->json('token');

        if (!$snapToken) {
            $reservasi->update(['status' => self::STATUS_CANCELLED]);
            throw new Exception('Snap Token tidak ditemukan dalam respon Midtrans.');
        }

        return $snapToken;
    }

    private function konfirmasiPembayaranSukses(Reservasi $reservasi, ?string $paymentType = null): void
    {
        if (in_array($reservasi->status, [self::STATUS_CONFIRMED, self::STATUS_COMPLETED])) {
            return;
        }

        $reservasi->update([
            'status'            => self::STATUS_CONFIRMED,
            'metode_pembayaran' => $paymentType,
            'expired_at'        => null,
        ]);

        $membership = Membership::firstOrCreate(
            ['user_id' => $reservasi->user_id],
            ['membership_type' => 'Bronze', 'points' => 0]
        );

        $poinBaru = (int) floor($reservasi->total_harga / 10000);
        $totalPoinAkhir = $membership->points + $poinBaru;

        $tierEvaluasi = match (true) {
            $totalPoinAkhir >= 300 => 'Gold',
            $totalPoinAkhir >= 100 => 'Silver',
            default                 => 'Bronze',
        };

        $membership->update([
            'points'          => $totalPoinAkhir,
            'membership_type' => $tierEvaluasi,
        ]);
    }

    private function generateNomorReservasiUnik(int $maxAttempts = 5): string
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            $kandidat = 'FM-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -5));

            if (!Reservasi::where('nomor_reservasi', $kandidat)->exists()) {
                return $kandidat;
            }
        }

        return 'FM-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }

    private function midtransHeaders(): array
    {
        $serverKey = config('services.midtrans.server_key');
        return [
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
        ];
    }

    private function cancelMidtransOrder(string $nomor_reservasi): bool
    {
        try {
            $baseUrl = config('services.midtrans.is_production', false) 
                ? 'https://api.midtrans.com/v2/' 
                : 'https://api.sandbox.midtrans.com/v2/';

            $response = Http::withHeaders($this->midtransHeaders())
                ->timeout(5)
                ->post($baseUrl . "{$nomor_reservasi}/cancel");

            return $response->successful();
        } catch (Exception $e) {
            Log::warning("Gagal koneksi ke Midtrans untuk pembatalan order {$nomor_reservasi}: " . $e->getMessage());
            return false;
        }
    }
}