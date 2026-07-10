<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $lapangan->nama_lapangan }} - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-[#0B131F] font-sans antialiased text-slate-200 scroll-smooth">

    <nav class="bg-[#0F172A]/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
            <a href="{{ route('landingPage') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto object-contain transform group-hover:rotate-6 transition duration-300">
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white tracking-wider leading-none">FUTSAL</span>
                    <span class="text-[10px] font-bold text-[#E25E20] tracking-widest uppercase mt-0.5">Mare</span>
                </div>
            </a>
            <a href="{{ route('landingPage') }}" class="text-xs font-black text-slate-400 hover:text-white uppercase tracking-wider flex items-center gap-1 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="bg-[#152238] rounded-3xl shadow-2xl border border-slate-800 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <div class="lg:col-span-5 bg-gradient-to-br from-[#0F172A] via-[#111C2C] to-[#080D16] text-white p-8 flex flex-col justify-between relative overflow-hidden min-h-[400px] lg:min-h-none border-b lg:border-b-0 lg:border-r border-slate-800">
                <div class="absolute inset-0 pointer-events-none opacity-[0.03] bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:2rem_2rem]"></div>
                
                <div class="relative z-10 space-y-6">
                    <div>
                        <span class="px-3 py-1.5 bg-[#E25E20] text-white text-[9px] font-black rounded-xl shadow-md tracking-widest uppercase">
                            Tipe: {{ $lapangan->jenis_rumput ?? 'Sintetis' }}
                        </span>
                        <h2 class="text-3xl font-black mt-4 tracking-tight leading-tight uppercase">{{ $lapangan->nama_lapangan }}</h2>
                        <p class="text-slate-400 text-xs font-medium mt-2 leading-relaxed">
                            Dilengkapi papan skor digital, sistem pencahayaan lampu LED malam hari yang terang, serta sirkulasi udara optimal standar turnamen fiksasi.
                        </p>
                    </div>

                    <div class="rounded-2xl overflow-hidden aspect-[16/10] bg-[#0B131F] border border-slate-800 shadow-xl relative">
                        @if($lapangan->foto_lapangan)
                            @if(file_exists(public_path('images/lapangan/' . $lapangan->foto_lapangan)))
                                <img src="{{ asset('images/lapangan/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover opacity-95">
                            @else
                                <img src="{{ asset('images/' . $lapangan->foto_lapangan) }}" alt="{{ $lapangan->nama_lapangan }}" class="w-full h-full object-cover opacity-95">
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-600 text-xs font-black uppercase tracking-widest">No Image</div>
                        @endif
                    </div>

                    <div class="bg-[#0B131F] rounded-xl p-4 border border-slate-800/80 space-y-2 text-[11px] text-slate-400">
                        <p class="font-black text-[#E25E20] uppercase tracking-wider">💡 Info Tarif Fleksibel (Dynamic Pricing):</p>
                        <p class="flex justify-between border-b border-slate-800/50 pb-1"><span>• Jam Sibuk (16:00 - 21:00)</span> <span class="text-slate-300 font-bold">+Rp 50.000 / Jam</span></p>
                        <p class="flex justify-between"><span>• Akhir Pekan (Sabtu & Minggu)</span> <span class="text-slate-300 font-bold">+Rp 20.000 / Jam</span></p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-800 mt-8 relative z-10 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] text-slate-500 font-black uppercase tracking-wider">Tarif Sewa Base</p>
                        <p class="text-3xl font-black text-[#E25E20] mt-0.5">
                            Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}<span class="text-xs text-slate-500 font-normal"> / Jam</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-950/60 text-emerald-400 border border-emerald-900/40 uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Arena Aktif
                        </span>
                    </div>
                </div>
            </div>

            <form id="form_reservasi" action="{{ route('reservasi.store') }}" method="POST" class="lg:col-span-7 p-8 sm:p-10 space-y-6 relative max-h-[85vh] overflow-y-auto custom-scrollbar bg-[#152238]">
                @csrf
                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">1. Tentukan Tanggal Main</label>
                    <input type="date" id="input_tanggal" name="tanggal_main" value="{{ $tanggal_pilihan }}" min="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-sm font-semibold focus:border-[#E25E20] focus:ring-[#E25E20] transition p-3.5 shadow-inner"
                        onchange="gantiTanggal(this.value)">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">2. Pilih Jam Mulai Tanding</label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                        @php $hasChecked = false; @endphp
                        @for ($jam = 8; $jam <= 21; $jam++)
                            @php
                                $isBooked = in_array($jam, $jam_terpesan);
                                $jam_format = sprintf('%02d:00', $jam);
                                $shouldCheck = (!$isBooked && !$hasChecked);
                                if ($shouldCheck) { $hasChecked = true; }
                            @endphp
                            <label class="relative">
                                <input type="radio" name="jam_mulai" value="{{ $jam }}" class="peer sr-only" 
                                    {{ $isBooked ? 'disabled' : '' }} 
                                    onchange="hitungTotal()" 
                                    {{ $shouldCheck ? 'checked' : '' }}>
                                <div class="w-full text-center py-3.5 rounded-xl border text-xs font-black tracking-wide transition cursor-pointer select-none
                                    {{ $isBooked 
                                        ? 'bg-[#0B131F]/40 border-slate-800 text-slate-600 cursor-not-allowed line-through' 
                                        : 'bg-[#0B131F] border-slate-800 text-slate-300 hover:border-slate-600 peer-checked:bg-[#E25E20] peer-checked:text-white peer-checked:border-transparent peer-checked:shadow-lg peer-checked:shadow-orange-950/50' }}">
                                    {{ $jam_format }}
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">3. Durasi Bermain</label>
                    <select name="durasi" id="input_durasi" onchange="hitungTotal()"
                        class="w-full rounded-xl bg-[#0B131F] border-slate-800 text-white text-sm font-semibold focus:border-[#E25E20] focus:ring-[#E25E20] transition p-3.5 shadow-inner">
                        <option value="1">1 Jam Sewa</option>
                        <option value="2" selected>2 Jam Main (Sangat Direkomendasikan)</option>
                        <option value="3">3 Jam Sewa</option>
                    </select>
                </div>

                <div class="bg-blue-950/40 border border-blue-900/40 rounded-2xl p-4 flex items-start gap-3">
                    <span class="text-blue-400 mt-0.5">🔒</span>
                    <div class="text-[11px] text-blue-400 leading-relaxed font-medium">
                        <p class="font-black uppercase tracking-wider mb-0.5">Payment Gateway Aktif</p>
                        Pembayaran aman via Midtrans. Mendukung otomatisasi QRIS (Gopay/Dana/OVO), Virtual Account Bank, dan Paylater tanpa perlu konfirmasi manual.
                    </div>
                </div>

                <div class="bg-[#0B131F] rounded-2xl border border-slate-800 p-5 space-y-2.5">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-500">
                        <span>Harga Lapangan / Jam (Base)</span>
                        <span class="text-slate-300">Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full h-px bg-slate-800 my-1"></div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-white uppercase tracking-wider">Estimasi Total Bayar</span>
                            <span class="text-[9px] text-slate-500 font-bold font-mono tracking-wide" id="rincian_surcharge"></span>
                        </div>
                        <span class="text-xl font-black text-[#22C55E]" id="live_total_harga">Rp 0</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btn_submit" class="w-full py-4 bg-[#0B131F] border border-slate-800 hover:border-transparent hover:bg-[#E25E20] text-white rounded-xl font-black text-xs tracking-widest uppercase transition-all duration-300 shadow-md">
                        Kunci Jadwal Arena &rarr;
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const hargaPerJam = {{ $lapangan->harga_per_jam }};

        function gantiTanggal(tanggal) {
            window.location.href = "?tanggal_main=" + tanggal;
        }

        function hitungTotal() {
            const inputTanggal = document.getElementById('input_tanggal').value;
            const selectDurasi = document.getElementById('input_durasi').value;
            const radioJam = document.querySelector('input[name="jam_mulai"]:checked');
            
            let startHour = radioJam ? parseInt(radioJam.value) : null;
            let durasi = parseInt(selectDurasi);
            let total = 0;
            let infoSurcharge = [];

            if (!startHour) {
                document.getElementById('live_total_harga').innerText = "Pilih jam dahulu";
                document.getElementById('rincian_surcharge').innerText = "";
                return;
            }

            const parts = inputTanggal.split('-');
            const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
            const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);

            for (let i = 0; i < durasi; i++) {
                let currentHour = startHour + i;
                let hargaSlot = hargaPerJam;

                if (currentHour >= 16 && currentHour < 22) {
                    hargaSlot += 50000;
                }
                if (isWeekend) {
                    hargaSlot += 20000;
                }
                total += hargaSlot;
            }

            if (isWeekend) infoSurcharge.push("Biaya Weekend (+Rp20k/jam)");
            if (startHour >= 16 || (startHour + durasi) > 16) infoSurcharge.push("Biaya Peak Hour (+Rp50k/jam)");

            document.getElementById('live_total_harga').innerText = "Rp " + total.toLocaleString('id-ID');
            document.getElementById('rincian_surcharge').innerText = infoSurcharge.join(' | ');
        }

        window.addEventListener('DOMContentLoaded', () => {
            hitungTotal();
        });

        // AJAX ASYNC SUBMIT HANDLING
        document.getElementById('form_reservasi').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const radioJam = document.querySelector('input[name="jam_mulai"]:checked');
            if (!radioJam) {
                alert("Silakan tentukan pilihan slot jam main Anda terlebih dahulu!");
                return;
            }
            
            const btnSubmit = document.getElementById('btn_submit');
            btnSubmit.disabled = true;
            btnSubmit.innerText = "MEMPROSES KONTRAK...";

            const formData = new FormData(this);

            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    throw new Error(data?.message || `Terjadi kendala server (Status: ${response.status})`);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = data.redirect;
                        },
                        onPending: function(result) {
                            window.location.href = data.redirect;
                        },
                        onError: function(result) {
                            alert("Proses transaksi dihentikan atau gagal dikirim.");
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML = "Kunci Jadwal Arena &rarr;";
                        },
                        onClose: function() {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    alert("Gagal mengamankan slot: " + data.message);
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = "Kunci Jadwal Arena &rarr;";
                }
            })
            .catch(error => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = "Kunci Jadwal Arena &rarr;";
                alert(error.message);
            });
        });
    </script>
</body>
</html>