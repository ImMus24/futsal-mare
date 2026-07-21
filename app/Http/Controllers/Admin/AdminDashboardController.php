<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Membership;
use Carbon\Carbon;
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
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
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
        // 1. Ringkasan Statistik
        $totalPendapatan = Reservasi::whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        $matchTerkonfirmasi = Reservasi::where('status', 'Confirmed')->count();
        $totalMember = User::has('membership')->count();
        $reservasis = Reservasi::with(['lapangan', 'user.membership'])->latest()->paginate(10);

        // 2. Kalkulasi Data Grafik Utilisasi 7 Hari Terakhir
        $labelUtilisasi = [];
        $dataUtilisasi = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Format label tanggal (Contoh: "21 Jul")
            $labelUtilisasi[] = $date->translatedFormat('d M'); 

            // Hitung total durasi jam (atau total reservasi) per hari
            // Catatan: Jika di database tidak ada kolom 'durasi', kita hitung selisih jam atau jumlah transaksi
            $totalJam = Reservasi::whereDate('tanggal_main', $date->toDateString())
                ->whereIn('status', ['Confirmed', 'Completed'])
                ->get()
                ->sum(function ($reservasi) {
                    // Mengambil selisih jam_mulai dan jam_selesai secara dinamis
                    if (!empty($reservasi->jam_mulai) && !empty($reservasi->jam_selesai)) {
                        $start = Carbon::parse($reservasi->jam_mulai);
                        $end = Carbon::parse($reservasi->jam_selesai);
                        return $start->diffInHours($end);
                    }
                    return 1; // Default 1 jam jika jam tidak terdefinisi
                });

            $dataUtilisasi[] = (int) $totalJam;
        }

        // 3. Pass data ke view 'admin.dashboard'
        return view('admin.dashboard', compact(
            'totalPendapatan',
            'matchTerkonfirmasi',
            'totalMember',
            'reservasis',
            'labelUtilisasi',
            'dataUtilisasi'
        ));
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Confirmed,Waiting Payment,Completed,Cancelled',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status yang dipilih tidak valid.',
        ]);

        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->update(['status' => $request->status]);

            return back()->with('success', "Status reservasi #{$reservasi->id} berhasil diubah menjadi {$request->status}.");

        } catch (\Exception $e) {
            Log::error('Gagal update status reservasi ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah status reservasi.');
        }
    }

    public function deleteReservasi($id)
    {
        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->delete();

            return redirect()->route('admin.reservasi.index')
                ->with('success', 'Data reservasi #' . $id . ' berhasil dihapus dari sistem.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus reservasi ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('admin.reservasi.index')
                ->with('error', 'Gagal menghapus data reservasi. Silakan coba lagi.');
        }
    }

    public function deleteReservasiMassal(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:reservasis,id',
        ], [
            'ids.required' => 'Pilih minimal satu data reservasi untuk dihapus.',
            'ids.array'    => 'Data yang dikirim tidak valid.',
            'ids.*.exists' => 'Salah satu data yang dipilih tidak ditemukan (mungkin sudah dihapus sebelumnya).',
        ]);

        try {
            $jumlah = Reservasi::whereIn('id', $request->ids)->count();
            Reservasi::whereIn('id', $request->ids)->delete();

            return redirect()->route('admin.reservasi.index')
                ->with('success', $jumlah . ' data reservasi terpilih berhasil dihapus dari sistem.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus massal reservasi: ' . $e->getMessage());
            return redirect()->route('admin.reservasi.index')
                ->with('error', 'Terjadi kesalahan sistem saat menghapus data terpilih.');
        }
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

    public function editMember($id)
    {
        $member = User::with('membership')->findOrFail($id);
        return view('admin.member.edit', compact('member'));
    }

    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'points' => 'required|integer|min:0',
        ], [
            'name.required'   => 'Nama member wajib diisi.',
            'name.max'        => 'Nama member maksimum 255 karakter.',
            'points.required' => 'Saldo poin wajib diisi.',
            'points.integer'  => 'Saldo poin harus berupa angka bulat.',
            'points.min'      => 'Saldo poin tidak boleh negatif.',
        ]);

        $member = User::findOrFail($id);

        try {
            DB::transaction(function () use ($member, $request) {
                $member->update(['name' => $request->name]);

                $membership = Membership::firstOrCreate(
                    ['user_id' => $member->id],
                    ['membership_type' => 'Bronze', 'points' => 0]
                );

                $tierBaru = $request->points >= 300 ? 'Gold' : ($request->points >= 100 ? 'Silver' : 'Bronze');

                $membership->update([
                    'points'          => $request->points,
                    'membership_type' => $tierBaru,
                ]);
            });

            return redirect()->route('admin.member.index')
                ->with('success', "Data member \"{$member->name}\" berhasil diperbarui.");

        } catch (\Exception $e) {
            Log::error('Gagal update member ID ' . $id . ': ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan perubahan data member.');
        }
    }

    public function deleteMember($id)
    {
        $member = User::findOrFail($id);

        if ($member->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($member->is_admin == 1) {
            return back()->with('error', 'Akun admin tidak dapat dihapus melalui halaman manajemen member.');
        }

        try {
            DB::transaction(function () use ($member) {
                $member->membership()->delete();
                $member->delete();
            });

            return redirect()->route('admin.member.index')
                ->with('success', "Member \"{$member->name}\" berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            Log::error('Gagal hapus member ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data member.');
        }
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
        $request->validate([
            'is_admin' => 'required|in:0,1',
        ], [
            'is_admin.required' => 'Status akses wajib dipilih.',
            'is_admin.in'       => 'Status akses tidak valid.',
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
            Log::error('Gagal update role ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah status akses.');
        }
    }
}