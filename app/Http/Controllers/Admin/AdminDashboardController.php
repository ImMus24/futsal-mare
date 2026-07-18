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
     * FIX: method ini belum pernah dibuat sebelumnya, padahal route
     * 'admin.reservasi.updateStatus' (dipakai dropdown ubah status di
     * tabel log reservasi) sudah menunjuk ke sini sejak awal — akan
     * error 500 "Call to undefined method" begitu dropdown dipakai,
     * persis seperti kasus deleteReservasi().
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Confirmed,Waiting Payment,Completed,Cancelled',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status yang dipilih tidak valid.',
        ]);

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update(['status' => $request->status]);

        return back()->with('success', "Status reservasi #{$reservasi->id} berhasil diubah menjadi {$request->status}.");
    }

    /**
     * FIX: menyelesaikan error 500 "Call to undefined method deleteReservasi()"
     * pada DELETE /admin/reservasi/{id}/delete.
     */
    public function deleteReservasi($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('admin.reservasi.index')
            ->with('success', 'Data reservasi #' . $id . ' berhasil dihapus dari sistem.');
    }

    /**
     * BARU: hapus banyak reservasi terpilih sekaligus dari tabel Log Reservasi.
     * PENTING: tidak dibatasi status (beda dari destroyMassal milik member di
     * ReservasiController) — admin memang perlu bisa membersihkan log apapun
     * statusnya, termasuk Waiting Payment yang menggantung/expired.
     */
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

        $jumlah = Reservasi::whereIn('id', $request->ids)->count();
        Reservasi::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.reservasi.index')
            ->with('success', $jumlah . ' data reservasi terpilih berhasil dihapus dari sistem.');
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
     * FIX: method ini belum pernah dibuat sebelumnya, padahal route
     * 'admin.member.edit' sudah menunjuk ke sini sejak awal — pola bug
     * yang sama dengan updateStatus() dan deleteReservasi() sebelumnya.
     */
    public function role(Request $request)
    {
        $query = User::query();}

    /**
     * FIX: method ini belum pernah dibuat sebelumnya, padahal route
     * 'admin.member.update' sudah menunjuk ke sini sejak awal.
     * Mengubah nama profil + menyesuaikan poin loyalitas secara manual,
     * dan MENGKALKULASI ULANG tier (Bronze/Silver/Gold) secara otomatis
     * berdasarkan poin baru — sesuai catatan yang sudah tertulis di
     * member/edit.blade.php ("Sistem akan otomatis mengkalkulasi ulang
     * Tier/Kasta berdasarkan jumlah poin ini"), yang sebelumnya cuma
     * janji kosong karena method-nya sendiri belum ada.
     */
    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'points' => ['required', 'integer', 'min:0'],
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

                $tierBaru = 'Bronze';
                if ($request->points >= 300) {
                    $tierBaru = 'Gold';
                } elseif ($request->points >= 100) {
                    $tierBaru = 'Silver';
                }

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

    /**
     * FIX: method ini belum pernah dibuat sebelumnya, padahal route
     * 'admin.member.delete' sudah menunjuk ke sini sejak awal.
     */
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
     * MODUL 5: MANAJEMEN ROLE (is_admin)
     */


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