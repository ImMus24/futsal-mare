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
     * ==========================================
     * 🛡️ MODUL KHUSUS: AUTENTIKASI PORTAL ADMIN
     * ==========================================
     */

    /**
     * Menampilkan Form Login Khusus Portal Admin
     */
    public function showLoginForm()
    {
        // Jika sudah login dan memiliki status admin, langsung bypass ke dashboard
        if (auth()->check() && auth()->user()->is_admin) { 
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Memproses Autentikasi Login Masuk Admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Mencoba mencocokkan kredensial dengan database
        if (auth()->attempt($credentials, $request->remember)) {
            // Validasi hak akses flag admin di tabel user
            if (auth()->user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Kembali di Panel Kontrol Utama!');
            }

            // Jika berhasil masuk tapi bukan admin, paksa keluar sesi demi keamanan
            auth()->logout();
            return redirect()->back()->withErrors(['email' => 'Akses Ditolak. Akun Anda tidak memiliki otoritas Administrator.'])->withInput();
        }

        return redirect()->back()->withErrors(['email' => 'Kredensial atau kata sandi yang Anda masukkan salah.'])->withInput();
    }

    /**
     * ==========================================
     * 📊 MODUL 1: DASHBOARD OVERVIEW ADMIN
     * ==========================================
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
     * ==========================================
     * 📅 MODUL 2: LOG & PENGELOLAAN RESERVASI
     * ==========================================
     */
    public function reservasi(Request $request)
    {
        $status = $request->get('status');
        
        $query = Reservasi::with(['lapangan', 'user']);

        if ($status && $status != '') {
            $query->where('status', $status);
        }

        $reservasis = $query->latest()->paginate(15);

        return view('admin.reservasi.index', compact('reservasis'));
    }

    /**
     * Fitur Ekspor Excel Data Reservasi Dinamis
     */
    public function exportExcel(Request $request)
    {
        $status = $request->get('status');
        
        $query = Reservasi::with(['lapangan', 'user']);

        if ($status && $status != '') {
            $query->where('status', $status);
        }

        $reservasis = $query->latest()->get();

        $filename = "Laporan_Reservasi_Futsal_Mare_" . date('Ymd_His') . ".xls";
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('admin.reservasi.excel', compact('reservasis'));
    }

    /**
     * Mengubah Status Reservasi secara Manual
     */
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
     * Menghapus Data Reservasi dari Log Sistem
     */
    public function deleteReservasi($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return redirect()->back()->with('success', "Record data log reservasi berhasil dihapus dari sistem.");
    }

    /**
     * ==========================================
     * 🌱 MODUL 3: KELOLA ARENA LAPANGAN
     * ==========================================
     */
    public function lapangan()
    {
        $lapangans = Lapangan::all();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    /**
     * ==========================================
     * 👥 MODUL 4: LOYALITAS & DATA MEMBER
     * ==========================================
     */
    public function member(Request $request)
    {
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
    }
}