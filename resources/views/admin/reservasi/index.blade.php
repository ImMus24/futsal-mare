@extends('layouts.admin')

@section('content')

{{-- ============================================================
     DESIGN SYSTEM — Futsal Mare (selaras dashboard, login, landing page)
     ============================================================ --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .fm-scope {
        --color-primary:      #e25e20;
        --color-primary-dark: #cb5119;
        --color-secondary:    #f5c518;
        --color-bg-main:      #121a23;
        --color-bg-card:      #0a0f14;
        --color-text-main:    #ffffff;
        --color-text-muted:   #94a3b8;
        --color-text-meta:    #5c6979;
        --line:               rgba(238, 241, 234, 0.08);

        --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
        --pending: #F59E0B; --pending-bg: rgba(245, 158, 11, 0.1);
        --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);
        --info:    #3B82F6; --info-bg:    rgba(59, 130, 246, 0.1);

        font-family: 'Work Sans', sans-serif;
        color: var(--color-text-main);
    }
    .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
    .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
    .fm-scope select, .fm-scope option { font-family: 'Work Sans', sans-serif; }
    .fm-scope a:focus-visible, .fm-scope button:focus-visible, .fm-scope select:focus-visible {
        outline: 2px solid var(--color-secondary); outline-offset: 2px;
    }
</style>

<div class="fm-scope space-y-6 animate-fade-in">

    <!-- 0. TOP NAVIGATION -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wide transition group"
           style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
           onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
            <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Dashboard
        </a>
        <div class="f-mono text-[10px] font-semibold tracking-wider uppercase" style="color: var(--color-text-meta);">Data Audit Transaksi</div>
    </div>

    <!-- 1. HEADER HERO WIDGET -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 rounded-2xl shadow-2xl relative overflow-hidden"
         style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(226,94,32,0.18), transparent 70%);"></div>
        <div class="relative z-10">
            <span class="eyebrow inline-flex items-center gap-2 f-mono text-[11px] font-semibold uppercase tracking-widest" style="color: var(--color-primary);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: var(--color-primary); animation: fm-pulse 1.6s infinite;"></span>
                Log Sistem
            </span>
            <h1 class="f-display text-2xl uppercase tracking-tight mt-1.5" style="color: var(--color-text-main);">📅 Log Data Reservasi</h1>
            <p class="text-xs mt-1.5 max-w-lg" style="color: var(--color-text-muted);">Pantau jadwal masuk, kendalikan status pembayaran, dan lakukan audit transaksi lapangan.</p>
        </div>

        <div class="relative z-10 flex items-center gap-3">
            <a href="{{ route('admin.reservasi.exportExcel', ['status' => request('status')]) }}"
               class="px-5 py-3 text-white font-semibold text-xs rounded-xl tracking-wider uppercase transition-all duration-200 flex items-center gap-2 transform hover:-translate-y-0.5"
               style="background: linear-gradient(120deg, #16a34a, #0d9488); box-shadow: 0 12px 26px -10px rgba(16,185,129,0.45);">
                📊 Unduh Excel (.xls)
            </a>
        </div>
    </div>

    <!-- FORM TERSEMBUNYI UNTUK HAPUS MASSAL — hidden inputs diisi via JS sebelum submit -->
    <form id="bulkDeleteForm" action="{{ route('admin.reservasi.deleteMassal') }}" method="POST"
          onsubmit="return confirm('Yakin ingin menghapus ' + document.querySelectorAll('.row-checkbox:checked').length + ' data reservasi terpilih secara permanen? Tindakan ini tidak dapat dibatalkan.')">
        @csrf
        @method('DELETE')
        <div id="bulkDeleteIdsContainer"></div>
    </form>

    <style>@keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }</style>

    <!-- 2. ALERTS NOTIFICATION BANNERS -->
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
        <div class="p-4 rounded-2xl text-xs font-semibold shadow-md"
             style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
            <p class="f-mono uppercase tracking-wide font-bold flex items-center gap-2"><span>⚠️</span> Aksi Gagal Diproses:</p>
            <ul class="list-disc list-inside mt-1.5 font-medium" style="opacity: 0.9;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3. SEARCH & FILTER CONTROL DECK -->
    <div class="p-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl"
         style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <form method="GET" action="{{ route('admin.reservasi.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <select name="status" onchange="this.form.submit()"
                    class="rounded-xl text-xs font-semibold px-4 py-2.5 min-w-[240px] cursor-pointer transition"
                    style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);">
                <option value="">Status Transaksi (Semua)</option>
                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>🟢 Berhasil (Confirmed)</option>
                <option value="Waiting Payment" {{ request('status') == 'Waiting Payment' ? 'selected' : '' }}>🟡 Menunggu Pembayaran</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>🔵 Selesai Main (Completed)</option>
                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>❌ Dibatalkan (Cancelled)</option>
            </select>
        </form>
        <div class="text-[11px] font-semibold self-end sm:self-auto uppercase tracking-wide" style="color: var(--color-text-muted);">
            Menampilkan
            <span class="f-mono font-bold px-1.5 py-0.5 rounded-md" style="color: #fff; background: var(--color-bg-main); border: 1px solid var(--line);">{{ $reservasis->total() }}</span>
            entri records lapangan.
        </div>
    </div>

    <!-- 4. MONITORING LOG DATA TABLE -->
    <div class="rounded-2xl shadow-2xl overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-[10px] font-semibold uppercase tracking-widest" style="background: var(--color-bg-main); border-bottom: 1px solid var(--line); color: var(--color-text-meta);">
                        <th class="py-4 px-5 w-10">
                            <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 rounded cursor-pointer" style="accent-color: var(--color-primary);" aria-label="Pilih semua baris">
                        </th>
                        <th class="py-4 px-6">ID / Transaksi</th>
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6">Arena</th>
                        <th class="py-4 px-6">Jadwal Tanding</th>
                        <th class="py-4 px-6 text-right">Total Bayar</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Aksi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-xs font-medium" style="border-color: var(--line); color: var(--color-text-muted);">
                    @forelse($reservasis as $reservasi)
                        <tr class="transition duration-150 row-selectable" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                            <td class="py-4 px-5">
                                <input type="checkbox" class="row-checkbox w-4 h-4 rounded cursor-pointer" style="accent-color: var(--color-primary);" value="{{ $reservasi->id }}" aria-label="Pilih reservasi #{{ $reservasi->id }}">
                            </td>
                            <!-- ID Transaksi -->
                            <td class="py-4 px-6">
                                <span class="f-mono font-bold block tracking-wide" style="color: var(--color-text-main);">{{ $reservasi->nomor_reservasi }}</span>
                                <span class="f-mono text-[10px] block mt-0.5" style="color: var(--color-text-meta);">ID Record: #{{ $reservasi->id }}</span>
                            </td>

                            <!-- Pelanggan -->
                            <td class="py-4 px-6">
                                <span class="font-semibold block uppercase tracking-wide text-sm" style="color: var(--color-text-main);">{{ $reservasi->user->name ?? 'User Terhapus' }}</span>
                                <span class="f-mono text-[10px] block mt-0.5" style="color: var(--color-text-meta);">{{ $reservasi->user->email ?? '-' }}</span>
                            </td>

                            <!-- Detail Arena -->
                            <td class="py-4 px-6">
                                <span class="font-bold uppercase tracking-wide" style="color: var(--color-primary);">{{ $reservasi->lapangan->nama_lapangan ?? 'Arena Terhapus' }}</span>
                                <span class="text-[10px] font-semibold block mt-0.5" style="color: var(--color-text-meta);">🌱 Rumput {{ $reservasi->lapangan->jenis_rumput ?? '-' }}</span>
                            </td>

                            <!-- Waktu Match -->
                            <td class="py-4 px-6">
                                <span class="block font-medium" style="color: var(--color-text-main);">{{ \Carbon\Carbon::parse($reservasi->tanggal_main)->translatedFormat('d F Y') }}</span>
                                <span class="f-mono text-[10px] font-semibold block mt-0.5" style="color: var(--info);">⏱️ {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }} WITA</span>
                            </td>

                            <!-- Nilai Finansial -->
                            <td class="py-4 px-6 text-right f-mono font-bold text-sm" style="color: var(--color-text-main);">
                                Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}
                            </td>

                            <!-- Status Badges -->
                            <td class="py-4 px-6 text-center">
                                @if($reservasi->status == 'Confirmed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: var(--success-bg); color: var(--success); border: 1px solid rgba(34,197,94,0.25);">🟢 Lunas (Confirmed)</span>
                                @elseif($reservasi->status == 'Waiting Payment')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: var(--pending-bg); color: var(--pending); border: 1px solid rgba(245,158,11,0.25);">🟡 Waiting Payment</span>
                                @elseif($reservasi->status == 'Completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: var(--info-bg); color: var(--info); border: 1px solid rgba(59,130,246,0.25);">🔵 Completed</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-semibold rounded-lg uppercase tracking-wider" style="background: var(--danger-bg); color: var(--danger); border: 1px solid rgba(239,68,68,0.25);">❌ Cancelled</span>
                                @endif
                            </td>

                            <!-- Konsol Interaksi Aksi -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Aksi Mutasi Status -->
                                    <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                                class="text-[10px] font-semibold rounded-xl px-2.5 py-1.5 cursor-pointer uppercase tracking-wider transition"
                                                style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-text-muted);">
                                            <option value="" disabled selected>⚙️ Opsi Status</option>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="Waiting Payment">Waiting Payment</option>
                                            <option value="Completed">Completed</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </form>

                                    <!-- Aksi Penghapusan Data Log -->
                                    <form action="{{ route('admin.reservasi.delete', $reservasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus record data reservasi ini dari log sistem secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-[10px] font-semibold rounded-xl uppercase tracking-wider transition duration-150"
                                                style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center font-semibold uppercase tracking-wider text-xs" style="color: var(--color-text-meta);">
                                <div class="text-3xl mb-2">📅</div>
                                Tidak ditemukan catatan data reservasi yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 5. PAGINATION -->
        @if($reservasis->hasPages())
            <div class="p-4 data-dark-pagination" style="background: var(--color-bg-main); border-top: 1px solid var(--line);">
                {{ $reservasis->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- FLOATING BULK ACTION BAR — muncul begitu ada baris dipilih -->
    <div id="bulkActionBar"
         class="fixed left-1/2 bottom-6 z-50 flex items-center gap-4 px-5 py-3.5 rounded-2xl shadow-2xl transition-all duration-200"
         style="background: var(--color-bg-card); border: 1px solid rgba(226,94,32,0.35); transform: translate(-50%, 24px); opacity: 0; pointer-events: none; box-shadow: 0 20px 50px -14px rgba(0,0,0,0.6);">
        <span class="f-mono text-xs font-semibold" style="color: var(--color-text-main);">
            <span id="bulkSelectedCount" style="color: var(--color-secondary);">0</span> data dipilih
        </span>
        <div style="width: 1px; height: 20px; background: var(--line);"></div>
        <button type="button" onclick="clearBulkSelection()"
                class="text-[11px] font-semibold uppercase tracking-wide transition"
                style="color: var(--color-text-muted);"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
            Batal Pilih
        </button>
        <button type="button" onclick="submitBulkDelete()"
                class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wide rounded-lg transition"
                style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.35); color: var(--danger);">
            🗑️ Hapus Terpilih
        </button>
    </div>
</div>

<script>
(function () {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkBar = document.getElementById('bulkActionBar');
    const bulkCountEl = document.getElementById('bulkSelectedCount');

    function getRowCheckboxes() {
        return Array.from(document.querySelectorAll('.row-checkbox'));
    }

    function refreshBulkBar() {
        const boxes = getRowCheckboxes();
        const checked = boxes.filter(cb => cb.checked);

        bulkCountEl.textContent = checked.length;

        if (checked.length > 0) {
            bulkBar.style.opacity = '1';
            bulkBar.style.transform = 'translate(-50%, 0)';
            bulkBar.style.pointerEvents = 'auto';
        } else {
            bulkBar.style.opacity = '0';
            bulkBar.style.transform = 'translate(-50%, 24px)';
            bulkBar.style.pointerEvents = 'none';
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = boxes.length > 0 && checked.length === boxes.length;
            selectAllCheckbox.indeterminate = checked.length > 0 && checked.length < boxes.length;
        }
    }

    selectAllCheckbox?.addEventListener('change', function () {
        getRowCheckboxes().forEach(cb => { cb.checked = selectAllCheckbox.checked; });
        refreshBulkBar();
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-checkbox')) {
            refreshBulkBar();
        }
    });

    window.clearBulkSelection = function () {
        getRowCheckboxes().forEach(cb => { cb.checked = false; });
        refreshBulkBar();
    };

    window.submitBulkDelete = function () {
        const checked = getRowCheckboxes().filter(cb => cb.checked);
        if (checked.length === 0) return;

        const container = document.getElementById('bulkDeleteIdsContainer');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('bulkDeleteForm').requestSubmit();
    };

    refreshBulkBar();
})();
</script>
@endsection