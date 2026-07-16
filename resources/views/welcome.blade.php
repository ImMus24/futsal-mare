<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Mare - Reservasi Lapangan Mudah & Terpercaya Kota Baubau</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth">

    <!-- NAVIGATION BAR -->
    <nav class="bg-[#0F172A]/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                    <div class="relative flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Futsal Mare" class="h-14 w-auto object-contain transform group-hover:rotate-6 transition duration-300">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-black text-white tracking-wider leading-none group-hover:text-[#E25E20] transition duration-200">FUTSAL</span>
                        <span class="text-xs font-bold text-[#E25E20] tracking-[0.25em] uppercase mt-0.5">Mare</span>
                    </div>
                </a>

                <div class="flex items-center space-x-6">
                    <!-- 🛡️ PORTAL LOGIN ADMIN (MODERN & PROFESIONAL) -->
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-wider text-slate-400 hover:text-white transition duration-200 shadow-sm">
                        🛡️ Portal Admin
                    </a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-xs font-black uppercase tracking-wider text-slate-300 hover:text-[#E25E20] transition duration-200">
                                🏟️ Dashboard Member
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-black uppercase tracking-wider text-red-400 hover:text-red-500 transition duration-200">
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-wider text-slate-300 hover:text-[#E25E20] transition duration-200">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="relative inline-flex items-center justify-center px-5 py-2.5 bg-[#E25E20] hover:bg-[#cb5119] text-white rounded-xl text-xs font-black shadow-md shadow-orange-950/50 transform hover:-translate-y-0.5 transition duration-200 uppercase tracking-wider">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="relative bg-gradient-to-br from-[#0B131F] via-[#111C2C] to-[#080D16] text-white overflow-hidden py-24 lg:py-32 border-b border-slate-900">
        <div class="absolute inset-0 pointer-events-none opacity-[0.03] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#E25E20] rounded-full filter blur-[150px] opacity-15 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-[10px] font-black bg-slate-800/60 text-[#E25E20] tracking-widest uppercase border border-slate-700/50 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-[#E25E20] animate-pulse"></span>
                        Sistem Informasi Reservasi Digital Kota Baubau
                    </span>
                    <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl xl:text-6xl leading-[1.15]">
                        Main Futsal Gak Pake <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#E25E20] to-orange-400">Ribet & Mengantre</span>!
                    </h1>
                    <p class="text-base sm:text-lg text-slate-400 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                        Pilih arena terbaik kelompokmu, dapatkan kepastian jadwal bertanding secara real-time, dan amankan slot bermain Anda dalam hitungan detik.
                    </p>
                    <div class="pt-4">
                        <a href="#daftar-lapangan" class="inline-flex items-center justify-center px-6 py-4 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl shadow-lg shadow-orange-950/50 uppercase tracking-widest transition duration-200 transform hover:-translate-y-0.5">
                            Lihat Jadwal Arena &darr;
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md lg:max-w-none aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl border-4 border-slate-800 transform lg:rotate-2 hover:rotate-0 transition duration-500 group">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B131F]/80 via-transparent to-transparent z-10"></div>
                        <img src="{{ asset('images/hero-futsal.png') }}" alt="Aksi Futsal Profesional" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                        <div class="absolute bottom-5 left-5 z-20 bg-[#0F172A]/90 backdrop-blur-sm text-white px-4 py-2 rounded-xl text-[10px] font-black shadow-lg border border-slate-800 flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Arena Berstandar Nasional
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- METRICS/HIGHLIGHTS SECTION -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="bg-[#111C2C]/90 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-800 p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-800">
            <div class="py-3 sm:py-0">
                <div class="text-lg font-black text-white uppercase tracking-wider">Standardisasi FIFA</div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase">Kualitas Lapangan Internasional</div>
            </div>
            <div class="py-4 sm:py-0">
                <div class="text-lg font-black text-[#E25E20] uppercase tracking-wider">100% Fleksibel</div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase">Atur Jam Main Sesuai Kebutuhan Tim</div>
            </div>
            <div class="py-3 sm:py-0">
                <div class="text-lg font-black text-white uppercase tracking-wider">Lampu LED Terang</div>
                <div class="text-xs font-bold text-slate-500 mt-1 uppercase">Pencahayaan Sempurna untuk Main Malam</div>
            </div>
        </div>
    </section>

    <!-- ARENA CATALOG SECTION -->
    <section class="bg-[#0E1726] border-t border-b border-slate-900 mt-20">
        <main id="daftar-lapangan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 text-center md:text-left">
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight sm:text-4xl uppercase">Pilihan Arena Terbaik di Baubau</h2>
                    <p class="text-slate-500 mt-2 text-xs font-bold uppercase tracking-wider">Investasikan kenyamanan bermain tim Anda dengan fasilitas lapangan berkelas.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex h-1.5 w-24 bg-gradient-to-r from-[#E25E20] to-orange-400 rounded-full"></span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($lapangans as $lapangan)
                    <div class="group bg-[#152238] rounded-3xl shadow-lg border border-slate-800 overflow-hidden hover:shadow-2xl hover:shadow-orange-950/20 hover:border-slate-700 transform hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="relative h-56 bg-[#0B131F] overflow-hidden">
                                @if($lapangan->foto_lapangan)
                                    @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                        <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 opacity-90 group-hover:opacity-100">
                                    @else
                                        <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 opacity-90 group-hover:opacity-100">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-600 text-xs font-black uppercase tracking-widest">No Image</div>
                                @endif

                                <span class="absolute top-4 right-4 px-3 py-1.5 bg-[#E25E20] text-white text-[9px] font-black rounded-xl shadow-md tracking-widest uppercase z-20">
                                    {{ $lapangan->jenis_rumput }}
                                </span>
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-black text-white group-hover:text-[#E25E20] transition duration-200 mb-4 uppercase tracking-tight">
                                    {{ $lapangan->nama_lapangan }}
                                </h3>

                                <div class="flex flex-wrap gap-1.5 items-center text-[9px] font-black uppercase text-slate-400 mb-6">
                                    <span class="bg-[#0B131F] border border-slate-800 px-2.5 py-1 rounded-md">Papan Skor</span>
                                    <span class="bg-[#0B131F] border border-slate-800 px-2.5 py-1 rounded-md">LED System</span>
                                    <span class="bg-[#0B131F] border border-slate-800 px-2.5 py-1 rounded-md">Kota Baubau</span>
                                </div>

                                <div class="pt-4 border-t border-slate-800 flex items-baseline">
                                    <span class="text-2xl font-black text-[#E25E20] tracking-tight">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 font-bold text-[10px] uppercase ml-1.5 tracking-wider">/ Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6">
                            <a href="{{ route('reservasi.create', $lapangan->id) }}" class="relative block w-full text-center py-4 bg-[#0B131F] border border-slate-800 hover:border-transparent hover:bg-[#E25E20] text-white font-black text-[11px] rounded-xl transition-all duration-300 tracking-widest uppercase shadow-md">
                                Booking Arena Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-[#070C14] text-slate-500 pt-16 pb-12 border-t-4 border-[#E25E20]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="text-white text-lg font-black tracking-wider">FUTSAL MARE</span>
                    <span class="text-[#E25E20] font-black">•</span>
                    <span class="text-[10px] font-black text-slate-600 tracking-widest uppercase">Sistem Reservasi Digital</span>
                </div>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-8 leading-relaxed font-medium">
                    Sistem penyewaan lapangan futsal terintegrasi di Kota Baubau. Amankan jadwal tanding tim Anda kapan saja dan di mana saja.
                </p>
                <div class="w-full border-t border-slate-900 my-6"></div>
                <p class="text-[10px] font-mono font-bold text-slate-700 tracking-wider">
                    &copy; {{ date('Y') }} Futsal Mare. Dikembangkan untuk Proyek Mata Kuliah Web Programming. Seluruh Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>