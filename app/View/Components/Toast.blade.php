{{-- ============================================================
     resources/views/components/toast.blade.php

     CARA PAKAI:
     1. Taruh @include('components.toast') sekali saja di layout utama
        (layouts/admin.blade.php DAN layout member/public), idealnya
        tepat sebelum tag </body>.
     2. Otomatis muncul kalau ada session('success'), session('error'),
        atau validasi gagal ($errors->any()) — tidak perlu kode tambahan
        di controller selain redirect()->with('success', '...') yang
        sudah kamu pakai sekarang.
     3. Untuk trigger manual dari JavaScript (mis. respons AJAX Midtrans),
        panggil di mana saja setelah komponen ini dimuat:

            FMToast.success('Pembayaran berhasil dikonfirmasi!');
            FMToast.error('Slot jam sudah dipesan orang lain.');
            FMToast.warning('Sesi pembayaran akan berakhir dalam 2 menit.');
            FMToast.info('Menghubungkan ke gateway pembayaran...');

     ============================================================ --}}

<div id="fm-toast-container" aria-live="polite" aria-atomic="true"></div>

<style>
    #fm-toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        left: auto;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: min(380px, calc(100vw - 32px));
        pointer-events: none;
    }
    @media (max-width: 640px) {
        #fm-toast-container {
            top: 12px;
            right: 12px;
            width: calc(100vw - 24px);
        }
    }

    .fm-toast {
        pointer-events: auto;
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #0f1720;
        border: 1px solid rgba(238, 241, 234, 0.1);
        border-left: 4px solid var(--fm-toast-accent, #3B82F6);
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 20px 45px -12px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.02);
        overflow: hidden;
        font-family: 'Work Sans', system-ui, sans-serif;
        transform: translateX(120%);
        opacity: 0;
        transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
    }
    .fm-toast.fm-toast-visible { transform: translateX(0); opacity: 1; }
    .fm-toast.fm-toast-leaving { transform: translateX(120%); opacity: 0; }

    .fm-toast-icon {
        flex-shrink: 0;
        width: 30px; height: 30px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        background: var(--fm-toast-accent-bg, rgba(59,130,246,0.15));
        color: var(--fm-toast-accent, #3B82F6);
    }
    .fm-toast-body { flex: 1; min-width: 0; padding-top: 1px; }
    .fm-toast-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
        color: var(--fm-toast-accent, #3B82F6);
        margin: 0 0 3px 0;
    }
    .fm-toast-message {
        font-size: 13px; font-weight: 500; line-height: 1.45;
        color: #e6e9ef; margin: 0; word-break: break-word;
    }
    .fm-toast-close {
        flex-shrink: 0;
        background: transparent; border: none; cursor: pointer;
        color: #5c6979; font-size: 15px; line-height: 1;
        padding: 2px; margin: -2px -2px 0 0; border-radius: 6px;
        transition: color .15s ease, background .15s ease;
    }
    .fm-toast-close:hover { color: #fff; background: rgba(255,255,255,0.06); }

    .fm-toast-bar {
        position: absolute; left: 0; bottom: 0; height: 3px;
        background: var(--fm-toast-accent, #3B82F6);
        opacity: .55;
        animation: fm-toast-shrink linear forwards;
    }
    @keyframes fm-toast-shrink { from { width: 100%; } to { width: 0%; } }

    @media (prefers-reduced-motion: reduce) {
        .fm-toast { transition: opacity .2s ease; transform: none !important; }
        .fm-toast-bar { animation: none; display: none; }
    }
</style>

<script>
(function () {
    const container = document.getElementById('fm-toast-container');

    const THEMES = {
        success: { accent: '#22C55E', bg: 'rgba(34,197,94,0.15)',  icon: '✅', label: 'Berhasil' },
        error:   { accent: '#EF4444', bg: 'rgba(239,68,68,0.15)',  icon: '⚠️', label: 'Gagal' },
        warning: { accent: '#F59E0B', bg: 'rgba(245,158,11,0.15)', icon: '⏳', label: 'Perhatian' },
        info:    { accent: '#3B82F6', bg: 'rgba(59,130,246,0.15)', icon: 'ℹ️', label: 'Info' },
    };

    function show(type, message, duration = 5000) {
        if (!message) return;
        const theme = THEMES[type] || THEMES.info;

        const el = document.createElement('div');
        el.className = 'fm-toast';
        el.style.setProperty('--fm-toast-accent', theme.accent);
        el.style.setProperty('--fm-toast-accent-bg', theme.bg);
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');

        el.innerHTML = `
            <div class="fm-toast-icon">${theme.icon}</div>
            <div class="fm-toast-body">
                <p class="fm-toast-title">${theme.label}</p>
                <p class="fm-toast-message"></p>
            </div>
            <button type="button" class="fm-toast-close" aria-label="Tutup notifikasi">✕</button>
            <div class="fm-toast-bar" style="animation-duration: ${duration}ms;"></div>
        `;
        // set message via textContent, bukan innerHTML, supaya aman dari injeksi HTML
        el.querySelector('.fm-toast-message').textContent = message;

        container.appendChild(el);
        requestAnimationFrame(() => el.classList.add('fm-toast-visible'));

        let dismissTimer = setTimeout(() => dismiss(el), duration);

        el.addEventListener('mouseenter', () => clearTimeout(dismissTimer));
        el.addEventListener('mouseleave', () => { dismissTimer = setTimeout(() => dismiss(el), 1500); });

        el.querySelector('.fm-toast-close').addEventListener('click', () => {
            clearTimeout(dismissTimer);
            dismiss(el);
        });
    }

    function dismiss(el) {
        el.classList.add('fm-toast-leaving');
        el.classList.remove('fm-toast-visible');
        el.addEventListener('transitionend', () => el.remove(), { once: true });
    }

    window.FMToast = {
        show,
        success: (msg, duration) => show('success', msg, duration),
        error:   (msg, duration) => show('error', msg, duration),
        warning: (msg, duration) => show('warning', msg, duration),
        info:    (msg, duration) => show('info', msg, duration),
    };

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            window.FMToast.success(@json(session('success')));
        @endif

        @if(session('error'))
            window.FMToast.error(@json(session('error')));
        @endif

        @if ($errors->any())
            window.FMToast.error(@json($errors->count() > 1
                ? $errors->count() . ' kolom belum valid — periksa detail di bawah form.'
                : $errors->first()
            ));
        @endif
    });
})();
</script>