@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras dengan landing page & dashboard admin)
     Display: Anton · Body: Work Sans · Data: JetBrains Mono
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e2601f; /* turf orange — CTA & elemen aktif */
        --color-primary-dark: #b8481a;
        --color-secondary:    #f5c518; /* floodlight gold — highlight & ikon */
        --color-bg-main:      #121a23; /* navy-slate — latar utama */
        --color-bg-card:      #0a0f14; /* charcoal dalam — panel & tabel */
        --color-bg-raised:    #1a2431;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);
        --line-2:             rgba(238, 241, 234, 0.14);
        --ease: cubic-bezier(.22,1,.36,1);

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope .eyebrow {
        font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: .14em; text-transform: uppercase;
        color: var(--color-primary); display: inline-flex; align-items: center; gap: 8px; font-weight: 500;
    }
    .fm-scope .eyebrow::before { content: ""; width: 14px; height: 2px; background: var(--color-primary); display: inline-block; }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible, .fm-scope select:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }

    /* Alert notifikasi — mengikuti palet success/danger yang sama dengan badge status di dashboard */
    .fm-scope .fm-alert {
        padding: 14px 18px; border-radius: 10px; font-size: 13px; font-weight: 600;
        display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
        border: 1px solid transparent;
    }
    .fm-scope .fm-alert-success { background: var(--success-bg); border-color: rgba(34,197,94,0.3); color: var(--success); }
    .fm-scope .fm-alert-error   { background: var(--danger-bg);  border-color: rgba(239,68,68,0.3); color: var(--danger); }

    /* Badge status akses — biner sesuai kolom is_admin (0/1) */
    .fm-scope .fm-role-badge {
        display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    }
    .fm-scope .fm-role-admin  { background: rgba(245,197,24,0.1); color: var(--color-secondary); }
    .fm-scope .fm-role-member { background: var(--color-bg-main); color: var(--color-text-muted); border: 1px solid var(--line); }

    .fm-scope .fm-select {
        background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-main);
        border-radius: 8px; padding: 8px 12px; font-size: 12px; font-weight: 600;
        transition: border-color .18s ease;
    }
    .fm-scope .fm-select:hover, .fm-scope .fm-select:focus { border-color: var(--line-2); }
    .fm-scope .fm-select:disabled { opacity: .5; cursor: not-allowed; }
    .fm-scope .fm-select option { background: var(--color-bg-card); color: var(--color-text-main); }

    .fm-scope .fm-btn-save {
        background: var(--color-primary); color: #fff; border-radius: 8px; padding: 8px 16px;
        font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
        transition: background .18s ease, transform .18s var(--ease);
    }
    .fm-scope .fm-btn-save:hover { background: var(--color-primary-dark); transform: translateY(-1px); }
    .fm-scope .fm-btn-save:disabled { background: var(--color-bg-raised); color: var(--color-text-meta); cursor: not-allowed; transform: none; }

    .fm-scope .fm-self-tag {
        font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--color-text-meta);
        text-transform: uppercase; letter-spacing: .06em;
    }

    .fm-scope .fm-row { transition: background .15s ease; }
    .fm-scope .fm-row:hover { background: rgba(255,255,255,0.025); }

    .fm-scope .fm-avatar {
        width: 30px; height: 30px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; background: var(--color-bg-raised); color: var(--color-text-muted); flex-shrink: 0;
    }

    /* Search bar */
    .fm-scope .fm-search {
        background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-main);
        border-radius: 10px; padding: 10px 14px; font-size: 13px; min-width: 240px;
        transition: border-color .18s ease;
    }
    .fm-scope .fm-search::placeholder { color: var(--color-text-meta); }
    .fm-scope .fm-search:focus { border-color: var(--line-2); }
    .fm-scope .fm-btn-search {
        background: var(--color-bg-raised); color: var(--color-text-main); border-radius: 10px; padding: 10px 18px;
        font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
        transition: background .18s ease;
    }
    .fm-scope .fm-btn-search:hover { background: var(--color-primary); }

    /* Override pagination default Laravel (Tailwind) agar cocok dengan tema gelap */
    .fm-scope .fm-pagination nav { color: var(--color-text-muted); font-size: 13px; }
    .fm-scope .fm-pagination a, .fm-scope .fm-pagination span {
        color: var(--color-text-muted) !important; border-color: var(--line) !important; background: var(--color-bg-card) !important;
    }
    .fm-scope .fm-pagination span[aria-current="page"] span {
        background: var(--color-primary) !important; color: #fff !important; border-color: var(--color-primary) !important;
    }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    <!-- ============ HEADER ============ -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b pb-5" style="border-color: var(--line);">
        <div>
            <div class="eyebrow">Kontrol Akses</div>
            <h2 class="f-display uppercase tracking-tight mt-2" style="font-size: clamp(22px, 3vw, 30px); color: var(--color-text-main);">
                Manajemen Role Pengguna
            </h2>
            <p class="text-sm mt-1 font-medium" style="color: var(--color-text-muted);">
                Berikan atau cabut status Admin untuk tiap akun terdaftar.
            </p>
        </div>
        <form action="{{ route('admin.role.index') }}" method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email…" class="fm-search">
            <button type="submit" class="fm-btn-search">Cari</button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.role.index') }}" class="f-mono text-[11px]" style="color: var(--color-text-meta);">Reset</a>
            @endif
        </form>
    </div>

    <!-- ============ ALERT NOTIFIKASI ============ -->
    @if(session('success'))
        <div class="fm-alert fm-alert-success">
            <span>✔</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fm-alert fm-alert-error">
            <span>✕</span> {{ session('error') }}
        </div>
    @endif

    <!-- ============ TABEL PENGGUNA ============ -->
    <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead style="background: var(--color-bg-main); border-bottom: 1px solid var(--line);">
                    <tr class="text-[10px] font-semibold uppercase tracking-wider" style="color: var(--color-text-meta);">
                        <th class="p-5">Pengguna</th>
                        <th class="p-5">Email</th>
                        <th class="p-5">Status Akses</th>
                        <th class="p-5">Ubah Akses</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--line);">
                    @forelse($users as $user)
                        @php $isSelf = $user->id === auth()->id(); @endphp
                        <tr class="fm-row">
                            <td class="p-5 text-sm" style="color: var(--color-text-main);">
                                <span class="inline-flex items-center gap-2.5 font-semibold">
                                    <span class="fm-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    {{ $user->name }}
                                </span>
                            </td>
                            <td class="p-5 f-mono text-xs" style="color: var(--color-text-muted);">{{ $user->email }}</td>
                            <td class="p-5">
                                <span class="fm-role-badge {{ $user->is_admin == 1 ? 'fm-role-admin' : 'fm-role-member' }}">
                                    {{ $user->is_admin == 1 ? 'Admin' : 'Member' }}
                                </span>
                            </td>
                            <td class="p-5">
                                @if($isSelf)
                                    <span class="fm-self-tag">Akun Anda — tidak dapat diubah sendiri</span>
                                @else
                                    <form action="{{ route('admin.role.update', $user->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf @method('PUT')
                                        <select name="is_admin" class="fm-select">
                                            <option value="1" {{ $user->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="0" {{ $user->is_admin == 0 ? 'selected' : '' }}>Member</option>
                                        </select>
                                        <button type="submit" class="fm-btn-save">Simpan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-14 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-2xl">👤</span>
                                    <p class="text-sm font-semibold" style="color: var(--color-text-main);">
                                        {{ request()->filled('search') ? 'Tidak ada pengguna yang cocok dengan pencarian.' : 'Belum ada pengguna terdaftar' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="fm-pagination p-5" style="border-top: 1px solid var(--line);">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection