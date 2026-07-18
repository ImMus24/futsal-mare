<<<<<<< HEAD
@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras index kelola lapangan,
     log reservasi, dashboard, dan landing page)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e25e20;
        --color-primary-dark: #cb5119;
        --color-secondary:    #f5c518;
        --color-bg-main:      #121a23;
        --color-bg-card:      #0a0f14;
        --color-surface-3:    #1a2431;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);
        --radius: 16px;

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }

    .fm-scope .input-field, .fm-scope .textarea-field {
        width: 100%; background: var(--color-surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--color-text-main);
        padding: 13px 14px; border-radius: 10px; font-family: 'Work Sans', sans-serif; font-size: 14px; font-weight: 600;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .fm-scope .input-field:focus, .fm-scope .textarea-field:focus {
        border-color: var(--color-primary); outline: none; box-shadow: 0 0 0 3px rgba(226,94,32,.2);
    }
    .fm-scope .input-field.has-error, .fm-scope .textarea-field.has-error {
        border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239,68,68,.15);
    }

    .fm-scope .label-field {
        font-size: 11px; color: var(--color-text-muted); display: block; margin-bottom: 8px;
        font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: .05em; font-weight: 700;
    }
    .fm-scope .field-error {
        display: flex; align-items: center; gap: 6px; font-family: 'JetBrains Mono', monospace;
        font-size: 11px; color: var(--danger); margin-top: 7px; font-weight: 600;
    }
    .fm-scope .field-hint {
        font-size: 11px; color: var(--color-text-meta); margin-top: 6px; font-weight: 500;
    }

    .fm-scope .btn-ui {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 14px 26px; border-radius: 10px; font-weight: 700; font-size: 13px;
        cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
        letter-spacing: .05em; transition: all 0.15s ease; font-family: 'Work Sans', sans-serif;
    }
    .fm-scope .btn-ui-primary { background: linear-gradient(120deg, var(--color-primary), var(--color-primary-dark)); color: white; width: 100%; box-shadow: 0 12px 26px -10px rgba(226,94,32,0.45); }
    .fm-scope .btn-ui-primary:hover:not(:disabled) { filter: brightness(1.06); }
    .fm-scope .btn-ui-primary:disabled { background: var(--color-surface-3); box-shadow: none; color: var(--color-text-meta); cursor: not-allowed; }
    .fm-scope .btn-ui-ghost {
        background: transparent; border: 1px solid var(--line); color: var(--color-text-muted);
        padding: 10px 16px; font-family: 'JetBrains Mono', monospace; font-size: 11px;
    }
    .fm-scope .btn-ui-ghost:hover { border-color: var(--color-text-muted); color: #fff; }

    .fm-scope .spinner {
        width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.35); border-top-color: #fff;
        border-radius: 50%; animation: fm-spin .7s linear infinite; flex-shrink: 0;
    }
    .fm-scope .btn-ui-primary:disabled .spinner { border-color: rgba(139,151,166,.35); border-top-color: var(--color-text-meta); }
    @keyframes fm-spin { to { transform: rotate(360deg); } }
    @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
    @media (prefers-reduced-motion: reduce) { .fm-scope .spinner { animation: none; } }

    .fm-scope .photo-preview {
        width: 68px; height: 68px; border-radius: 10px; object-fit: cover;
        border: 1px solid rgba(238,241,234,0.12); flex-shrink: 0; background: var(--color-surface-3);
    }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible, .fm-scope input:focus-visible, .fm-scope textarea:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    <!-- 0. TOP NAVIGATION -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.lapangan.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wide transition group"
           style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
            <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Kelola Lapangan
        </a>
        <div class="f-mono text-[10px] font-semibold tracking-wider uppercase" style="color: var(--color-text-meta);">Infrastruktur &amp; Arena</div>
    </div>

    <div class="max-w-2xl mx-auto w-full">

        <!-- BANNER SUKSES -->
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl text-xs font-semibold flex items-center gap-2" style="background: var(--success-bg); border: 1px solid rgba(34,197,94,0.3); color: var(--success);">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- BANNER GAGAL (non-validasi, misal exception saat upload) -->
        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl text-xs font-semibold flex items-center gap-2" style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                <span>⚠️</span> {{ session('error') }}
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
=======
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
>>>>>>> main
                    </div>
                </div>
            @endif

<<<<<<< HEAD
            <!-- FORM EDIT -->
            <form id="form_edit_lapangan" action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data" class="p-6 flex flex-col gap-5">
=======
            <!-- CORE CONFIGURATOR EDIT FORM -->
            <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
