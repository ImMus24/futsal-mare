<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Gate Scanner - Futsal Mare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-[#0F172A] font-sans antialiased text-slate-200 flex flex-col min-h-screen">

    <nav class="bg-[#1E293B] border-b border-slate-800 py-5 px-6 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <span class="text-xs font-black tracking-widest text-[#22C55E]">STAFF PANEL</span>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white transition">&larr; Dashboard</a>
        </div>
    </nav>

    <main class="max-w-md w-full mx-auto p-4 flex-grow flex flex-col justify-center space-y-6">
        
        <div class="text-center space-y-1">
            <h1 class="text-xl font-black text-white tracking-tight">E-Ticket Gate Validator</h1>
            <p class="text-xs text-slate-400">Arahkan kamera ke QR Code tiket untuk memproses Check-In pemain.</p>
        </div>

        <div class="bg-[#1E293B] border border-slate-800 rounded-3xl overflow-hidden p-4 shadow-2xl relative">
            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-slate-900 border-0"></div>
        </div>

        <div id="result_box" class="hidden p-4 rounded-2xl border text-xs font-bold text-center transition-all duration-300">
            <p id="result_message"></p>
        </div>

    </main>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Berhenti scan sementara setelah kode terdeteksi agar tidak melakukan request berkali-kali
            html5QrcodeScanner.clear();

            const resultBox = document.getElementById('result_box');
            const resultMessage = document.getElementById('result_message');

            resultBox.className = "p-4 rounded-2xl border text-xs font-bold text-center bg-slate-800 border-slate-700 text-slate-300";
            resultBox.classList.remove('hidden');
            resultMessage.innerText = "⏳ Sedang memverifikasi kode: " + decodedText;

            // Kirim nomor_reservasi ke backend menggunakan Fetch API (AJAX)
            fetch("{{ route('staff.checkin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    nomor_reservasi: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    resultBox.className = "p-4 rounded-2xl border text-xs font-bold text-center bg-emerald-950/40 border-emerald-500/30 text-emerald-400";
                    resultMessage.innerText = "✅ " + data.message;
                } else {
                    resultBox.className = "p-4 rounded-2xl border text-xs font-bold text-center bg-red-950/40 border-red-500/30 text-red-400";
                    resultMessage.innerText = "❌ " + data.message;
                }
                // Nyalakan kembali kamera scanner setelah jeda 4 detik
                setTimeout(() => { location.reload(); }, 4000);
            })
            .catch(error => {
                resultBox.className = "p-4 rounded-2xl border text-xs font-bold text-center bg-red-950/40 border-red-500/30 text-red-400";
                resultMessage.innerText = "⚠️ Terjadi gangguan koneksi sistem.";
                setTimeout(() => { location.reload(); }, 4000);
            });
        }

        // Inisialisasi konfigurasi HTML5-QRCode Scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }, /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>
</html>