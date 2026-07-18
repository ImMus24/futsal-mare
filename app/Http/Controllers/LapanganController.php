<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LapanganController extends Controller
{
    /**
     * Pesan validasi custom dipakai bersama oleh store() & update()
     * supaya konsisten dan tidak terduplikasi.
     */
    private function validationMessages(): array
    {
        return [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi.',
            'nama_lapangan.max'      => 'Nama lapangan maksimum 255 karakter.',
            'jenis_rumput.required'  => 'Jenis rumput wajib diisi.',
            'jenis_rumput.max'       => 'Jenis rumput maksimum 100 karakter.',
            'harga_per_jam.required' => 'Harga per jam wajib diisi.',
            'harga_per_jam.numeric'  => 'Harga per jam harus berupa angka.',
            'harga_per_jam.min'      => 'Harga per jam tidak boleh kurang dari Rp0.',
            'foto.image'             => 'File yang diunggah harus berupa gambar.',
            'foto.mimes'             => 'Format foto harus JPG, PNG, atau WebP.',
            'foto.max'               => 'Ukuran foto maksimum 2MB.',
        ];
    }

    /**
     * Menampilkan daftar lapangan di panel inventaris admin.
     */
    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    /**
     * Menampilkan formulir tambah lapangan baru.
     */
    public function create()
    {
        return view('admin.lapangan.create');
    }

    /**
     * Menyimpan data lapangan baru ke database dengan pembersihan nama file otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis_rumput'  => 'required|string|max:100',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi'     => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], $this->validationMessages());

        // Ambil request input HTML yang valid
        $data = $request->only(['nama_lapangan', 'jenis_rumput', 'harga_per_jam', 'deskripsi']);

        try {
            // Kelola proses upload gambar ke folder publik
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                // 🌟 CLEAN SANITIZATION: Ganti spasi dan karakter unik ilegal menjadi garis bawah (_)
                $cleanName = preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());

                // Integrasikan dengan timestamp unik untuk mencegah duplikasi nama berkas sejenis
                $namaFoto = time() . '_' . $cleanName;

                // Pindahkan berkas fisik ke direktori public/images/lapangan
                $file->move(public_path('images/lapangan'), $namaFoto);

                // Petakan nama berkas hasil pembersihan ke kolom 'foto_lapangan' di database
                $data['foto_lapangan'] = $namaFoto;
            }

            // Simpan data melalui Eloquent Mass Assignment
            Lapangan::create($data);

            return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan baru berhasil ditambahkan ke sistem!');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan lapangan baru: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan lapangan baru. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan formulir edit lapangan (Mendukung Route Model Binding '$lapangan').
     */
    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    /**
     * Memperbarui data lapangan dan menghapus berkas gambar usang jika diganti.
     */
    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan' => 'required|string|max:255',
            'jenis_rumput'  => 'required|string|max:100',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi'     => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], $this->validationMessages());

        $data = $request->only(['nama_lapangan', 'jenis_rumput', 'harga_per_jam', 'deskripsi']);

        try {
            if ($request->hasFile('foto')) {
                // Bersihkan dan hapus berkas foto lama agar storage server tidak penuh
                if ($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan))) {
                    @unlink(public_path('images/lapangan/' . $lapangan->foto_lapangan));
                }

                $file = $request->file('foto');

                // Jalankan fungsi regex pembersihan nama file baru
                $cleanName = preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
                $namaFoto = time() . '_' . $cleanName;

                $file->move(public_path('images/lapangan'), $namaFoto);

                // Petakan nama file baru ke kolom tabel
                $data['foto_lapangan'] = $namaFoto;
            }

            $lapangan->update($data);

            return redirect()->route('admin.lapangan.index')->with('success', 'Data lapangan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Gagal update lapangan ID ' . $lapangan->id . ': ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan perubahan. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus instans data lapangan beserta file gambar fisiknya secara permanen.
     */
    public function destroy(Lapangan $lapangan)
    {
        try {
            // Pastikan file gambar ikut terhapus dari folder public sebelum data di-drop
            if ($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan))) {
                @unlink(public_path('images/lapangan/' . $lapangan->foto_lapangan));
            }

            $namaLapangan = $lapangan->nama_lapangan;
            $lapangan->delete();

            return redirect()->route('admin.lapangan.index')->with('success', "Lapangan \"{$namaLapangan}\" sukses dihapus dari database.");

        } catch (\Exception $e) {
            Log::error('Gagal hapus lapangan ID ' . $lapangan->id . ': ' . $e->getMessage());

            return back()->with('error', 'Gagal menghapus lapangan. Kemungkinan masih memiliki data reservasi terkait, atau terjadi kesalahan sistem.');
        }
    }
}