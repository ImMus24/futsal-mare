<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Reservasi #{{ $reservasi->nomor_reservasi ?? 'E-TICKET' }} - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 print:bg-white print:text-slate-800 scroll-smooth">

    <nav class="bg-[#0F172A]/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-slate-800 no-print">
        <div class="max-w-4xl mx-auto px-4 h-20 flex justify-between items-center">
            <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <a href="{{ route('dashboard') }}" class="text-xs font-black text-slate-400 hover:text-[#E25E20] uppercase tracking-wider transition duration-150">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <main class="max-w-md mx-auto px-4 py-12 print:py-0 print:px-0">
        <div class="bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden relative print:shadow-none print:border-none print:bg-white print:rounded-none">
            
            <div class="bg-gradient-to-r from-[#0F172A] to-[#1E293B] text-white p-6 text-center relative print:from-slate-100 print:to-slate-200 print:text-slate-800 print:border-b print:border-slate-300">
                <span class="px-3 py-1 bg-[#E25E20] text-white text-[9px] font-black rounded-full tracking-wider uppercase shadow-sm print:bg-slate-800">
                    E-Tiket Resmi
                </span>
                <h2 class="text-xs font-black mt-3 tracking-widest text-slate-400 print:text-slate-500 uppercase">Nomor Reservasi</h2>
                <p class="text-xl font-mono font-black text-[#E25E20] print:text-slate-900 tracking-wider uppercase mt-1">
                    {{ $reservasi->nomor_reservasi }}
                </p>
            </div>

            <div class="p-6 space-y-4 print:p-4">
                <div class="flex justify-between items-center pb-3 border-b border-dashed border-slate-800 print:border-slate-300">
                    <div>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Nama Pemesan</p>
                        <p class="text-sm font-black text-white print:text-slate-800">{{ auth()->user()->name ?? 'Pelanggan' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Status Pembayaran</p>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 print:bg-emerald-50 print:text-emerald-700 print:border-emerald-200 uppercase tracking-wide">
                            Lunas
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Arena Lapangan</p>
                        <p class="text-base font-black text-white print:text-slate-900 uppercase tracking-tight">{{ $reservasi->lapangan->nama_lapangan ?? 'Lapangan Utama' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Tanggal Main</p>
                            <p class="text-xs font-bold text-slate-300 print:text-slate-700">
                                {{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Jam Tanding</p>
                            <p class="text-xs font-bold text-slate-300 print:text-slate-700 font-mono">
                                {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center justify-center p-4 bg-[#0B131F] border border-slate-800 rounded-2xl my-4 print:bg-slate-50 print:border-slate-200">
                    @if($reservasi->qr_code_path && file_exists(public_path('images/qrcodes/' . $reservasi->qr_code_path)))
                        <img src="{{ asset('images/qrcodes/' . $reservasi->qr_code_path) }}" alt="QR Code E-Tiket" class="w-48 h-48 object-contain bg-white p-2 rounded-xl print:p-0">
                    @endif
                    <p class="text-[9px] font-mono font-bold text-slate-500 uppercase tracking-widest mt-2">Pindai Masuk Pengawas Arena</p>
                </div>

                <div class="bg-[#0B131F] rounded-xl p-4 flex justify-between items-center border border-slate-800 print:bg-slate-50 print:border-slate-200">
                    <div>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Total Biaya</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-wide print:text-slate-500">Metode: {{ str_replace('_', ' ', $reservasi->metode_pembayaran ?? 'QRIS') }}</p>
                    </div>
                    <p class="text-xl font-black text-[#22C55E] print:text-emerald-700">
                        Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="text-[10px] text-slate-500 space-y-1 pt-2 leading-relaxed print:text-slate-600">
                    <p class="font-bold text-slate-400 print:text-slate-700">📌 Ketentuan Lapangan:</p>
                    <p>• Datang 15 menit sebelum kick-off dimulai untuk persiapan tim.</p>
                    <p>• Wajib menggunakan sepatu futsal standar (non-cleat / tanpa pul besi).</p>
                    <p>• Tunjukkan e-tiket QR Code di atas kepada pengawas lapangan di lokasi untuk check-in.</p>
                </div>
            </div>

            <div class="p-6 bg-[#0B131F] border-t border-slate-800 flex gap-2 no-print">
                <button onclick="window.print()" class="flex-1 py-4 bg-[#E25E20] hover:bg-[#cb5119] text-white rounded-xl font-black text-xs tracking-widest uppercase shadow-md transition text-center duration-150">
                    🖨️ Cetak / Simpan PDF
                </button>
            </div>
        </div>
    </main>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { 
                background-color: white !important; 
                color: #1e293b !important;
            }
            main { 
                padding: 0 !important; 
                max-w: 100% !important;
            }
            /* Memaksa elemen berubah menjadi putih bersih saat dicetak */
            .print\:bg-white { background-color: #ffffff !important; }
            .print\:text-slate-800 { color: #1e293b !important; }
            .print\:text-slate-900 { color: #0f172a !important; }
            .print\:text-slate-700 { color: #334155 !important; }
            .print\:border-none { border-style: none !important; }
            .print\:shadow-none { box-shadow: none !important; }
            .print\:rounded-none { border-radius: 0 !important; }
        }
    </style>
</body>
</html>