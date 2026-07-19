<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};

class AdminDashboardController extends Controller
{
    /**
     * MODUL 0: AUTENTIKASI ADMIN
     */
    public function showLoginForm()
    {
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
        $reservasis = Reservasi::with(['lapangan', 'user.membership'])->latest()->paginate(10);

        return view('admin.dashboard', compact('totalPendapatan', 'matchTerkonfirmasi', 'totalMember', 'reservasis'));
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Confirmed,Waiting Payment,Completed,Cancelled']);
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => $request->status]);
        return back()->with('success', "Status reservasi #{$reservasi->id} berhasil diubah.");
    }

    public function deleteReservasi($id)
    {
        Reservasi::findOrFail($id)->delete();
        return redirect()->route('admin.reservasi.index')->with('success', 'Data reservasi berhasil dihapus.');
    }

    public function deleteReservasiMassal(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $jumlah = Reservasi::whereIn('id', $request->ids)->count();
        Reservasi::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.reservasi.index')->with('success', $jumlah . ' data berhasil dihapus.');
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

    // TAMBAHAN: Method agar route('admin.member.edit') bisa berjalan
    public function editMember($id)
    {
        $member = User::with('membership')->findOrFail($id);
        return view('admin.member.edit', compact('member'));
    }

    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
        ]);

        $member = User::findOrFail($id);
        DB::transaction(function () use ($member, $request) {
            $member->update(['name' => $request->name]);
            $membership = Membership::firstOrCreate(['user_id' => $member->id]);
            
            $tierBaru = $request->points >= 300 ? 'Gold' : ($request->points >= 100 ? 'Silver' : 'Bronze');
            
            $membership->update([
                'points' => $request->points,
                'membership_type' => $tierBaru,
            ]);
        });

        return redirect()->route('admin.member.index')->with('success', "Data member \"{$member->name}\" berhasil diperbarui.");
    }

    public function deleteMember($id)
    {
        $member = User::findOrFail($id);
        if ($member->id === Auth::id()) return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        
        $member->membership()->delete();
        $member->delete();
        return redirect()->route('admin.member.index')->with('success', "Member \"{$member->name}\" berhasil dihapus.");
    }

    /**
     * MODUL 5: MANAJEMEN ROLE
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
        $request->validate(['is_admin' => 'required|in:0,1']);
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akses sendiri!');
        }

        $user->update(['is_admin' => $request->is_admin]);
        return back()->with('success', "Status akses {$user->name} berhasil diperbarui.");
    }
}