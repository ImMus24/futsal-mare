<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - Futsal Mare Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] text-slate-200 font-sans antialiased">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        
        <!-- 0. GLOBAL BACK NAVIGATION BUTTON -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition group tracking-wide uppercase">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
            </a>
            <div class="text-[10px] font-mono font-bold text-slate-600 tracking-wider uppercase">Infrastruktur & Arena</div>
        </div>
        
        <!-- 1. HEADER CONTROL BAR -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-[#152238] p-6 rounded-3xl border border-slate-800 shadow-2xl">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase">🏟️ Kelola Lapangan Futsal</h1>
                <p class="text-xs text-slate-400 mt-1">Tambah, edit, atau nonaktifkan infrastruktur arena tanding Futsal Mare.</p>
            </div>
            <a href="{{ route('admin.lapangan.create') }}" class="px-5 py-3 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl shadow-lg shadow-orange-950/40 tracking-wider uppercase transition transform hover:-translate-y-0.5">
                + Tambah Lapangan Baru
            </a>
        </div>

        <!-- 2. NOTIFIKASI SYSTEM SINKRON -->
        @if(session('success'))
            <div class="p-4 bg-emerald-950/40 border border-emerald-800/60 text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-md">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- 3. MAIN INVENTARIS GRID CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lapangans as $lapangan)
                <div class="bg-[#152238] rounded-2xl border border-slate-800 shadow-xl overflow-hidden flex flex-col group hover:border-slate-700 transition duration-300">
                    
                    <!-- AREA VISUAL DAN ASSET CHECKER FALLBACK -->
                    <div class="h-48 overflow-hidden bg-[#0F172A] relative">
                        @if($lapangan->foto_lapangan)
                            @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-600 text-sm font-bold uppercase tracking-widest">No Image</div>
                        @endif
                        <span class="absolute top-3 right-3 px-2.5 py-1 text-[9px] font-black bg-emerald-950/80 text-emerald-400 border border-emerald-900/40 rounded-lg uppercase tracking-wider backdrop-blur-sm">
                            🟢 Ready for Match
                        </span>
                    </div>

                    <!-- CARD BODY CONTENT -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-black text-white uppercase tracking-tight">{{ $lapangan->nama_lapangan }}</h3>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 bg-[#0B131F] text-slate-400 border border-slate-800 text-[10px] font-bold rounded-md uppercase">
                                    🌱 Rumput: {{ $lapangan->jenis_rumput }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium line-clamp-2 mt-3">
                                {{ $lapangan->deskripsi ?? 'Tidak ada deskripsi tambahan mengenai spesifikasi lapangan ini.' }}
                            </p>
                        </div>

                        <!-- BILLING AND CRUD CONTROL ACTION BUTTONS -->
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-black text-slate-500 uppercase block tracking-wider">Tarif Sewa</span>
                                <span class="text-sm font-black text-[#22C55E] font-mono">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span class="text-[10px] text-slate-500 font-normal">/jam</span></span>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}" class="p-2 bg-[#0B131F] border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-bold rounded-xl transition">
                                    ✏️ Edit
                                </a>
                                
                                <form action="{{ route('admin.lapangan.destroy', $lapangan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini dari database?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-950/30 border border-red-900/40 text-red-400 hover:bg-red-900/50 text-xs font-bold rounded-xl transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-16 text-center space-y-2 bg-[#152238] rounded-2xl border border-slate-800">
                    <div class="text-3xl">🥅</div>
                    <p class="text-slate-500 font-bold text-sm">Belum ada data lapangan futsal yang terdaftar.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>