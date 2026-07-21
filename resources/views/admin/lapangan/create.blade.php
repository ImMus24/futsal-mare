<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lapangan - Futsal Mare Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .fm-scope {
            --color-primary:      #e25e20;
            --color-primary-dark: #cb5119;
            --color-secondary:    #f5c518;
            --color-bg-main:      #121a23;
            --color-bg-card:      #0a0f14;
            --color-surface:      #121a23;
            --color-surface-2:    #1a2431;
            --color-surface-3:    #212d3c;
            --color-text-main:    #ffffff;
            --color-text-muted:   #94a3b8;
            --color-text-meta:    #5c6979;
            --line:               rgba(238, 241, 234, 0.08);
            --radius: 14px;

            --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
            --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);

            font-family: 'Work Sans', sans-serif;
            color: var(--color-text-main);
        }
        body { background: var(--color-bg-main, #121a23); -webkit-font-smoothing: antialiased; margin: 0; }
        .fm-scope h1, .fm-scope h2, .fm-scope h3 {
            font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; text-transform: uppercase;
        }
        .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }

        .fm-scope .input-field, .fm-scope .textarea-field {
            width: 100%; background: var(--color-surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--color-text-main);
            padding: 13px 14px; border-radius: 8px; font-family: 'Work Sans', sans-serif; font-size: 14px; font-weight: 600;
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
            padding: 14px 26px; border-radius: 8px; font-weight: 700; font-size: 14px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
            letter-spacing: .05em; width: 100%; transition: all 0.15s ease; font-family: 'Work Sans', sans-serif;
        }
        .fm-scope .btn-ui-primary { background: var(--color-primary); color: white; }
        .fm-scope .btn-ui-primary:hover:not(:disabled) { background: var(--color-primary-dark); }
        .fm-scope .btn-ui-primary:disabled { background: var(--color-surface-3); color: var(--color-text-meta); cursor: not-allowed; }
        .fm-scope .btn-ui-ghost {
            background: transparent; border-color: rgba(238, 241, 234, 0.2); color: var(--color-text-main);
            width: auto; padding: 8px 14px; font-family: 'JetBrains Mono', monospace; font-size: 11px;
        }
        .fm-scope .btn-ui-ghost:hover { border-color: var(--color-text-main); background: var(--color-surface-3); }

        .fm-scope .spinner {
            width: 15px; height: 15px; border: 2px solid rgba(255,255,255,.35); border-top-color: #fff;
            border-radius: 50%; animation: fm-spin .7s linear infinite; flex-shrink: 0;
        }
        .fm-scope .btn-ui-primary:disabled .spinner { border-color: rgba(139,151,166,.35); border-top-color: var(--color-text-meta); }
        @keyframes fm-spin { to { transform: rotate(360deg); } }
        @media (prefers-reduced-motion: reduce) { .fm-scope .spinner { animation: none; } }

        .fm-scope :focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }
    </style>
</head>
<body class="antialiased fm-scope">

    <main class="max-w-2xl mx-auto px-4 py-12">

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

        <div style="background: var(--color-surface); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6);">

            <!-- HEADER BAR -->
            <div style="padding: 24px; border-bottom: 1px solid var(--line); background: rgba(226,94,32,0.04); display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 16px; color: white; letter-spacing: 0.05em;">✨ Daftarkan Lapangan Baru</h2>
                <a href="{{ route('admin.lapangan.index') }}" class="btn-ui btn-ui-ghost">&larr; Kembali</a>
            </div>

            <!-- RINGKASAN ERROR VALIDASI -->
            @if ($errors->any())
                <div style="padding: 24px; padding-bottom: 0;">
                    <div style="padding: 16px; background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger); border-radius: 12px; font-size: 13px; font-weight: 600;">
                        <p class="f-mono" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">
                            ⚠️ Gagal Menyimpan — {{ $errors->count() }} kolom perlu diperbaiki:
                        </p>
                        <p style="opacity: 0.85; margin-top: 4px; font-weight: 500;">Detail kesalahan ditandai langsung di bawah setiap kolom terkait.</p>
                    </div>
                </div>
            @endif

            <!-- FORM TAMBAH LAPANGAN -->
            <form id="form_tambah_lapangan" action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label-field" for="nama_lapangan">Nama Lapangan</label>
                        <input type="text" id="nama_lapangan" name="nama_lapangan"
                               value="{{ old('nama_lapangan') }}"
                               required maxlength="255" placeholder="Contoh: Lapangan Wembley"
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
                               value="{{ old('jenis_rumput') }}"
                               required maxlength="100" placeholder="Contoh: Sintetis Monofilament"
                               class="input-field @error('jenis_rumput') has-error @enderror"
                               aria-invalid="{{ $errors->has('jenis_rumput') ? 'true' : 'false' }}"
                               aria-describedby="err_jenis_rumput">
                        @error('jenis_rumput')
                            <div class="field-error" id="err_jenis_rumput">⚠ {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="label-field" for="harga_per_jam">Harga Per Jam (Rp)</label>
                    <input type="number" id="harga_per_jam" name="harga_per_jam" min="0" step="1000"
                           value="{{ old('harga_per_jam') }}"
                           required placeholder="Contoh: 150000"
                           class="input-field @error('harga_per_jam') has-error @enderror"
                           aria-invalid="{{ $errors->has('harga_per_jam') ? 'true' : 'false' }}"
                           aria-describedby="err_harga_per_jam">
                    @error('harga_per_jam')
                        <div class="field-error" id="err_harga_per_jam">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Nilai minimum Rp0, tanpa titik/koma — cukup angka murni.</div>
                </div>

                <div>
                    <label class="label-field" for="foto">Unggah Foto Utama Arena (Opsional)</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="input-field @error('foto') has-error @enderror file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer"
                           style="padding: 10px 14px;"
                           aria-invalid="{{ $errors->has('foto') ? 'true' : 'false' }}"
                           aria-describedby="err_foto">
                    @error('foto')
                        <div class="field-error" id="err_foto">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Format JPG/PNG/WebP, maksimum 2MB. Boleh dikosongkan dan ditambahkan belakangan lewat halaman Edit.</div>
                </div>

                <div>
                    <label class="label-field" for="deskripsi">Deskripsi & Fasilitas Lapangan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000"
                              placeholder="Tuliskan detail kelebihan lapangan atau kelengkapan fasilitas di sini..."
                              class="textarea-field @error('deskripsi') has-error @enderror"
                              aria-invalid="{{ $errors->has('deskripsi') ? 'true' : 'false' }}"
                              aria-describedby="err_deskripsi">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="field-error" id="err_deskripsi">⚠ {{ $message }}</div>
                    @enderror
                    <div class="field-hint">Maksimum 1000 karakter, opsional.</div>
                </div>

                <div style="padding-top: 8px;">
                    <button type="submit" id="btn_submit" class="btn-ui btn-ui-primary">
                        <span id="btn_submit_label">🚀 Simpan Data Lapangan</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Cegah submit ganda + beri umpan balik loading — form tetap submit normal (bukan AJAX)
        // supaya penanganan validasi & redirect session flash bawaan Laravel tidak perlu diduplikasi di JS.
        document.getElementById('form_tambah_lapangan').addEventListener('submit', function () {
            const btn = document.getElementById('btn_submit');
            const label = document.getElementById('btn_submit_label');
            btn.disabled = true;
            label.innerHTML = '<span class="spinner" style="display:inline-block; vertical-align:-2px;"></span> Menyimpan...';
        });
    </script>

</body>
</html>