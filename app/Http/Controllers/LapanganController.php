<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
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
        ]);

        // Ambil request input HTML yang valid
        $data = $request->only(['nama_lapangan', 'jenis_rumput', 'harga_per_jam', 'deskripsi']);

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
        ]);

        $data = $request->only(['nama_lapangan', 'jenis_rumput', 'harga_per_jam', 'deskripsi']);

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
    }

    /**
     * Menghapus instans data lapangan beserta file gambar fisiknya secara permanen.
     */
    public function destroy(Lapangan $lapangan)
    {
        // Pastikan file gambar ikut terhapus dari folder public sebelum data di-drop
        if ($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan))) {
            @unlink(public_path('images/lapangan/' . $lapangan->foto_lapangan));
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan sukses dihapus dari database.');
    }
}