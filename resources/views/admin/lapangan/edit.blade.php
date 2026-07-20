<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lapangan - Futsal Mare Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-2: #1a2431;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --turf-dark: #cb5119;
            --floodlight: #f5c518;
            --line: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            font-family: var(--display);
            letter-spacing: .01em;
            text-transform: uppercase;
        }

        .input-brutal, .textarea-brutal {
            width: 100%; background: var(--surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--line);
            padding: 14px; border-radius: 8px; font-family: var(--body); font-size: 14px; font-weight: 600; transition: border-color 0.15s ease;
        }
        .input-brutal:focus, .textarea-brutal:focus { border-color: var(--turf); outline: none; }
        
        .label-brutal {
            font-size: 11px; color: var(--muted); display: block; margin-bottom: 8px; font-family: var(--mono); text-transform: uppercase; letter-spacing: .05em; font-weight: 700;
        }

        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px 26px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; 
            letter-spacing: .05em; width: 100%; transition: all 0.15s ease; font-family: var(--body);
        }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-ghost { background: transparent; border-color: rgba(238, 241, 234, 0.2); color: var(--line); width: auto; padding: 8px 14px; font-family: var(--mono); font-size: 11px; }
        .btn-ui-ghost:hover { border-color: var(--line); background: var(--surface-3); }
    </style>
</head>
<body class="antialiased">

    <main class="max-w-2xl mx-auto px-4 py-12">
        <div style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: var(--radius); overflow: hidden; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6);">
            
            <!-- HEADER BAR CONTAINER -->
            <div style="padding: 24px; border-bottom: 1px solid rgba(238, 241, 234, 0.08); background: rgba(15, 23, 42, 0.2); display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 16px; color: white; letter-spacing: 0.05em;">✏️ Modifikasi Data Lapangan</h2>
                <a href="{{ route('admin.lapangan.index') }}" class="btn-ui btn-ui-ghost">
                    &larr; Kembali
                </a>
            </div>
        @endif

            <!-- ERROR HANDLING BANNER SINKRON -->
            <div style="padding: 24px; padding-bottom: 0;">
                @if ($errors->any())
                    <div style="padding: 16px; background: rgba(226, 87, 76, 0.15); border: 1px solid rgba(226, 87, 76, 0.3); color: #e2574c; border-radius: 12px; font-size: 13px; font-weight: 600;" class="space-y-1">
                        <p style="font-family: var(--mono); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">⚠️ Perubahan Gagal Disimpan:</p>
                        <ul class="list-disc list-inside font-medium" style="opacity: 0.85;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">

            <!-- HEADER BAR -->
            <div class="p-6 flex justify-between items-center relative overflow-hidden" style="border-bottom: 1px solid var(--line); background: rgba(226,94,32,0.05);">
                <div class="absolute -right-14 -top-14 w-32 h-32 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,94,32,0.18), transparent 70%);"></div>
                <div class="relative z-10">
                    <span class="f-mono text-[11px] font-semibold uppercase tracking-widest flex items-center gap-2" style="color: var(--color-primary);">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                        Modifikasi Data
                    </span>
                    <h1 class="f-display text-xl uppercase tracking-tight mt-1">✏️ Edit Lapangan: {{ $lapangan->nama_lapangan }}</h1>
                </div>
                <a href="{{ route('admin.lapangan.index') }}" class="btn-ui btn-ui-ghost relative z-10">&larr; Batal</a>
            </div>

            <!-- RINGKASAN ERROR VALIDASI -->
            @if ($errors->any())
                <div class="p-6 pb-0">
                    <div class="p-4 rounded-xl text-xs font-semibold" style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                        <p class="f-mono uppercase tracking-wide font-bold">
                            ⚠️ Perubahan Gagal Disimpan — {{ $errors->count() }} kolom perlu diperbaiki:
                        </p>
                        <p class="mt-1 font-medium" style="opacity: 0.85;">Detail kesalahan ditandai langsung di bawah setiap kolom terkait.</p>
                    </div>
                </div>
            @endif

            <!-- CORE CONFIGURATOR EDIT FORM -->
            <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label-brutal">Nama Lapangan</label>
                        <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required class="input-brutal">
                    </div>
                    <div>
                        <label class="label-brutal">Jenis Rumput</label>
                        <input type="text" name="jenis_rumput" value="{{ old('jenis_rumput', $lapangan->jenis_rumput) }}" required class="input-brutal">
                    </div>
                </div>

                <div>
                    <label class="label-brutal">Harga Per Jam (Rp)</label>
                    <input type="number" name="harga_per_jam" value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}" required class="input-brutal">
                </div>

                <div>
                    <label class="label-brutal">Ganti Foto Arena (Biarkan kosong jika tidak ingin diubah)</label>
                    <input type="file" name="foto" class="input-brutal file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer" style="padding: 10px 14px;">
                </div>

                <div>
                    <label class="label-brutal">Deskripsi & Fasilitas Lapangan</label>
                    <textarea name="deskripsi" rows="4" class="textarea-brutal">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                </div>

                <div style="padding-top: 8px;">
                    <button type="submit" class="btn-ui btn-ui-primary">
                        💾 Perbarui Perubahan Lapangan
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
