<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Membership;
use App\Models\Reservasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /**
     * ==========================================
     * 🛡️ MODUL KHUSUS: AUTENTIKASI PORTAL ADMIN
     * ==========================================
     */

    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->is_admin) { 
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin == 1) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang kembali di Panel Kontrol Utama!');
            }

            Auth::logout();
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
        // 1. Ringkasan Statistik Utama
        $totalPendapatan = Reservasi::whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        $matchTerkonfirmasi = Reservasi::where('status', 'Confirmed')->count();
        $totalMember = User::has('membership')->where('is_admin', 0)->count();
        $reservasis = Reservasi::with(['lapangan', 'user.membership'])->latest()->paginate(10);

        // 2. Kalkulasi Grafik Utilisasi 7 Hari Terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $reservasi7Hari = Reservasi::whereBetween('tanggal_main', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', ['Confirmed', 'Completed'])
            ->get(['tanggal_main', 'jam_mulai', 'jam_selesai'])
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal_main)->format('Y-m-d');
            });

        $labelUtilisasi = [];
        $dataUtilisasi = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');

            $labelUtilisasi[] = $date->translatedFormat('d M');

            $totalJam = 0;
            if (isset($reservasi7Hari[$dateString])) {
                $totalJam = $reservasi7Hari[$dateString]->sum(function ($reservasi) {
                    if (!empty($reservasi->jam_mulai) && !empty($reservasi->jam_selesai)) {
                        $start = (int) substr($reservasi->jam_mulai, 0, 2);
                        $end = (int) substr($reservasi->jam_selesai, 0, 2);
                        return max(1, $end - $start);
                    }
                    return 1;
                });
            }

            $dataUtilisasi[] = (int) $totalJam;
        }

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
     * ==========================================
     * 🔑 MODUL 1.5: KELOLA HAK AKSES / ROLE
     * ==========================================
     */
    public function role(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('is_admin', 'desc')
                       ->orderBy('name', 'asc')
                       ->paginate(10)
                       ->withQueryString();

        return view('admin.role.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'is_admin' => 'required|in:0,1',
        ], [
            'is_admin.required' => 'Status hak akses wajib dipilih.',
            'is_admin.in'       => 'Pilihan hak akses tidak valid.',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status role akun Anda sendiri.');
        }

        $user->update(['is_admin' => $request->is_admin]);

        $statusRole = $user->is_admin == 1 ? 'Administrator' : 'Pengguna Biasa';
        return back()->with('success', "Akses untuk \"{$user->name}\" berhasil diperbarui menjadi {$statusRole}.");
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_reservasi', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reservasis = $query->latest()->paginate(15)->withQueryString();

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

        if (ob_get_level()) {
            ob_end_clean();
        }

        $filename = "Laporan_Reservasi_Futsal_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('admin.reservasi.excel', compact('reservasis'));
    }

    /**
     * Mengubah Status Reservasi secara Manual (+ Sinkronisasi Poin Member)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Confirmed,Waiting Payment,Completed,Cancelled',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status yang dipilih tidak valid.',
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                $reservasi = Reservasi::findOrFail($id);
                $statusLama = $reservasi->status;
                $statusBaru = $request->status;

                $reservasi->update(['status' => $statusBaru]);

                // Jika diubah manual menjadi Confirmed / Completed dari Waiting Payment, hitung poin member
                if (!in_array($statusLama, ['Confirmed', 'Completed']) && in_array($statusBaru, ['Confirmed', 'Completed'])) {
                    $this->tambahPoinMember($reservasi);
                }
            });

            return back()->with('success', "Status reservasi #{$id} berhasil diperbarui menjadi {$request->status}.");
        } catch (\Exception $e) {
            Log::error('Gagal update status reservasi ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah status reservasi.');
        }
    }

    /**
     * Helper Internal: Tambah Poin & Update Tier Member
     */
    private function tambahPoinMember(Reservasi $reservasi): void
    {
        if (!$reservasi->user_id) return;

        $membership = Membership::firstOrCreate(
            ['user_id' => $reservasi->user_id],
            ['membership_type' => 'Bronze', 'points' => 0]
        );

        $poinBaru = (int) floor($reservasi->total_harga / 10000);
        $totalPoinAkhir = $membership->points + $poinBaru;

        $tierEvaluasi = 'Bronze';
        if ($totalPoinAkhir >= 300) {
            $tierEvaluasi = 'Gold';
        } elseif ($totalPoinAkhir >= 100) {
            $tierEvaluasi = 'Silver';
        }

        $membership->update([
            'points'          => $totalPoinAkhir,
            'membership_type' => $tierEvaluasi,
        ]);
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
            'ids.*.exists' => 'Salah satu data yang dipilih tidak ditemukan.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Reservasi::whereIn('id', $request->ids)->delete();
            });

            return redirect()->route('admin.reservasi.index')
                ->with('success', count($request->ids) . ' data reservasi terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal hapus massal reservasi: ' . $e->getMessage());
            return redirect()->route('admin.reservasi.index')
                ->with('error', 'Terjadi kesalahan sistem saat menghapus data terpilih.');
        }
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
        // Menggunakan subquery Eloquent yang bersih tanpa resiko ID ter-overwrite
        $query = User::where('is_admin', 0)
            ->with('membership')
            ->addSelect([
                'total_points' => Membership::select('points')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1)
            ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderByRaw('COALESCE(total_points, 0) DESC')
            ->latest('users.created_at')
            ->paginate(10)
            ->withQueryString();

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

                $tierBaru = $request->points >= 300 ? 'Gold' : ($request->points >= 100 ? 'Silver' : 'Bronze');

                $member->membership()->updateOrCreate(
                    ['user_id' => $member->id],
                    [
                        'points'          => $request->points,
                        'membership_type' => $tierBaru,
                    ]
                );
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
                // Hapus reservasi terkait terlebih dahulu agar tidak melempar Foreign Key Constraint Error
                $member->reservasis()->delete();
                $member->membership()->delete();
                $member->delete();
            });

            return redirect()->route('admin.member.index')
                ->with('success', "Member \"{$member->name}\" dan seluruh riwayat ikatannya berhasil dihapus dari sistem.");
        } catch (\Exception $e) {
            Log::error('Gagal hapus member ID ' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data member.');
        }
    }
}