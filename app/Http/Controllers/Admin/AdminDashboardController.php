<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
            if (Auth::user()->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat Datang Kembali di Panel Kontrol Utama!');
            }

            Auth::logout();
            return redirect()->back()
                ->withErrors(['email' => 'Akses Ditolak. Akun Anda tidak memiliki otoritas Administrator.'])
                ->withInput();
        }

        return redirect()->back()
            ->withErrors(['email' => 'Kredensial atau kata sandi yang Anda masukkan salah.'])
            ->withInput();
    }

    /**
     * ==========================================
     * 🎫 MODUL KHUSUS: AUTENTIKASI PORTAL STAFF
     * ==========================================
     */

    public function loginStaff(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email staff wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password/PIN wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Arahkan staff langsung ke terminal/halaman scanner QR
            return redirect()->route('admin.staff.scan')
                ->with('success', 'Selamat bekerja! Sesi Operator Terminal berhasil dibuka.');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Kredensial atau PIN Staff yang Anda masukkan salah.'])
            ->withInput();
    }

    /**
     * ==========================================
     * 📊 MODUL 1: DASHBOARD OVERVIEW ADMIN
     * ==========================================
     */
    public function index()
    {
        // Metrik Ringkasan (Stat Cards)
        $totalPendapatan = Reservasi::whereIn('status', ['Confirmed', 'Completed'])->sum('total_harga');
        $matchTerkonfirmasi = Reservasi::where('status', 'Confirmed')->count();
        $totalMember = User::has('membership')->where('is_admin', 0)->count();
        
        // Data Table Reservasi Terbaru
        $reservasis = Reservasi::with(['lapangan', 'user.membership'])
            ->latest()
            ->paginate(10);

        // Grafik Utilisasi 7 Hari Terakhir
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $reservasi7Hari = Reservasi::whereBetween('tanggal_main', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('status', ['Confirmed', 'Completed'])
            ->get(['tanggal_main', 'jam_mulai', 'jam_selesai'])
            ->groupBy(fn($item) => Carbon::parse($item->tanggal_main)->format('Y-m-d'));

        $labelUtilisasi = [];
        $dataUtilisasi = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');

            $labelUtilisasi[] = $date->translatedFormat('d M');

            $totalJam = 0;
            if (isset($reservasi7Hari[$dateString])) {
                $totalJam = $reservasi7Hari[$dateString]->sum(function ($reservasi) {
                    if ($reservasi->jam_mulai && $reservasi->jam_selesai) {
                        $start = Carbon::parse($reservasi->jam_mulai);
                        $end = Carbon::parse($reservasi->jam_selesai);
                        
                        // Perhitungan presisi dalam jam (mendukung pecahan jam)
                        $diffInMinutes = $start->diffInMinutes($end);
                        return max(1, round($diffInMinutes / 60, 1));
                    }
                    return 1;
                });
            }

            $dataUtilisasi[] = $totalJam;
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
     * 📅 MODUL 2: LOG & PENGELOLAAN RESERVASI
     * ==========================================
     */
    public function reservasi(Request $request)
    {
        $status = $request->get('status');
        
        $reservasis = Reservasi::with(['lapangan', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function exportExcel(Request $request)
    {
        $status = $request->get('status');
        
        $reservasis = Reservasi::with(['lapangan', 'user'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->get();

        $filename = "Laporan_Reservasi_Futsal_" . now()->format('Ymd_His') . ".xls";

        return response()
            ->view('admin.reservasi.excel', compact('reservasis'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

            return back()->with('success', "Status reservasi #{$reservasi->id} (Nota: {$reservasi->nomor_reservasi}) berhasil diperbarui.");

        } catch (\Exception $e) {
            Log::error("Gagal update status reservasi ID {$id}: {$e->getMessage()}");
            return back()->with('error', 'Terjadi kesalahan sistem saat mengubah status reservasi.');
        }
    }

    public function deleteReservasi($id)
    {
        try {
            $reservasi = Reservasi::findOrFail($id);
            $reservasi->delete();

            return back()->with('success', "Data reservasi #{$id} berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            Log::error("Gagal hapus reservasi ID {$id}: {$e->getMessage()}");
            return back()->with('error', 'Gagal menghapus data reservasi. Silakan coba lagi.');
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
            $jumlah = Reservasi::whereIn('id', $request->ids)->delete();

            return redirect()->route('admin.reservasi.index')
                ->with('success', "{$jumlah} data reservasi terpilih berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            Log::error("Gagal hapus massal reservasi: {$e->getMessage()}");
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
        $search = $request->get('search');

        $members = User::where('is_admin', 0)
            ->with('membership')
            ->addSelect([
                'total_points' => Membership::select('points')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1)
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('COALESCE(total_points, 0) DESC')
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

                $tierBaru = match (true) {
                    $request->points >= 300 => 'Gold',
                    $request->points >= 100 => 'Silver',
                    default                 => 'Bronze',
                };

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
            Log::error("Gagal update member ID {$id}: {$e->getMessage()}");
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

        if ($member->is_admin) {
            return back()->with('error', 'Akun admin tidak dapat dihapus melalui halaman manajemen member.');
        }

        try {
            DB::transaction(function () use ($member) {
                $member->reservasis()->delete();
                $member->membership()->delete();
                $member->delete();
            });

            return redirect()->route('admin.member.index')
                ->with('success', "Member \"{$member->name}\" berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            Log::error("Gagal hapus member ID {$id}: {$e->getMessage()}");
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data member.');
        }
    }

    /**
     * ==========================================
     * 🔑 MODUL 5: PENGELOLAAN HAK AKSES & ROLE
     * ==========================================
     */
    public function role(Request $request)
    {
        $search = $request->get('search');

        $users = User::when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.role.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'is_admin' => 'required|in:0,1',
        ], [
            'is_admin.required' => 'Status akses wajib dipilih.',
            'is_admin.in'       => 'Pilihan status akses tidak valid.',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akses akun Anda sendiri.');
        }

        try {
            $user->update([
                'is_admin' => (int) $request->is_admin,
            ]);

            $statusText = $user->is_admin == 1 ? 'Administrator (Admin)' : 'Member';

            return back()->with('success', "Akses akun \"{$user->name}\" berhasil diperbarui menjadi {$statusText}.");

        } catch (\Exception $e) {
            Log::error("Gagal update role user ID {$id}: {$e->getMessage()}");
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui hak akses pengguna.');
        }
    }
}