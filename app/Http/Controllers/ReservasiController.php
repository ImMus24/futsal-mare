<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservasiRequest;
use App\Models\Lapangan;
use App\Models\Reservasi;
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

    // Helper untuk mendapatkan jam terpesan
    private function getJamTerpesan($lapangan_id, $tanggal)
    {
        return Reservasi::where('lapangan_id', $lapangan_id)
            ->where('tanggal_main', $tanggal)
            ->where(function ($q) {
                $q->whereIn('status', ['Confirmed', 'Completed'])
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Waiting Payment')
                         ->where('created_at', '>', now()->subMinutes(self::MENIT_KEDALUARSA_PEMBAYARAN));
                  });
            })
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($booking) {
                $mulai = (int) substr($booking->jam_mulai, 0, 2);
                $selesai = (int) substr($booking->jam_selesai, 0, 2);
                return range($mulai, $selesai - 1);
            })->flatten()->toArray();
    }

    public function landingPage()
    {
        return view('welcome', ['lapangans' => Lapangan::all()]);
    }

    public function showLapangan(Request $request, $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());
        return view('lapangan.detail', [
            'lapangan' => $lapangan,
            'tanggal_pilihan' => $tanggal_pilihan,
            'jam_terpesan' => $this->getJamTerpesan($id, $tanggal_pilihan)
        ]);
    }

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
        // ... (gunakan kode store yang sudah kita perbaiki sebelumnya)
    }

    public function handleNotification(Request $request)
    {
        // ... (kode handleNotification Anda)
    }

    public function dashboard()
    {
        // ... (kode dashboard Anda)
    }

    public function batalkanReservasi($id)
    {
        // ... (kode pembatalan Anda)
    }

    public function destroy($id)
    {
        // ... (kode hapus Anda)
    }

    public function destroyMassal(Request $request)
    {
        // ... (kode hapus massal Anda)
    }

    public function cetakTiket($id)
    {
        // ... (kode tiket Anda)
    }

    public function processStaffCheckIn(Request $request)
    {
        $reservasi = Reservasi::where('nomor_reservasi', $request->nomor_reservasi)->firstOrFail();
        
        if ($reservasi->status !== 'Confirmed') {
            return response()->json(['success' => false, 'message' => 'Tiket tidak valid atau belum lunas.'], 422);
        }

        $reservasi->update(['status' => 'Completed']);
        return response()->json(['success' => true, 'message' => 'Check-in berhasil!']);
    }
}