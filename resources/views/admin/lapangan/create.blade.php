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
        :root {
            --ink: #0a0f14;
            --surface: #121a23;
            --surface-2: #1a2431;
            --surface-3: #212d3c;
            --turf: #e25e20;
            --turf-dark: #cb5119;
            --turf-glow: rgba(226, 94, 32, 0.25);
            --floodlight: #f5c518;
            --line: #eef1ea;
            --muted: #8b97a6;
            --muted-2: #5c6979;
            --danger: #e2574c;
            --success: #2f9e58;
            --radius: 14px;
            --display: 'Anton', sans-serif;
            --body: 'Work Sans', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--ink);
            color: var(--line);
            font-family: var(--body);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            font-family: var(--display);
            font-weight: 400;
            letter-spacing: .01em;
            text-transform: uppercase;
        }

        a { color: inherit; }
        :focus-visible { outline: 2px solid var(--floodlight); outline-offset: 2px; }

        .eyebrow {
            font-family: var(--mono); font-size: 11px; letter-spacing: .14em; text-transform: uppercase;
            color: var(--turf); display: flex; align-items: center; gap: 8px; font-weight: 600;
        }
        .eyebrow::before { content: ""; width: 14px; height: 2px; background: var(--turf); display: inline-block; }

        .breadcrumb { font-family: var(--mono); font-size: 12px; color: var(--muted-2); margin-bottom: 20px; }
        .breadcrumb a:hover { color: var(--line); }

        .field-group { margin-bottom: 22px; }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 640px) { .field-row { grid-template-columns: 1fr; } }

        .label-brutal {
            font-size: 11px; color: var(--muted); display: flex; align-items: center; gap: 4px;
            margin-bottom: 8px; font-family: var(--mono); text-transform: uppercase; letter-spacing: .05em; font-weight: 700;
        }
        .label-brutal .req { color: var(--danger); }
        .label-hint { font-size: 11px; color: var(--muted-2); margin-top: 6px; font-family: var(--body); font-weight: 400; }

        .input-brutal, .textarea-brutal, select.input-brutal {
            width: 100%; background: var(--surface-3); border: 1px solid rgba(238, 241, 234, 0.12); color: var(--line);
            padding: 13px 14px; border-radius: 8px; font-family: var(--body); font-size: 14px; font-weight: 600;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .input-brutal::placeholder, .textarea-brutal::placeholder { color: var(--muted-2); font-weight: 400; }
        .input-brutal:focus, .textarea-brutal:focus { border-color: var(--turf); outline: none; box-shadow: 0 0 0 3px var(--turf-glow); }
        .input-brutal.input-error, .textarea-brutal.input-error { border-color: var(--danger); }
        .textarea-brutal { resize: vertical; min-height: 100px; }

        .price-wrap { position: relative; }
        .price-wrap .prefix {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            font-family: var(--mono); font-size: 13px; color: var(--muted); pointer-events: none;
        }
        .price-wrap input { padding-left: 40px; }

        .field-error {
            font-size: 11px; color: var(--danger); margin-top: 6px; font-family: var(--mono);
            display: flex; align-items: center; gap: 6px;
        }

        .section-divider {
            border: none; border-top: 1px solid rgba(238, 241, 234, 0.08); margin: 28px 0;
        }
        .section-label { font-family: var(--mono); font-size: 11px; color: var(--muted-2); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 16px; }

        .upload-box {
            border: 2px dashed rgba(238, 241, 234, .18); border-radius: 10px; padding: 20px; text-align: center;
            cursor: pointer; transition: border-color .15s ease; position: relative;
        }
        .upload-box:hover, .upload-box.drag { border-color: var(--turf); }
        .upload-box input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
        .upload-box .ic { font-size: 20px; color: var(--turf); margin-bottom: 6px; }
        .upload-box .txt { font-size: 13px; color: var(--muted); }
        .upload-box .txt b { color: var(--line); }
        .upload-box .hint { font-size: 11px; color: var(--muted-2); margin-top: 4px; }
        #preview-wrap { display: none; margin-top: 14px; align-items: center; gap: 14px; }
        #preview-wrap.show { display: flex; }
        #preview-img {
            width: 88px; height: 88px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(238, 241, 234, .12);
        }
        #preview-name { font-size: 12px; color: var(--muted); word-break: break-all; }
        #preview-remove { font-size: 11px; color: var(--danger); font-family: var(--mono); cursor: pointer; margin-top: 6px; display: inline-block; }

        .counter { font-size: 11px; color: var(--muted-2); font-family: var(--mono); text-align: right; margin-top: 6px; }

        .alert { padding: 16px; border-radius: 10px; font-size: 13px; font-weight: 500; margin-bottom: 20px; }
        .alert-danger { background: rgba(226, 87, 76, 0.12); border: 1px solid rgba(226, 87, 76, 0.3); color: var(--danger); }
        .alert-success { background: rgba(47, 158, 88, 0.12); border: 1px solid rgba(47, 158, 88, 0.3); color: var(--success); }
        .alert-title { font-family: var(--mono); text-transform: uppercase; letter-spacing: .05em; font-weight: 700; font-size: 11px; margin-bottom: 8px; }
        .alert ul { margin: 0; padding-left: 18px; opacity: .9; }
        .alert li { margin-bottom: 2px; }

        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 24px; border-radius: 8px; font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase;
            letter-spacing: .05em; transition: background .15s ease, border-color .15s ease, transform .15s ease;
            font-family: var(--body);
        }
        .btn-ui:active { transform: scale(.97); }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-ghost { background: transparent; border-color: rgba(238, 241, 234, 0.2); color: var(--line); font-family: var(--mono); font-size: 11px; padding: 10px 16px; }
        .btn-ui-ghost:hover { border-color: var(--line); background: var(--surface-3); }

        .form-footer { display: flex; justify-content: flex-end; gap: 12px; padding-top: 8px; }
        @media (max-width: 480px) { .form-footer { flex-direction: column-reverse; } .form-footer .btn-ui { width: 100%; } }

        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>
