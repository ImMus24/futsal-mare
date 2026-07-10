<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    /**
     * Menampilkan daftar lapangan admin.
     */
    public function index()
    {
        $lapangans = Lapangan::latest()->get();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    /**
     * Menampilkan formulir tambah lapangan.
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

        // Ambil semua request input HTML
        $data = $request->only(['nama_lapangan', 'jenis_rumput', 'harga_per_jam', 'deskripsi']);

        // Kelola upload gambar ke folder publik
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // 🌟 SANGAT AMAN: Bersihkan spasi, tanda kurung, dan karakter unik dari nama file asli
            $cleanName = preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            
            // Gabungkan dengan timestamp unik untuk mencegah duplikasi berkas
            $namaFoto = time() . '_' . $cleanName;
            
            // Pindahkan file fisik ke folder public/images/lapangan
            $file->move(public_path('images/lapangan'), $namaFoto);
            
            // SINKRONISASI: Masukkan nama file yang sudah bersih ke kolom database 'foto_lapangan'
            $data['foto_lapangan'] = $namaFoto;
        }

        // Simpan data ke database melalui Eloquent Mass Assignment
        Lapangan::create($data);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan baru berhasil ditambahkan ke sistem!');
    }

    /**
     * Menampilkan formulir edit lapangan.
     */
    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    /**
     * Memperbarui data lapangan yang sudah ada dan membersihkan nama file baru.
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
            // Hapus berkas foto lama dari direktori jika sebelumnya ada
            if ($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan))) {
                @unlink(public_path('images/lapangan/' . $lapangan->foto_lapangan));
            }

            $file = $request->file('foto');
            
            // 🌟 SANGAT AMAN: Bersihkan nama file baru dari spasi atau simbol mengganggu
            $cleanName = preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
            $namaFoto = time() . '_' . $cleanName;
            
            $file->move(public_path('images/lapangan'), $namaFoto);
            
            // SINKRONISASI: Perbarui kolom 'foto_lapangan'
            $data['foto_lapangan'] = $namaFoto;
        }

        $lapangan->update($data);

        return redirect()->route('admin.lapangan.index')->with('success', 'Data lapangan berhasil diperbarui!');
    }

    /**
     * Menghapus lapangan dari sistem beserta file fisik fotonya.
     */
    public function destroy(Lapangan $lapangan)
    {
        // Bersihkan file fisik foto dari storage publik sebelum record dihapus
        if ($lapangan->foto_lapangan && file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan))) {
            @unlink(public_path('images/lapangan/' . $lapangan->foto_lapangan));
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan sukses dihapus dari database.');
    }
}