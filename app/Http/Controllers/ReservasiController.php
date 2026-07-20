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

class ReservasiController extends Controller
{
    const MENIT_KEDALUARSA_PEMBAYARAN = 10;

    /**
     * Helper untuk mendapatkan jam terpesan yang valid
     */
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
        $lapangan = Lapangan::findOrFail($id);
        $tanggal_pilihan = $request->get('tanggal_main', Carbon::today()->toDateString());

        return view('reservasi.create', [
            'lapangan' => $lapangan,
            'tanggal_pilihan' => $tanggal_pilihan,
            'jam_terpesan' => $this->getJamTerpesan($id, $tanggal_pilihan)
        ]);
    }

    // Metode lainnya (store, dashboard, dll) tetap di sini...
    // Pastikan tidak ada duplikasi nama method di bawah ini.
}