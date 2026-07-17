<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Futsal Mare Admin</title>
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

        h1, h2, h3, h4 {
            font-family: var(--display);
            letter-spacing: .01em;
            text-transform: uppercase;
        }

        .btn-ui {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px 22px; border-radius: 8px; font-weight: 700; font-size: 13px;
            cursor: pointer; border: 1px solid transparent; text-transform: uppercase; 
            letter-spacing: .05em; font-family: var(--body); transition: all 0.15s ease;
        }
        .btn-ui:active { transform: scale(.97); }
        .btn-ui-primary { background: var(--turf); color: white; }
        .btn-ui-primary:hover { background: var(--turf-dark); }
        .btn-ui-ghost { background: transparent; border-color: rgba(238, 241, 234, 0.2); color: var(--line); }
        .btn-ui-ghost:hover { border-color: var(--line); background: var(--surface-3); }
        .btn-ui-danger { background: rgba(226, 87, 76, 0.15); border-color: rgba(226, 87, 76, 0.3); color: #e2574c; }
        .btn-ui-danger:hover { background: rgba(226, 87, 76, 0.25); }

        .eyebrow-brutal {
            font-family: var(--mono); font-size: 11px; letter-spacing: .14em; text-transform: uppercase; color: var(--turf); 
            display: flex; align-items: center; gap: 8px; font-weight: 700;
        }
        .eyebrow-brutal::before { content: ""; width: 14px; height: 2px; background: var(--turf); display: inline-block; }

        .court-card-brutal {
            background: var(--surface); border-radius: var(--radius); overflow: hidden; border: 1px solid rgba(238, 241, 234, 0.08);
            transition: border-color .2s ease, transform .2s ease; display: flex; flex-direction: column; justify-content: space-between;
        }
        .court-card-brutal:hover { border-color: var(--turf); transform: translateY(-4px); }
        
        .media-brutal { height: 180px; position: relative; background: #0b131f; overflow: hidden; }
        .media-brutal img { width: 100%; height: 100%; object-fit: cover; }
        .media-brutal::after { content: ""; position: absolute; inset: 12px; border: 2px solid rgba(238, 241, 234, 0.15); border-radius: 6px; pointer-events: none; }
    </style>
</head>
<body class="antialiased">

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 animate-fade-in">
        
        <!-- 0. GLOBAL BACK NAVIGATION BUTTON -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="btn-ui btn-ui-ghost btn-ui-sm" style="padding: 8px 14px; border-radius: 6px; font-family: var(--mono); font-size: 11px;">
                &larr; Kembali ke Dashboard
            </a>
            <div class="text-[11px] font-mono font-bold text-slate-600 tracking-widest uppercase">Infrastruktur & Arena</div>
        </div>
        
        <!-- 1. HEADER CONTROL BAR -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 bg-[var(--surface)] p-6 sm:p-8 rounded-2xl border border-slate-800/80 shadow-2xl">
            <div class="space-y-1">
                <div class="eyebrow-brutal">Sistem Manajemen Inventaris</div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Kelola Lapangan Futsal</h1>
                <p class="text-xs text-slate-400 font-medium">Tambah, edit spesifikasi teknis, atau bersihkan infrastruktur arena tanding Futsal Mare.</p>
            </div>
            <a href="{{ route('admin.lapangan.create') }}" class="btn-ui btn-ui-primary shrink-0 text-center">
                + Tambah Lapangan Baru
            </a>
        </div>

        <!-- 2. NOTIFIKASI SYSTEM SINKRON -->
        @if(session('success'))
            <div style="padding: 16px; background: rgba(47, 158, 88, 0.15); border: 1px solid rgba(47, 158, 88, 0.3); color: #2f9e58; border-radius: 12px; font-size: 13px; font-weight: 600;" class="animate-pulse">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- 3. MAIN INVENTARIS GRID CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lapangans as $lapangan)
                <div class="court-card-brutal shadow-xl">
                    
                    <!-- AREA VISUAL DAN ASSET CHECKER FALLBACK -->
                    <div class="media-brutal">
                        @if($lapangan->foto_lapangan)
                            @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                            @else
                                <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-700 font-mono text-xs uppercase tracking-widest font-bold">No Image Found</div>
                        @endif
                        <span style="font-family: var(--mono); font-size: 10px; font-weight: 700; background: rgba(47, 158, 88, 0.2); border: 1px solid rgba(47, 158, 88, 0.4); color: #2f9e58; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; position: absolute; top: 20px; right: 20px; z-index: 10;" class="backdrop-blur-sm">
                            ● Ready for Match
                        </span>
                    </div>

                    <!-- CARD BODY CONTENT -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                        <div class="space-y-2">
                            <h3 class="text-xl font-black text-white tracking-tight leading-none">{{ $lapangan->nama_lapangan }}</h3>
                            <div style="display: inline-flex; align-items: center; background: var(--ink); border: 1px solid rgba(238, 241, 234, 0.08); border-radius: 6px; padding: 4px 8px;">
                                <span style="font-family: var(--mono); font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;">
                                    🌱 Rumput: {{ $lapangan->jenis_rumput }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium line-clamp-2 pt-1 leading-relaxed">
                                {{ $lapangan->deskripsi ?? 'Tidak ada rincian deskripsi tambahan mengenai kualifikasi spesifikasi teknis lapangan ini.' }}
                            </p>
                        </div>

                        <!-- BILLING AND CRUD CONTROL ACTION BUTTONS -->
                        <div style="padding-top: 16px; border-top: 1px solid rgba(238, 241, 234, 0.08);" class="flex items-center justify-between">
                            <div>
                                <span style="font-family: var(--mono); font-size: 10px; color: var(--muted-2); text-transform: uppercase; font-weight: 700; display: block; letter-spacing: 0.02em;">Tarif Sewa Base</span>
                                <div style="font-family: var(--mono); font-size: 15px; color: var(--floodlight); font-weight: 700; line-height: 1; margin-top: 2px;">
                                    Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span style="font-size: 10px; color: var(--muted); font-family: 'Work Sans'; font-weight: 500;">/jam</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}" class="btn-ui btn-ui-ghost btn-ui-sm font-mono text-[11px]" style="padding: 8px 12px; border-radius: 6px;">
                                    ✏️ Edit
                                </a>
                                
                                <form action="{{ route('admin.lapangan.destroy', $lapangan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeksekusi penghapusan arena lapangan ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ui btn-ui-danger btn-ui-sm font-mono text-[11px]" style="padding: 8px 12px; border-radius: 6px;">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-16 text-center space-y-3 bg-[var(--surface)] rounded-2xl border border-dashed border-slate-800">
                    <div style="font-size: 32px;" class="animate-bounce">🥅</div>
                    <p class="text-slate-500 font-bold text-sm uppercase tracking-wide">Belum ada data modul arena lapangan futsal yang terdaftar.</p>
                </div>
            @endforelse
        </div>
    </main>

</body>
</html>