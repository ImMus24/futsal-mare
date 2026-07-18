<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard member: ringkasan poin membership,
     * metrik reservasi, dan riwayat transaksi milik user yang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil seluruh riwayat reservasi milik user ini beserta relasi lapangan,
        // supaya $reservasi->lapangan tidak memicu query N+1 di view.
        $reservasis = $user->reservasis()
            ->with('lapangan')
            ->latest()
            ->get();

        // Data membership — fallback ke Bronze/0 poin jika user belum
        // punya baris membership sama sekali (mis. akun baru).
        $membership = $user->membership ?? (object) [
            'membership_type' => 'Bronze',
            'points' => 0,
        ];

        // Metrik ringkasan dihitung dari koleksi yang sama supaya konsisten
        // dengan daftar riwayat yang ditampilkan di tabel bawahnya.
        $totalBooking = $reservasis->count();
        $lunasBooking = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->count();
        $totalPengeluaran = $reservasis->whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');

        return view('member.dashboard', compact(
            'reservasis',
            'membership',
            'totalBooking',
            'lunasBooking',
            'totalPengeluaran'
        ));
    }
}