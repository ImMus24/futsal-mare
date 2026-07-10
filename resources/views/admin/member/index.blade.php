<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Member - Futsal Mare Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0B131F] text-slate-200 font-sans antialiased">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#152238] hover:bg-slate-800 border border-slate-800 rounded-xl text-xs font-black text-slate-300 transition group tracking-wide uppercase">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
            </a>
            <div class="text-[10px] font-mono font-bold text-slate-600 tracking-wider uppercase">Loyalitas & Gamifikasi</div>
        </div>
        
        <div class="bg-[#152238] p-6 rounded-3xl border border-slate-800 shadow-2xl">
            <h1 class="text-2xl font-black text-white tracking-tight uppercase">👥 Kelola Data Member</h1>
            <p class="text-xs text-slate-400 mt-1">Pantau poin loyalitas pelanggan, sesuaikan status akun, dan lakukan moderasi user Futsal Mare.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-950/40 border border-emerald-800/60 text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-md">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#152238] rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#0F172A] border-b border-slate-800/60 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <th class="py-4 px-6">ID Member</th>
                            <th class="py-4 px-6">Nama Profil</th>
                            <th class="py-4 px-6">Email Sistem</th>
                            <th class="py-4 px-6 text-center">Poin Loyalitas (Gamifikasi)</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-xs font-semibold">
                       @forelse($members as $member)
    <tr class="hover:bg-[#0B131F]/30 transition duration-150">
        <td class="py-4 px-6 font-mono text-slate-500">#USR-{{ $member->id }}</td>
        <td class="py-4 px-6 text-white uppercase font-bold tracking-wide">
            {{ $member->name }}
        </td>
        <td class="py-4 px-6 font-mono text-slate-300">{{ $member->email }}</td>
        <td class="py-4 px-6 text-center">
            @if($member->membership)
                @if($member->membership->membership_type == 'Gold')
                    <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-amber-500 text-white rounded-lg uppercase tracking-wider">🏆 Gold</span>
                @elseif($member->membership->membership_type == 'Silver')
                    <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-slate-500 text-white rounded-lg uppercase tracking-wider">🥈 Silver</span>
                @else
                    <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-amber-800 text-white rounded-lg uppercase tracking-wider">🥉 Bronze</span>
                @endif
            @else
                <span class="inline-flex px-2.5 py-1 text-[9px] font-black bg-slate-900 text-slate-500 border border-slate-800 rounded-lg uppercase tracking-wider">Reguler Guest</span>
            @endif
        </td>
        <td class="py-4 px-6 text-right font-bold text-emerald-400 font-mono text-sm">
            {{ number_format($member->membership->points ?? 0, 0, ',', '.') }} PTS
        </td>
        <td class="py-4 px-6 flex items-center justify-center gap-2">
            <a href="{{ route('admin.member.edit', $member->id) }}" class="px-3 py-1.5 bg-[#0B131F] border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 text-xs font-bold rounded-xl transition">
                ✏️ Edit & Poin
            </a>
            <form action="{{ route('admin.member.delete', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun member ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-red-950/30 border border-red-900/40 text-red-400 hover:bg-red-900/50 text-xs font-bold rounded-xl transition">
                    🗑️ Hapus
                </button>
            </form>
        </td>
    </tr>
@empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500 font-bold">
                                    <div class="text-2xl mb-1">👥</div>
                                    Tidak ada data member terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="p-4 bg-[#0F172A]/40 border-t border-slate-800">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>