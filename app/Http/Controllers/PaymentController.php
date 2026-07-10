<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Membership;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Fungsi Webhook Handler (Daftarkan URL ini di Dashboard Midtrans)
    public function handleWebhook(Request $request)
    {
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id; // Ini memuat Nomor Reservasi Anda (FM-XXXXX)

        $reservasi = Reservasi::where('nomor_reservasi', $order_id)->first();

        if (!$reservasi) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Logika Otomatisasi Update Status
        if ($transaction == 'settlement') {
            $reservasi->update(['status' => 'Confirmed']);

            // Inject Poin & Update Tingkat Loyalitas Pelanggan
            $membership = Membership::firstOrCreate(['user_id' => $reservasi->user_id]);
            $membership->increment('points', 10);

            // Otomatisasi Naik Level berdasarkan Poin
            if ($membership->points >= 300) {
                $membership->update(['membership_type' => 'Gold']);
            } elseif ($membership->points >= 100) {
                $membership->update(['membership_type' => 'Silver']);
            }

        } else if ($transaction == 'pending') {
            $reservasi->update(['status' => 'Waiting Payment']);
        } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $reservasi->update(['status' => 'Cancelled']);
        }

        return response()->json(['message' => 'Webhook Berhasil Diproses']);
    }
}