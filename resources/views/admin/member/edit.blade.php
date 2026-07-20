<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Futsal Mare Admin</title>
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
            --color-surface-3:    #212d3c;
            --color-text-main:    #ffffff;
            --color-text-muted:   #94a3b8;
            --color-text-meta:    #5c6979;
            --line: rgba(238, 241, 234, 0.08);

            --success: #22C55E; --success-bg: rgba(34, 197, 94, 0.1);
            --danger:  #EF4444; --danger-bg:  rgba(239, 68, 68, 0.1);
            --gold: #f59e0b; --silver: #94a3b8; --bronze: #b45309;

            font-family: 'Work Sans', sans-serif;
            color: var(--color-text-main);
        }
        body { background: var(--color-bg-main); margin: 0; -webkit-font-smoothing: antialiased; }
        .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; text-transform: uppercase; }
        .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }

        .fm-scope .input-field {
            width: 100%; background: var(--color-surface-3); border: 1px solid rgba(238,241,234,0.12); color: var(--color-text-main);
            border-radius: 12px; padding: 12px 16px; font-size: 14px; font-weight: 700; transition: border-color .15s ease, box-shadow .15s ease;
        }
        .fm-scope .input-field:focus { border-color: var(--color-primary); outline: none; box-shadow: 0 0 0 3px rgba(226,94,32,.2); }
        .fm-scope .input-field.has-error { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
        .fm-scope .input-field:disabled { background: var(--color-bg-main); border-color: #1e293b; color: var(--color-text-meta); cursor: not-allowed; }
        .fm-scope .label-field {
            display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
            color: var(--color-text-muted); margin-bottom: 8px;
        }
        .fm-scope .field-error { display: flex; align-items: center; gap: 6px; font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--danger); margin-top: 7px; font-weight: 600; }
        .fm-scope .field-hint { font-size: 10px; color: var(--color-text-meta); margin-top: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
        .fm-scope :focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }

        .tier-preview {
            display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px;
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-left: 8px;
        }
    </style>
</head>
<body class="fm-scope antialiased">
    <main class="max-w-2xl mx-auto px-4 py-10 space-y-6">

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.member.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wide transition group"
               style="background: var(--color-bg-card); border: 1px solid var(--line); color: var(--color-text-muted);"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--color-text-muted)'">
                <span class="transform group-hover:-translate-x-1 transition duration-200">⬅️</span> Kembali ke Daftar Member
            </a>
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

        <div class="p-6 rounded-2xl shadow-2xl space-y-6" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div>
                <h1 class="f-display text-xl tracking-tight" style="color: var(--color-text-main);">✏️ Edit Informasi &amp; Poin Member</h1>
                <p class="text-xs mt-1.5" style="color: var(--color-text-muted);">Sesuaikan nama profil atau kelola saldo poin gamifikasi secara manual.</p>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-xl text-xs font-semibold" style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                    <p class="f-mono uppercase tracking-wide font-bold">⚠️ Perubahan Gagal Disimpan — periksa kolom yang ditandai di bawah.</p>
                </div>
            @endif

            <form action="{{ route('admin.member.update', $member->id) }}" method="POST" id="form_edit_member" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="label-field" for="name">Nama Member</label>
                    <input type="text" id="name" name="name" maxlength="255"
                           value="{{ old('name', $member->name) }}"
                           class="input-field @error('name') has-error @enderror"
                           aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" aria-describedby="err_name">
                    @error('name')
                        <div class="field-error" id="err_name">⚠ {{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="label-field" for="email">Email Akun (Read-Only)</label>
                    <input type="email" id="email" value="{{ $member->email }}" disabled class="input-field">
                    <div class="field-hint">Email tidak dapat diubah dari panel admin, demi keamanan akun.</div>
                </div>

                <div>
                    <label class="label-field" for="points">Saldo Poin Gamifikasi</label>
                    <input type="number" id="points" name="points" min="0" step="1"
                           value="{{ old('points', $member->membership->points ?? 0) }}"
                           class="input-field f-mono @error('points') has-error @enderror"
                           style="color: var(--color-secondary);"
                           oninput="previewTier(this.value)"
                           aria-invalid="{{ $errors->has('points') ? 'true' : 'false' }}" aria-describedby="err_points">
                    @error('points')
                        <div class="field-error" id="err_points">⚠ {{ $message }}</div>
                    @enderror
                    <p class="field-hint">
                        💡 Sistem otomatis mengkalkulasi ulang tier: <span id="tierPreview" class="tier-preview"></span>
                    </p>
                    <div class="field-hint" style="text-transform: none; letter-spacing: normal; margin-top: 4px;">
                        Ambang batas — Bronze: 0–99 · Silver: 100–299 · Gold: 300+
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="btn_submit"
                            class="w-full py-3 font-black text-xs rounded-xl uppercase tracking-wider shadow-lg transition"
                            style="background: var(--color-primary); color: #fff; box-shadow: 0 12px 26px -10px rgba(226,94,32,0.5);">
                        <span id="btn_submit_label">💾 Simpan Perubahan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function previewTier(points) {
            const val = parseInt(points || 0, 10);
            const el = document.getElementById('tierPreview');
            let label, bg, color;

            if (val >= 300) {
                label = '🏆 Gold'; bg = 'rgba(245,158,11,0.15)'; color = 'var(--gold)';
            } else if (val >= 100) {
                label = '🥈 Silver'; bg = 'rgba(148,163,184,0.15)'; color = 'var(--silver)';
            } else {
                label = '🥉 Bronze'; bg = 'rgba(180,83,9,0.15)'; color = 'var(--bronze)';
            }

            el.textContent = label;
            el.style.background = bg;
            el.style.color = color;
        }

        document.addEventListener('DOMContentLoaded', function () {
            previewTier(document.getElementById('points').value);
        });

        document.getElementById('form_edit_member').addEventListener('submit', function () {
            const btn = document.getElementById('btn_submit');
            const label = document.getElementById('btn_submit_label');
            btn.disabled = true;
            btn.style.opacity = '0.7';
            label.textContent = 'Menyimpan...';
        });
    </script>
</body>
</html>