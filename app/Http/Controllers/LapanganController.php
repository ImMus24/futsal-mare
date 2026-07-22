<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class LapanganController extends Controller
{
    /**
     * Menampilkan daftar lapangan di panel inventaris admin.
     */
    private function rules(): array
    {
        return [
            'nama_lapangan' => 'required|string|max:255',
            'jenis_rumput'  => 'required|string|max:100',
            'harga_per_jam' => 'required|numeric|min:0',
            'deskripsi'     => 'nullable|string',
            // 15360 KB = 15 MB
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:15360',
        ];
    }

    private function messages(): array
    {
        return [
            'nama_lapangan.required' => 'Nama lapangan wajib diisi.',
            'jenis_rumput.required'  => 'Jenis rumput wajib diisi.',
            'harga_per_jam.required' => 'Harga per jam wajib diisi.',
            'foto.image'             => 'File yang diunggah harus berupa gambar.',
            'foto.mimes'             => 'Format foto harus JPG, PNG, atau WebP.',
            'foto.max'               => 'Ukuran foto maksimum 15MB.',
        ];
    }

    /**
     * Logic upload foto yang disentralisasi.
     */
    private function handleFileUpload($file, $oldFileName = null): string
    {
        // Hapus file lama jika ada
        if ($oldFileName && File::exists(public_path('images/lapangan/' . $oldFileName))) {
            File::delete(public_path('images/lapangan/' . $oldFileName));
        }

        // Sanitasi nama file
        $cleanName = preg_replace('/[^A-Za-z0-9\-\.]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $cleanName;

        // Pindahkan ke folder public
        $file->move(public_path('images/lapangan'), $fileName);

        return $fileName;
    }

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

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

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

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan baru berhasil ditambahkan!');
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
        $request->validate($this->rules(), $this->messages());

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

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Data lapangan berhasil diperbarui!');
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

        return redirect()->route('admin.lapangan.index')
                         ->with('success', 'Lapangan berhasil dihapus.');
    }
}