<body class="antialiased">

    <main class="max-w-2xl mx-auto px-4 py-12">

        <div class="breadcrumb">
            <a href="{{ route('admin.lapangan.index') }}">Dashboard</a> /
            <a href="{{ route('admin.lapangan.index') }}">Lapangan</a> /
            <span style="color: var(--line);">Tambah Lapangan</span>
        </div>

        <div style="background: var(--surface); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: var(--radius); overflow: hidden; box-shadow: 0 20px 60px -20px rgba(0,0,0,0.6);">

            <!-- HEADER -->
            <div style="padding: 22px 24px; border-bottom: 1px solid rgba(238, 241, 234, 0.08); background: rgba(15, 23, 42, 0.2); display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <div class="eyebrow">Data Lapangan</div>
                    <h2 style="font-size: 18px; color: white; letter-spacing: 0.03em; margin-top: 6px;">Daftarkan Lapangan Baru</h2>
                </div>
                <a href="{{ route('admin.lapangan.index') }}" class="btn-ui btn-ui-ghost">&larr; Kembali</a>
            </div>

            <div style="padding: 24px 24px 0;">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p class="alert-title">Gagal Menyimpan Data</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- FORM -->
            <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;" novalidate>
                @csrf

                <div class="section-label">Informasi Dasar</div>
                <div class="field-row field-group">
                    <div>
                        <label for="nama_lapangan" class="label-brutal">Nama Lapangan <span class="req">*</span></label>
                        <input id="nama_lapangan" type="text" name="nama_lapangan" value="{{ old('nama_lapangan') }}"
                               required maxlength="60" placeholder="Contoh: Lapangan Wembley"
                               class="input-brutal @error('nama_lapangan') input-error @enderror">
                        @error('nama_lapangan')<p class="field-error">⚠ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jenis_rumput" class="label-brutal">Jenis Rumput <span class="req">*</span></label>
                        <input id="jenis_rumput" type="text" name="jenis_rumput" value="{{ old('jenis_rumput') }}"
                               required list="jenis-rumput-list" placeholder="Contoh: Sintetis Monofilament"
                               class="input-brutal @error('jenis_rumput') input-error @enderror">
                        <datalist id="jenis-rumput-list">
                            <option value="Sintetis Monofilament">
                            <option value="Vinyl">
                            <option value="Interlock">
                            <option value="Rumput Alami">
                        </datalist>
                        @error('jenis_rumput')<p class="field-error">⚠ {{ $message }}</p>@enderror
                    </div>
                </div>

                <hr class="section-divider">

                <div class="section-label">Harga & Foto</div>
                <div class="field-group">
                    <label for="harga_per_jam" class="label-brutal">Harga Per Jam <span class="req">*</span></label>
                    <div class="price-wrap">
                        <span class="prefix">Rp</span>
                        <input id="harga_per_jam" type="number" name="harga_per_jam" value="{{ old('harga_per_jam') }}"
                               required min="0" step="1000" placeholder="150000"
                               class="input-brutal @error('harga_per_jam') input-error @enderror">
                    </div>
                    <p class="label-hint">Masukkan angka tanpa titik atau simbol, contoh: 150000 untuk Rp150.000.</p>
                    @error('harga_per_jam')<p class="field-error">⚠ {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="label-brutal">Foto Utama Arena <span class="req">*</span></label>
                    <div class="upload-box" id="upload-box">
                        <input id="foto" type="file" name="foto" accept="image/png, image/jpeg, image/webp" required>
                        <div class="ic">⬆</div>
                        <div class="txt"><b>Klik untuk unggah</b> atau tarik & lepas foto di sini</div>
                        <div class="hint">JPG, PNG, atau WEBP · maks. 5MB</div>
                    </div>
                    <div id="preview-wrap">
                        <img id="preview-img" src="" alt="Pratinjau foto lapangan">
                        <div>
                            <div id="preview-name"></div>
                            <span id="preview-remove">Hapus foto</span>
                        </div>
                    </div>
                    @error('foto')<p class="field-error">⚠ {{ $message }}</p>@enderror
                </div>

                <hr class="section-divider">

                <div class="section-label">Deskripsi</div>
                <div class="field-group" style="margin-bottom: 8px;">
                    <label for="deskripsi" class="label-brutal">Deskripsi & Fasilitas Lapangan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="500"
                              placeholder="Tuliskan detail kelebihan lapangan atau kelengkapan fasilitas di sini..."
                              class="textarea-brutal @error('deskripsi') input-error @enderror">{{ old('deskripsi') }}</textarea>
                    <div class="counter"><span id="desk-count">0</span>/500</div>
                    @error('deskripsi')<p class="field-error">⚠ {{ $message }}</p>@enderror
                </div>

                <div class="form-footer">
                    <a href="{{ route('admin.lapangan.index') }}" class="btn-ui btn-ui-ghost" style="padding: 13px 24px; font-size: 13px;">Batal</a>
                    <button type="submit" class="btn-ui btn-ui-primary">Simpan Data Lapangan</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Preview foto sebelum upload
        const fileInput = document.getElementById('foto');
        const previewWrap = document.getElementById('preview-wrap');
        const previewImg = document.getElementById('preview-img');
        const previewName = document.getElementById('preview-name');
        const previewRemove = document.getElementById('preview-remove');
        const uploadBox = document.getElementById('upload-box');

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            previewImg.src = url;
            previewName.textContent = file.name;
            previewWrap.classList.add('show');
        });

        previewRemove.addEventListener('click', () => {
            fileInput.value = '';
            previewWrap.classList.remove('show');
        });

        ['dragenter', 'dragover'].forEach(evt =>
            uploadBox.addEventListener(evt, (e) => { e.preventDefault(); uploadBox.classList.add('drag'); })
        );
        ['dragleave', 'drop'].forEach(evt =>
            uploadBox.addEventListener(evt, (e) => { e.preventDefault(); uploadBox.classList.remove('drag'); })
        );

        // Counter deskripsi
        const desk = document.getElementById('deskripsi');
        const deskCount = document.getElementById('desk-count');
        const updateCount = () => deskCount.textContent = desk.value.length;
        desk.addEventListener('input', updateCount);
        updateCount();
    </script>

</body>
</html>