>>>>>>> main
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
<<<<<<< HEAD
                        <label class="label-field" for="nama_lapangan">Nama Lapangan</label>
                        <input type="text" id="nama_lapangan" name="nama_lapangan"
                               value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                               required maxlength="100"
                               class="input-field @error('nama_lapangan') has-error @enderror"
                               aria-invalid="{{ $errors->has('nama_lapangan') ? 'true' : 'false' }}"
                               aria-describedby="err_nama_lapangan">
                        @error('nama_lapangan')
                            <div class="field-error" id="err_nama_lapangan">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label-field" for="jenis_rumput">Jenis Rumput</label>
                        <input type="text" id="jenis_rumput" name="jenis_rumput"
                               value="{{ old('jenis_rumput', $lapangan->jenis_rumput) }}"
                               required maxlength="60"
                               class="input-field @error('jenis_rumput') has-error @enderror"
                               aria-invalid="{{ $errors->has('jenis_rumput') ? 'true' : 'false' }}"
                               aria-describedby="err_jenis_rumput">
                        @error('jenis_rumput')
                            <div class="field-error" id="err_jenis_rumput">⚠ {{ $message }}</div>
                        @enderror
=======
                        <label class="label-brutal">Nama Lapangan</label>
                        <input type="text" name="nama_lapangan" value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}" required class="input-brutal">
                    </div>
                    <div>
                        <label class="label-brutal">Jenis Rumput</label>
                        <input type="text" name="jenis_rumput" value="{{ old('jenis_rumput', $lapangan->jenis_rumput) }}" required class="input-brutal">
>>>>>>> main
                    </div>
                </div>

                <div>
<<<<<<< HEAD
                    <label class="label-field" for="harga_per_jam">Harga Per Jam (Rp)</label>
                    <input type="number" id="harga_per_jam" name="harga_per_jam" min="0" step="1000"
                           value="{{ old('harga_per_jam', $lapangan->harga_per_jam) }}"
                           required
                           class="input-field @error('harga_per_jam') has-error @enderror"
                           aria-invalid="{{ $errors->has('harga_per_jam') ? 'true' : 'false' }}"
                           aria-describedby="err_harga_per_jam">
                    @error('harga_per_jam')
                        <div class="field-error" id="err_harga_per_jam">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Nilai minimum Rp0, tanpa titik/koma — cukup angka murni.</div>
                </div>

                <div>
                    <label class="label-field" for="foto">Ganti Foto Arena (Biarkan kosong jika tidak ingin diubah)</label>

                    <div class="flex items-center gap-4">
                        @if($lapangan->foto_lapangan)
                            <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="Foto {{ $lapangan->nama_lapangan }} saat ini" class="photo-preview">
                        @else
                            <div class="photo-preview flex items-center justify-center f-mono" style="font-size: 9px; color: var(--color-text-meta); text-align: center;">Tidak ada foto</div>
                        @endif

                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="input-field @error('foto') has-error @enderror file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer"
                               style="padding: 10px 14px;"
                               aria-invalid="{{ $errors->has('foto') ? 'true' : 'false' }}"
                               aria-describedby="err_foto">
                    </div>
                    @error('foto')
                        <div class="field-error" id="err_foto">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Format JPG/PNG/WebP, maksimum 2MB.</div>
                </div>

                <div>
                    <label class="label-field" for="deskripsi">Deskripsi &amp; Fasilitas Lapangan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000"
                              class="textarea-field @error('deskripsi') has-error @enderror"
                              aria-invalid="{{ $errors->has('deskripsi') ? 'true' : 'false' }}"
                              aria-describedby="err_deskripsi">{{ old('deskripsi', $lapangan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="field-error" id="err_deskripsi">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Maksimum 1000 karakter, opsional.</div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btn_submit" class="btn-ui btn-ui-primary">
                        <span id="btn_submit_label">💾 Perbarui Perubahan Lapangan</span>
=======
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
>>>>>>> main
                    </button>
                </div>
            </form>
        </div>
<<<<<<< HEAD
    </div>
</div>

<script>
    // Cegah submit ganda + beri umpan balik loading — form tetap submit normal (bukan AJAX)
    // supaya penanganan validasi & redirect session flash bawaan Laravel tidak perlu diduplikasi di JS.
    document.getElementById('form_edit_lapangan').addEventListener('submit', function () {
        const btn = document.getElementById('btn_submit');
        const label = document.getElementById('btn_submit_label');
        btn.disabled = true;
        label.innerHTML = '<span class="spinner" style="display:inline-block; vertical-align:-2px;"></span> Menyimpan Perubahan...';
    });
</script>
@endsection
=======
    </main>

</body>
</html>
>>>>>>> main
