<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Mare - Reservasi Lapangan Futsal Premium Kota Baubau</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth selection:bg-[#E25E20] selection:text-white">

    <!-- NAVIGATION BAR -->
    <nav class="bg-[#0F172A]/70 backdrop-blur-xl shadow-2xl sticky top-0 z-50 border-b border-slate-800/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Brand Identity -->
                <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                    <div class="relative flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#E25E20] to-orange-500 rounded-xl filter blur-md opacity-20 group-hover:opacity-40 transition duration-300"></div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Futsal Mare" class="h-12 w-auto object-contain transform group-hover:rotate-6 transition duration-300 relative z-10">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                        <span class="text-[10px] font-black text-[#E25E20] tracking-[0.3em] uppercase mt-0.5">Mare</span>
                    </div>
                </a>

                <!-- Centered Navigation Links (Membership Link Integrated) -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="nav-link text-xs font-black uppercase tracking-wider text-slate-200 hover:text-[#E25E20] transition duration-200">Beranda</a>
                    <a href="#fitur-unggulan" class="nav-link text-xs font-black uppercase tracking-wider text-slate-400 hover:text-white transition duration-200">Keunggulan</a>
                    <a href="#program-membership" class="nav-link text-xs font-black uppercase tracking-wider text-[#E25E20] hover:text-orange-400 transition duration-200">⭐ Info Member</a>
                    <a href="#daftar-lapangan" class="nav-link text-xs font-black uppercase tracking-wider text-slate-400 hover:text-white transition duration-200">Katalog Arena</a>
                    <a href="#kontak-footer" class="nav-link text-xs font-black uppercase tracking-wider text-slate-400 hover:text-white transition duration-200">Kontak Info</a>
                </div>

                <!-- User Session Actions -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-950/80 border border-slate-800 hover:border-slate-700/80 rounded-xl text-[9px] font-black uppercase tracking-wider text-slate-400 hover:text-white transition duration-200 shadow-inner">
                        🛡️ Portal Admin
                    </a>

                    @if (Route::has('login'))
                        @auth
                            <div class="flex items-center gap-3 border-l border-slate-800/80 pl-4">
                                <a href="{{ route('dashboard') }}" class="text-xs font-black uppercase tracking-wider text-slate-300 hover:text-[#E25E20] transition duration-200">
                                    🏟️ Dashboard Member
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black uppercase tracking-wider text-red-400 bg-red-950/20 border border-red-950/40 px-2.5 py-1.5 rounded-xl hover:bg-red-900/30 transition duration-200">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-wider text-slate-300 hover:text-[#E25E20] transition duration-200">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="relative inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-[#E25E20] to-orange-600 hover:from-[#cb5119] hover:to-orange-700 text-white rounded-xl text-xs font-black shadow-lg shadow-orange-950/40 transform hover:-translate-y-0.5 transition duration-200 uppercase tracking-wider">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="relative bg-gradient-to-b from-[#0F172A]/40 via-[#0B131F] to-[#0E1726] text-white overflow-hidden py-24 lg:py-36 border-b border-slate-900/60">
        <div class="absolute inset-0 pointer-events-none opacity-[0.02] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#E25E20] rounded-full filter blur-[150px] opacity-10 pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-600 rounded-full filter blur-[150px] opacity-5 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                
                <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl text-[10px] font-black bg-[#152238]/60 text-[#E25E20] tracking-widest uppercase border border-slate-800 backdrop-blur-sm shadow-inner shadow-black/40">
                        <span class="w-2 h-2 rounded-full bg-[#E25E20] animate-pulse"></span>
                        Sistem Informasi Reservasi Digital Kota Baubau
                    </span>
                    <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl xl:text-6xl leading-[1.15] uppercase">
                        Main Futsal Gak Pake <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#E25E20] via-orange-400 to-yellow-500">Ribet & Mengantre</span>!
                    </h1>
                    <p class="text-sm sm:text-base text-slate-400 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                        Pilih arena terbaik kelompokmu, dapatkan kepastian jadwal bertanding secara real-time, dan amankan slot bermain Anda dalam hitungan detik terintegrasi payment gateway.
                    </p>
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="#daftar-lapangan" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-[#E25E20] to-orange-600 hover:from-[#cb5119] hover:to-orange-700 text-white font-black text-xs rounded-xl shadow-xl shadow-orange-950/50 uppercase tracking-widest transition duration-200 transform hover:-translate-y-0.5">
                            Lihat Jadwal Arena &darr;
                        </a>
                        <a href="#program-membership" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition duration-200 uppercase tracking-widest">
                            Pelajari Benefit Member
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md lg:max-w-none aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl border border-slate-800/80 transform lg:rotate-2 hover:rotate-0 transition duration-500 group bg-[#152238]">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B131F] via-transparent to-transparent z-10 opacity-60"></div>
                        @if(file_exists(public_path('images/hero-futsal.png')))
                            <img src="{{ asset('images/hero-futsal.png') }}" alt="Aksi Futsal Profesional" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700 filter brightness-90">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-700 border-2 border-dashed border-slate-800 p-8 text-center uppercase tracking-widest text-xs font-black">
                                ⚽ Asset Placeholder<br><span class="text-[9px] font-mono lowercase text-slate-500 mt-2">images/hero-futsal.png</span>
                            </div>
                        @endif
                        <div class="absolute bottom-5 left-5 right-5 z-20 bg-[#0F172A]/90 backdrop-blur-md text-white px-4 py-3 rounded-2xl text-[10px] font-black shadow-2xl border border-slate-800 flex items-center justify-between uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Arena Standar FIFA & Nasional
                            </span>
                            <span class="text-slate-500 font-mono">Kota Baubau</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- METRICS/HIGHLIGHTS SECTION -->
    <section id="fitur-unggulan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="bg-[#152238]/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-800/80 p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center divide-y sm:divide-y-0 sm:divide-x divide-slate-800">
            <div class="py-3 sm:py-0 space-y-1">
                <div class="text-xs text-[#E25E20] font-black tracking-widest uppercase">Fasilitas Inti</div>
                <div class="text-base font-black text-white uppercase tracking-wider">Standardisasi FIFA</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase">Kualitas Lapangan Rumput Terbaik</div>
            </div>
            <div class="py-4 sm:py-0 space-y-1">
                <div class="text-xs text-emerald-400 font-black tracking-widest uppercase">Manajemen Waktu</div>
                <div class="text-base font-black text-white uppercase tracking-wider">100% Fleksibel</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase">Atur Jam Main Sesuai Kebutuhan Tim</div>
            </div>
            <div class="py-3 sm:py-0 space-y-1">
                <div class="text-xs text-blue-400 font-black tracking-widest uppercase">Operasional</div>
                <div class="text-base font-black text-white uppercase tracking-wider">Lampu LED Terang</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase">Pencahayaan Optimal untuk Main Malam</div>
            </div>
        </div>
    </section>

    <!-- NEW CONTENT: INTERACTIVE MEMBERSHIP HIGHLIGHT SECTION -->
    <section id="program-membership" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <span class="text-[10px] font-black text-[#E25E20] tracking-[0.2em] uppercase bg-orange-950/40 border border-orange-900/30 px-3 py-1 rounded-xl">Sistem Kasta Loyalitas</span>
            <h2 class="text-3xl font-black text-white uppercase tracking-tight">Makin Sering Main, Makin Untung!</h2>
            <p class="text-xs text-slate-400 font-medium">Setiap sewa lapangan menyumbang <span class="text-white font-bold font-mono">+10 Poin</span>. Tingkatkan kasta tim Anda untuk potongan harga langsung.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Tier Bronze Card -->
            <div class="bg-[#152238] rounded-2xl p-6 border border-slate-800 relative overflow-hidden group hover:border-amber-800/60 transition duration-300">
                <div class="absolute -right-6 -bottom-6 text-6xl opacity-5 group-hover:opacity-10 transition">🥉</div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-amber-800 to-amber-700 px-2.5 py-1 rounded-lg">🥉 Tier Bronze</span>
                    <span class="text-xs font-mono font-bold text-slate-500">Mulai Awal</span>
                </div>
                <ul class="space-y-2.5 text-xs font-semibold text-slate-400">
                    <li class="flex items-center gap-2">✔ Akumulasi Poin Aktif</li>
                    <li class="flex items-center gap-2 text-slate-600">❌ Tanpa Potongan Diskon</li>
                    <li class="flex items-center gap-2 text-slate-600">❌ Tanpa Akses Prioritas</li>
                </ul>
            </div>

            <!-- Tier Silver Card -->
            <div class="bg-[#152238] rounded-2xl p-6 border border-slate-800 relative overflow-hidden group hover:border-slate-500/40 transition duration-300">
                <div class="absolute -right-6 -bottom-6 text-6xl opacity-5 group-hover:opacity-10 transition">🥈</div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-slate-500 to-slate-400 px-2.5 py-1 rounded-lg">🥈 Tier Silver</span>
                    <span class="text-xs font-mono font-black text-slate-300">100 Poin</span>
                </div>
                <ul class="space-y-2.5 text-xs font-semibold text-slate-300">
                    <li class="flex items-center gap-2">✔ Seluruh Benefit Bronze</li>
                    <li class="flex items-center gap-2 text-emerald-400">✔ Diskon Otomatis 5% / Sewa</li>
                    <li class="flex items-center gap-2 text-slate-600">❌ Tanpa Akses Prioritas</li>
                </ul>
            </div>

            <!-- Tier Gold Card -->
            <div class="bg-gradient-to-br from-[#152238] to-[#1c3050] rounded-2xl p-6 border border-slate-800 relative overflow-hidden group hover:border-amber-500/40 transition duration-300 shadow-xl">
                <div class="absolute inset-0 bg-amber-500/5 filter blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="absolute -right-6 -bottom-6 text-6xl opacity-10 group-hover:opacity-15 transition">🏆</div>
                <div class="flex items-center justify-between border-b border-slate-800/80 pb-3 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-wider bg-gradient-to-r from-amber-500 to-yellow-400 text-slate-950 px-2.5 py-1 rounded-lg font-bold shadow-md">🏆 Tier Gold</span>
                    <span class="text-xs font-mono font-black text-amber-400">300 Poin</span>
                </div>
                <ul class="space-y-2.5 text-xs font-bold text-slate-200">
                    <li class="flex items-center gap-2">✔ Seluruh Benefit Silver</li>
                    <li class="flex items-center gap-2 text-emerald-400">✔ Diskon Otomatis 10% / Sewa</li>
                    <li class="flex items-center gap-2 text-amber-500 animate-pulse">⚡ Akses Sistem Prioritas 24/7</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ARENA CATALOG SECTION -->
    <section id="daftar-lapangan" class="bg-[#0E1726] border-t border-b border-slate-900/60 py-24 mt-20 relative">
        <div class="absolute inset-0 pointer-events-none opacity-[0.01] bg-[radial-gradient(#ffffff_1px,transparent_1px)] bg-[size:40px_40px]"></div>
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 border-b border-slate-800/60 pb-6 text-center md:text-left">
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight sm:text-4xl uppercase">Pilihan Arena Terbaik di Baubau</h2>
                    <p class="text-slate-400 mt-2 text-xs font-bold uppercase tracking-wider">Investasikan kenyamanan bermain tim Anda dengan fasilitas lapangan berkelas nasional.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex h-1.5 w-24 bg-gradient-to-r from-[#E25E20] to-orange-500 rounded-full"></span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($lapangans as $lapangan)
                    <div class="group bg-[#152238] rounded-3xl shadow-xl border border-slate-800 overflow-hidden hover:shadow-2xl hover:shadow-orange-950/20 hover:border-slate-700/80 transform hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="relative h-56 bg-[#0B131F] overflow-hidden">
                                @if($lapangan->foto_lapangan)
                                    @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                        <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 opacity-90 group-hover:opacity-100 filter brightness-95">
                                    @else
                                        <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 opacity-90 group-hover:opacity-100 filter brightness-95">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-700 bg-[#0B131F] text-xs font-black uppercase tracking-widest border border-slate-800/40">No Image Found</div>
                                @endif

                                <span class="absolute top-4 right-4 px-3 py-1.5 bg-[#E25E20] text-white text-[9px] font-black rounded-xl shadow-md tracking-widest uppercase z-20 shadow-orange-950/60">
                                    🌱 {{ $lapangan->jenis_rumput }}
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

                                <div class="pt-4 border-t border-slate-800/80 flex items-baseline">
                                    <span class="text-2xl font-black text-[#E25E20] tracking-tight font-mono">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 font-bold text-[10px] uppercase ml-1.5 tracking-wider">/ Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6">
                            <a href="{{ route('reservasi.create', $lapangan->id) }}" class="relative block w-full text-center py-4 bg-[#0B131F] border border-slate-800 hover:border-transparent hover:bg-gradient-to-r hover:from-[#E25E20] hover:to-orange-600 text-white font-black text-[11px] rounded-xl transition-all duration-300 tracking-widest uppercase shadow-md shadow-black/30">
                                Booking Arena Sekarang
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-[#152238] rounded-3xl border border-slate-800 text-slate-500 font-bold uppercase tracking-wider text-xs">
                        ⚽ Belum ada data katalog arena lapangan futsal yang terdaftar saat ini.
                    </div>
                @endforelse
            </div>
        </main>
    </section>

    <!-- FOOTER SECTION -->
    <footer id="kontak-footer" class="bg-[#070C14] text-slate-500 pt-16 pb-12 border-t-4 border-[#E25E20]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="text-white text-lg font-black tracking-wider uppercase">Futsal Mare HQ</span>
                    <span class="text-[#E25E20] font-black">•</span>
                    <span class="text-[10px] font-black text-slate-600 tracking-widest uppercase">Sistem Reservasi Digital</span>
                </div>
                <p class="text-xs text-slate-500 max-w-md mx-auto mb-8 leading-relaxed font-medium">
                    Sistem penyewaan lapangan futsal terintegrasi di Kota Baubau. Amankan jadwal tanding tim Anda kapan saja dan di mana saja secara praktis.
                </p>
                <div class="w-full border-t border-slate-900 my-6"></div>
                <p class="text-[9px] font-mono font-bold text-slate-700 tracking-wider uppercase">
                    &copy; {{ date('Y') }} Futsal Mare. Dikembangkan untuk Proyek Mata Kuliah Web Programming. Seluruh Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE SMOOTH SCROLL WITH COMPENSATED HEADER OFFSET -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.nav-link, a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    
                    if (targetId === '#') {
                        e.preventDefault();
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        return;
                    }

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        e.preventDefault();
                        
                        const navbarHeight = 80; // Sesuai dengan tinggi h-20 di Tailwind
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>