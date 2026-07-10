<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Futsal Mare Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] text-slate-200 font-sans antialiased">
    <main class="max-w-2xl mx-auto px-4 py-10 space-y-6">
        
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.member.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition group tracking-wide uppercase">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Daftar Member
            </a>
        </div>

        <div class="bg-[#152238] p-6 rounded-3xl border border-slate-800 shadow-2xl space-y-6">
            <div>
                <h1 class="text-xl font-black text-white uppercase tracking-tight">✏️ Edit Informasi & Poin Member</h1>
                <p class="text-xs text-slate-400 mt-1">Sesuaikan nama profil atau kelola saldo poin gamifikasi secara manual.</p>
            </div>

            <form action="{{ route('admin.member.update', $member->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Nama Member</label>
                    <input type="text" name="name" value="{{ old('name', $member->name) }}" class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-sm font-bold text-white focus:border-[#E25E20] focus:ring-0">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Email Akun (Read-Only)</label>
                    <input type="email" value="{{ $member->email }}" disabled class="w-full bg-[#0F172A] border border-slate-900 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Saldo Poin Gamifikasi</label>
                    <input type="number" name="points" value="{{ old('points', $member->membership->points ?? 0) }}" class="w-full bg-[#0B131F] border border-slate-800 rounded-xl px-4 py-3 text-sm font-mono font-bold text-amber-400 focus:border-[#E25E20] focus:ring-0">
                    <p class="text-[10px] text-slate-500 mt-1 font-semibold uppercase tracking-wide">
                        💡 Catatan: Sistem akan otomatis mengkalkulasi ulang Tier/Kasta berdasarkan jumlah poin ini.
                    </p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3 bg-[#E25E20] hover:bg-[#cb5119] text-white font-black text-xs rounded-xl uppercase tracking-wider shadow-lg shadow-orange-950/40 transition">
                        💾 Simpan Perubahan Data
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>