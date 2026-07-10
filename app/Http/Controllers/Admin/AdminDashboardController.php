<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Modul 1: Menampilkan halaman utama Dashboard Admin Futsal Mare (Overview).
     */
    public function index()
    {
        // Ambil data statistik untuk metrik ringkasan (Stat Cards)
        $totalPendapatan = Reservasi::whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        $matchTerkonfirmasi = Reservasi::where('status', 'Confirmed')->count();
        $totalMember = User::has('membership')->count();

        // Ambil data reservasi terbaru untuk tabel utama (Real-time Monitoring)
        $reservasis = Reservasi::with(['lapangan', 'user.membership'])
                                ->latest()
                                ->paginate(10);

        return view('admin.dashboard', compact(
            'totalPendapatan', 
            'matchTerkonfirmasi', 
            'totalMember', 
            'reservasis'
        ));
    }

    /**
     * Modul 2: Log Data Reservasi Lengkap (Terhubung dengan Filter Status)
     */
    public function reservasi(Request $request)
    {
        $status = $request->get('status');
        
        // Membuka query dasar dengan eager loading relasi lapangan dan user
        $query = Reservasi::with(['lapangan', 'user']);

        // Jika ada filter status yang dipilih
        if ($status && $status != '') {
            $query->where('status', $status);
        }

        // Ambil data terbaru dengan pembatasan 15 baris per halaman
        $reservasis = $query->latest()->paginate(15);

        return view('admin.reservasi.index', compact('reservasis'));
    }

    /**
     * Modul 2b: Fitur Ekspor Excel Data Reservasi Dinamis
     */
    public function exportExcel(Request $request)
    {
        $status = $request->get('status');
        
        $query = Reservasi::with(['lapangan', 'user']);

        // Menyesuaikan data ekspor dengan filter status yang sedang aktif di halaman admin
        if ($status && $status != '') {
            $query->where('status', $status);
        }

        $reservasis = $query->latest()->get();

        // Mengatur header agar browser mendownload file sebagai Excel .xls asli
        $filename = "Laporan_Reservasi_Futsal_Mare_" . date('Ymd_His') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('admin.reservasi.excel', compact('reservasis'));
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Confirmed,Waiting Payment,Completed,Cancelled'
    ]);

    $reservasi = Reservasi::findOrFail($id);
    $reservasi->update([
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', "Status nota transaksi {$reservasi->nomor_reservasi} berhasil diperbarui!");
}

/**
 * Fitur Aksi 2: Menghapus Data Reservasi dari Log Sistem
 */
public function deleteReservasi($id)
{
    $reservasi = Reservasi::findOrFail($id);
    $reservasi->delete();

    return redirect()->back()->with('success', "Record data log reservasi berhasil dihapus dari sistem.");
}
    /**
     * Modul 3: Kelola Arena Lapangan (Mengambil Seluruh Inventaris Lapangan)
     */
    public function lapangan()
    {
        $lapangans = Lapangan::all();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    /**
     * Modul 4: Database & Tingkat Loyalitas Member Futsal Mare
     */
    public function member(Request $request)
{
    // Mengambil user terdaftar beserta poin yang di-join dari relasi memberships
    $members = User::with('membership')
        ->leftJoin('memberships', 'users.id', '=', 'memberships.user_id')
        ->select('users.*', 'memberships.points as total_points')
        ->orderBy('total_points', 'desc')
        ->paginate(10);

    return view('admin.member.index', compact('members'));
}

/**
 * Form Edit Member / Poin
 */
public function editMember($id)
{
    $member = User::with('membership')->findOrFail($id);
    return view('admin.member.edit', compact('member'));
}

/**
 * Eksekusi Update Data & Poin Member
 */
public function updateMember(Request $request, $id)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'points' => 'required|integer|min:0',
    ]);

    $member = User::findOrFail($id);
    $member->update([
        'name' => $request->name,
    ]);

    // Update atau buat data poin baru di tabel memberships secara otomatis
    $tierEvaluasi = 'Bronze';
    if ($request->points >= 300) {
        $tierEvaluasi = 'Gold';
    } elseif ($request->points >= 100) {
        $tierEvaluasi = 'Silver';
    }

    $member->membership()->updateOrCreate(
        ['user_id' => $member->id],
        [
            'points' => $request->points,
            'membership_type' => $tierEvaluasi
        ]
    );

    return redirect()->route('admin.member.index')->with('success', "Data poin loyalitas member {$member->name} berhasil diperbarui!");
}}