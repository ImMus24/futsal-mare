<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};

class AdminDashboardController extends Controller
{
    /**
     * MODUL 0: AUTENTIKASI ADMIN
     */
    public function showLoginForm()
    {
        // Mengecek is_admin == 1 sesuai database Anda
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek is_admin == 1
            if (Auth::user()->is_admin == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang Admin!');
            }

            Auth::logout();
            return back()->with('error', 'Anda tidak memiliki akses sebagai admin.');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    /**
     * MODUL 1: DASHBOARD OVERVIEW
     */
    public function index()
    {
        $totalPendapatan = Reservasi::whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        $matchTerkonfirmasi = Reservasi::where('status', 'Confirmed')->count();
        $totalMember = User::has('membership')->count();

        $reservasis = Reservasi::with(['lapangan', 'user.membership'])
                            ->latest()
                            ->paginate(10);

        // Menggunakan array eksplisit untuk menghindari TypeError: array_merge
        return view('admin.dashboard', [
            'totalPendapatan'    => $totalPendapatan,
            'matchTerkonfirmasi' => $matchTerkonfirmasi,
            'totalMember'        => $totalMember,
            'reservasis'         => $reservasis
        ]);
    }

    /**
     * MODUL 2: LOG RESERVASI
     */
    public function reservasi(Request $request)
    {
        $query = Reservasi::with(['lapangan', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservasis = $query->latest()->paginate(15);
        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function exportExcel(Request $request)
    {
        $query = Reservasi::with(['lapangan', 'user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservasis = $query->latest()->get();

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Laporan_Reservasi_" . date('Ymd_His') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('admin.reservasi.excel', compact('reservasis'));
    }

    /**
     * MODUL 3: KELOLA ARENA
     */
    public function lapangan()
    {
        $lapangans = Lapangan::all();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    /**
     * MODUL 4: DATA MEMBER
     */
    public function member()
    {
        $members = User::select('users.*', 'memberships.points as total_points')
            ->leftJoin('memberships', 'users.id', '=', 'memberships.user_id')
            ->orderByRaw('total_points IS NULL, total_points DESC')
            ->paginate(10);

        return view('admin.member.index', compact('members'));
    }

    /**
     * MODUL 5: MANAJEMEN ROLE (is_admin)
     */
    public function role(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->paginate(10);
        return view('admin.role.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        // Update validasi untuk is_admin (0 atau 1)
        $request->validate([
            'is_admin' => 'required|in:0,1',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akses sendiri!');
        }

        try {
            DB::transaction(function () use ($user, $request) {
                $user->update(['is_admin' => $request->is_admin]);
            });
            return back()->with('success', "Status akses {$user->name} berhasil diperbarui.");
        } catch (\Exception $e) {
            Log::error("Gagal update role: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }
}