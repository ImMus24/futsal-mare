<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lapangan - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] text-slate-200 font-sans antialiased">
    <main class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-[#152238] rounded-3xl border border-slate-800 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 bg-[#0F172A]/40 flex justify-between items-center">
                <h2 class="text-sm font-black uppercase text-white tracking-wider">✏️ Modifikasi Data Lapangan</h2>
                <a href="{{ route('admin.lapangan.index') }}" class="text-xs text-slate-400 hover:text-white font-bold transition">← Kembali</a>
            </div>

            <div class="p-6 pb-0">
                @if ($errors->any())
                    <div class="p-4 bg-red-950/40 border border-red-800/40 text-red-400 rounded-2xl text-xs font-bold space-y-1 shadow-md">
                        <p class="font-black uppercase tracking-wider text-red-300">⚠️ Perubahan Gagal Disimpan:</p>
                        <ul class="list-disc list-inside space-y-0.5 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data" class="p-6 pt-2 space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Nama Lapangan</label>
                        <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-[#E25E20] transition">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Jenis Rumput</label>
                        <input type="text" name="jenis_rumput" value="{{ old('jenis_rumput', $lapangan->jenis_rumput) }}" required class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-[#E25E20] transition">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Harga Per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" required class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-[#E25E20] transition">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Ganti Foto Arena (Biarkan kosong jika tidak ingin diubah)</label>
                    <input type="file" name="foto" class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-wider text-slate-400">Deskripsi & Fasilitas Lapangan</label>
                    <textarea name="deskripsi" rows="4" class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-[#E25E20] transition">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl tracking-widest uppercase shadow-lg transition">
                    💾 Perbarui Perubahan Lapangan
                </button>
            </form>
        </div>
    </main>
</body>
</html>