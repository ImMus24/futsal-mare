<<<<<<< HEAD
@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras dashboard, login, landing page,
     dan halaman log reservasi)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e25e20;
        --color-primary-dark: #cb5119;
        --color-secondary:    #f5c518;
        --color-bg-main:      #121a23;
        --color-bg-card:      #0a0f14;
        --color-bg-card-alt:  #1a2431;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);
        --info:    #3B82F6; --info-bg:    rgba(59, 130, 246, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }

    .fm-scope .court-card {
        background: var(--color-bg-card);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--line);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color .2s ease, transform .2s ease;
    }
    .fm-scope .court-card:hover { border-color: var(--color-primary); transform: translateY(-4px); }

    .fm-scope .court-media {
        height: 180px;
        position: relative;
        background: #0b131f;
        overflow: hidden;
    }
    .fm-scope .court-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .fm-scope .court-media::after {
        content: "";
        position: absolute; inset: 12px;
        border: 2px solid rgba(238, 241, 234, 0.12);
        border-radius: 8px;
        pointer-events: none;
    }

    .fm-scope .badge-ready {
        font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 700;
        background: var(--success-bg); border: 1px solid rgba(34,197,94,0.3); color: var(--success);
        padding: 4px 9px; border-radius: 6px; text-transform: uppercase; letter-spacing: .04em;
        position: absolute; top: 14px; right: 14px; z-index: 10; backdrop-filter: blur(4px);
    }

    @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    <!-- 0. TOP NAVIGATION -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wide transition group"
           style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
            <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
        </a>
        <div class="f-mono text-[10px] font-semibold tracking-wider uppercase" style="color: var(--color-text-meta);">Infrastruktur &amp; Arena</div>
    </div>

    <!-- 1. HEADER HERO WIDGET -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 rounded-2xl shadow-2xl relative overflow-hidden"
         style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,94,32,0.18), transparent 70%);"></div>
        <div class="relative z-10">
            <span class="eyebrow inline-flex items-center gap-2 f-mono text-[11px] font-semibold uppercase tracking-widest" style="color: var(--color-primary);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                Sistem Manajemen Inventaris
            </span>
            <h1 class="f-display text-2xl uppercase tracking-tight mt-1.5" style="color: var(--color-text-main);">🥅 Kelola Lapangan Futsal</h1>
            <p class="text-xs mt-1.5 max-w-lg" style="color: var(--color-text-muted);">Tambah, edit spesifikasi teknis, atau bersihkan data infrastruktur arena tanding Futsal Mare.</p>
        </div>

        <div class="relative z-10">
            <a href="{{ route('admin.lapangan.create') }}"
               class="px-5 py-3 text-white font-semibold text-xs rounded-xl tracking-wider uppercase transition-all duration-200 flex items-center gap-2 transform hover:-translate-y-0.5"
               style="background: linear-gradient(120deg, var(--color-primary), var(--color-primary-dark)); box-shadow: 0 12px 26px -10px rgba(226,94,32,0.45);">
                ➕ Tambah Lapangan Baru
            </a>
        </div>
    </div>

    <!-- 2. NOTIFIKASI: SUKSES -->
    @if(session('success'))
        <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
             style="background: var(--success-bg); border: 1px solid rgba(34,197,94,0.3); color: var(--success);">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

    <!-- 2b. NOTIFIKASI: GAGAL / ERROR UMUM (mis. gagal hapus karena masih ada reservasi aktif) -->
    @if(session('error'))
        <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
             style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
            <span>⚠️</span> {{ session('error') }}
        </div>
    @endif

    <!-- 2c. NOTIFIKASI: ERROR VALIDASI (jika redirect kembali membawa error bag) -->
    @if ($errors->any())
        <div class="p-4 rounded-2xl text-xs font-semibold shadow-md"
             style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
            <p class="f-mono uppercase tracking-wide font-bold flex items-center gap-2"><span>⚠️</span> Aksi Gagal Diproses:</p>
            <ul class="list-disc list-inside mt-1.5 font-medium" style="opacity: 0.9;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. MAIN INVENTARIS GRID CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lapangans as $lapangan)
            <div class="court-card shadow-xl">

                <!-- AREA VISUAL DAN ASSET CHECKER FALLBACK -->
                <div class="court-media">
                    @if($lapangan->foto_lapangan)
                        @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                            <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                        @else
                            <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}">
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center font-mono text-xs uppercase tracking-widest font-bold" style="color: var(--color-text-meta);">
                            Tidak Ada Foto
                        </div>
                    @endif
                    <span class="badge-ready">● Siap Tanding</span>
                </div>

                <!-- CARD BODY CONTENT -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                    <div class="space-y-2">
                        <h3 class="text-xl font-black tracking-tight leading-none" style="color: var(--color-text-main);">{{ $lapangan->nama_lapangan }}</h3>
                        <div class="inline-flex items-center rounded-lg px-2.5 py-1" style="background: var(--color-bg-main); border: 1px solid var(--line);">
                            <span class="f-mono text-[10px] font-semibold uppercase tracking-wide" style="color: var(--color-text-muted);">
                                🌱 Rumput: {{ $lapangan->jenis_rumput }}
                            </span>
                        </div>
                        <p class="text-xs font-medium line-clamp-2 pt-1 leading-relaxed" style="color: var(--color-text-muted);">
                            {{ $lapangan->deskripsi ?? 'Tidak ada rincian deskripsi tambahan mengenai spesifikasi teknis lapangan ini.' }}
                        </p>
                    </div>

                    <!-- BILLING AND CRUD CONTROL ACTION BUTTONS -->
                    <div class="flex items-center justify-between pt-4" style="border-top: 1px solid var(--line);">
                        <div>
                            <span class="f-mono text-[10px] font-semibold uppercase tracking-wide block" style="color: var(--color-text-meta);">Tarif Sewa Base</span>
                            <div class="f-mono text-[15px] font-bold leading-none mt-0.5" style="color: var(--color-secondary);">
                                Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span class="text-[10px] font-medium" style="color: var(--color-text-muted); font-family: 'Work Sans', sans-serif;">/jam</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}"
                               class="f-mono text-[11px] font-semibold uppercase tracking-wide px-3 py-2 rounded-lg transition"
                               style="border: 1px solid var(--line); color: var(--color-text-muted);"
                               onmouseover="this.style.color='#fff'; this.style.borderColor='var(--color-text-muted)'"
                               onmouseout="this.style.color='var(--color-text-muted)'; this.style.borderColor='var(--line)'">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('admin.lapangan.destroy', $lapangan->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan &quot;{{ $lapangan->nama_lapangan }}&quot; secara permanen? Data reservasi terkait lapangan ini bisa jadi ikut terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="f-mono text-[11px] font-semibold uppercase tracking-wide px-3 py-2 rounded-lg transition"
                                        style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-16 text-center space-y-3 rounded-2xl"
                 style="background: var(--color-bg-card); border: 1px dashed var(--line);">
                <div class="text-3xl">🥅</div>
                <p class="font-bold text-xs uppercase tracking-wide" style="color: var(--color-text-meta);">Belum ada data lapangan futsal yang terdaftar.</p>
                <a href="{{ route('admin.lapangan.create') }}"
                   class="inline-flex items-center gap-2 mt-2 px-4 py-2.5 text-white text-xs font-semibold rounded-xl uppercase tracking-wide transition-all duration-200"
                   style="background: linear-gradient(120deg, var(--color-primary), var(--color-primary-dark));">
                    ➕ Tambah Lapangan Pertama
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
=======
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
>>>>>>> main
