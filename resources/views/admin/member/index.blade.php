<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Member - Futsal Mare Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .fm-scope {
            --color-primary:      #e25e20;
            --color-primary-dark: #cb5119;
            --color-secondary:    #f5c518;
            --color-bg-main:      #121a23;
            --color-bg-card:      #0a0f14;
            --color-text-main:    #ffffff;
            --color-text-muted:   #94a3b8;
            --color-text-meta:    #5c6979;
            --line: rgba(238, 241, 234, 0.08);

            --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
            --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);
            --gold:    #f59e0b; --silver: #94a3b8; --bronze: #b45309;

            font-family: 'Work Sans', sans-serif;
            color: var(--color-text-main);
        }
        body { background: var(--color-bg-main); margin: 0; -webkit-font-smoothing: antialiased; }
        .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
        .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
        .fm-scope :focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }
    </style>
</head>
<body class="fm-scope antialiased">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wide transition group"
               style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
            </a>
            <div class="f-mono text-[10px] font-semibold tracking-wider uppercase" style="color: var(--color-text-meta);">Loyalitas & Gamifikasi</div>
        </div>

        <div class="p-6 rounded-2xl shadow-2xl relative overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,94,32,0.18), transparent 70%);"></div>
            <h1 class="f-display text-2xl uppercase tracking-tight relative z-10" style="color: var(--color-text-main);">👥 Kelola Data Member</h1>
            <p class="text-xs mt-1.5 relative z-10" style="color: var(--color-text-muted);">Pantau poin loyalitas pelanggan, sesuaikan status akun, dan lakukan moderasi user Futsal Mare.</p>
        </div>

        <!-- NOTIFIKASI -->
        @if(session('success'))
            <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
                 style="background: var(--success-bg); border: 1px solid rgba(34,197,94,0.3); color: var(--success);">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-md"
                 style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl text-xs font-semibold shadow-md" style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                <p class="f-mono uppercase tracking-wide font-bold flex items-center gap-2"><span>⚠️</span> Aksi Gagal Diproses:</p>
                <ul class="list-disc list-inside mt-1.5 font-medium" style="opacity: 0.9;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl shadow-xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="text-[10px] font-semibold uppercase tracking-widest" style="background: var(--color-bg-main); border-bottom: 1px solid var(--line); color: var(--color-text-meta);">
                            <th class="py-4 px-6">ID Member</th>
                            <th class="py-4 px-6">Nama Profil</th>
                            <th class="py-4 px-6">Email Sistem</th>
                            <th class="py-4 px-6 text-center">Tier Membership</th>
                            <th class="py-4 px-6 text-right">Poin Loyalitas</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-xs font-medium" style="border-color: var(--line); color: var(--color-text-muted);">
                        @forelse($members as $member)
                            <tr class="transition duration-150" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                <td class="py-4 px-6 f-mono" style="color: var(--color-text-meta);">#USR-{{ $member->id }}</td>
                                <td class="py-4 px-6 uppercase font-semibold tracking-wide" style="color: var(--color-text-main);">
                                    {{ $member->name }}
                                </td>
                                <td class="py-4 px-6 f-mono" style="color: var(--color-text-muted);">{{ $member->email }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if($member->membership)
                                        @if($member->membership->membership_type == 'Gold')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: rgba(245,158,11,0.15); color: var(--gold); border: 1px solid rgba(245,158,11,0.3);">🏆 Gold</span>
                                        @elseif($member->membership->membership_type == 'Silver')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: rgba(148,163,184,0.15); color: var(--silver); border: 1px solid rgba(148,163,184,0.3);">🥈 Silver</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: rgba(180,83,9,0.15); color: var(--bronze); border: 1px solid rgba(180,83,9,0.3);">🥉 Bronze</span>
                                        @endif
                                    @else
                                        <span class="inline-flex px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: var(--color-bg-main); color: var(--color-text-meta); border: 1px solid var(--line);">Reguler Guest</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right font-bold f-mono text-sm" style="color: var(--color-secondary);">
                                    {{ number_format($member->membership->points ?? 0, 0, ',', '.') }} PTS
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.member.edit', $member->id) }}"
                                           class="px-3 py-1.5 text-[10px] font-semibold rounded-xl transition uppercase tracking-wider"
                                           style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);"
                                           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                                            ✏️ Edit &amp; Poin
                                        </a>
                                        <form action="{{ route('admin.member.delete', $member->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun member ini? Data poin loyalitas ikut terhapus permanen.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-[10px] font-semibold rounded-xl transition uppercase tracking-wider"
                                                    style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-14 text-center font-semibold uppercase tracking-wider text-xs" style="color: var(--color-text-meta);">
                                    <div class="text-2xl mb-1">👥</div>
                                    Tidak ada data member terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($members->hasPages())
                <div class="p-4" style="background: var(--color-bg-main); border-top: 1px solid var(--line);">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>