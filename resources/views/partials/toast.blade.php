@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())
<div id="fm-toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-md w-full px-4 pointer-events-none">
    
    <style>
        @keyframes fm-toast-slide {
            from { opacity: 0; transform: translateY(-12px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes fm-progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        .fm-toast-item {
            animation: fm-toast-slide .35s cubic-bezier(.22, 1, .36, 1) forwards;
            pointer-events: auto;
            backdrop-filter: blur(12px);
            box-shadow: 0 16px 36px -12px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,0.08) inset;
            transition: opacity .3s ease, transform .3s ease;
        }
        .fm-toast-progress {
            animation: fm-progress 4.5s linear forwards;
        }
    </style>

    {{-- SUCCESS TOAST --}}
    @if (session('success'))
    <div class="fm-toast-item flex items-start gap-3.5 p-4 rounded-xl text-white"
         style="background: rgba(18, 26, 36, 0.95); border-left: 4px solid var(--success);">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-base"
             style="background: var(--success-bg); border: 1px solid var(--success-border);">
            ⚡
        </div>
        <div class="flex-1 pt-0.5">
            <h5 class="f-display text-xs uppercase tracking-wider" style="color: var(--success);">Berhasil Eksekusi</h5>
            <p class="text-xs font-medium mt-0.5 text-slate-300 leading-relaxed">{{ session('success') }}</p>
        </div>
        <button onclick="this.closest('.fm-toast-item').remove()" class="text-slate-500 hover:text-white transition-colors text-sm font-bold px-1.5 py-0.5">
            ✕
        </button>
        <div class="absolute bottom-0 left-0 h-[2px]" style="background: var(--success); width: 100%;">
            <div class="fm-toast-progress h-full" style="background: var(--success);"></div>
        </div>
    </div>
    @endif

    {{-- ERROR TOAST --}}
    @if (session('error'))
    <div class="fm-toast-item flex items-start gap-3.5 p-4 rounded-xl text-white relative overflow-hidden"
         style="background: rgba(18, 26, 36, 0.95); border-left: 4px solid var(--danger);">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-base"
             style="background: var(--danger-bg); border: 1px solid var(--danger-border);">
            ⚠️
        </div>
        <div class="flex-1 pt-0.5">
            <h5 class="f-display text-xs uppercase tracking-wider" style="color: var(--danger);">Peringatan Kesalahan</h5>
            <p class="text-xs font-medium mt-0.5 text-slate-300 leading-relaxed">{{ session('error') }}</p>
        </div>
        <button onclick="this.closest('.fm-toast-item').remove()" class="text-slate-500 hover:text-white transition-colors text-sm font-bold px-1.5 py-0.5">
            ✕
        </button>
        <div class="absolute bottom-0 left-0 h-[2px]" style="background: var(--danger); width: 100%;">
            <div class="fm-toast-progress h-full" style="background: var(--danger);"></div>
        </div>
    </div>
    @endif

    {{-- WARNING TOAST --}}
    @if (session('warning'))
    <div class="fm-toast-item flex items-start gap-3.5 p-4 rounded-xl text-white relative overflow-hidden"
         style="background: rgba(18, 26, 36, 0.95); border-left: 4px solid var(--pending);">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-base"
             style="background: var(--pending-bg); border: 1px solid var(--pending-border);">
            🔔
        </div>
        <div class="flex-1 pt-0.5">
            <h5 class="f-display text-xs uppercase tracking-wider" style="color: var(--pending);">Perhatian Sistem</h5>
            <p class="text-xs font-medium mt-0.5 text-slate-300 leading-relaxed">{{ session('warning') }}</p>
        </div>
        <button onclick="this.closest('.fm-toast-item').remove()" class="text-slate-500 hover:text-white transition-colors text-sm font-bold px-1.5 py-0.5">
            ✕
        </button>
        <div class="absolute bottom-0 left-0 h-[2px]" style="background: var(--pending); width: 100%;">
            <div class="fm-toast-progress h-full" style="background: var(--pending);"></div>
        </div>
    </div>
    @endif

    {{-- INFO TOAST --}}
    @if (session('info'))
    <div class="fm-toast-item flex items-start gap-3.5 p-4 rounded-xl text-white relative overflow-hidden"
         style="background: rgba(18, 26, 36, 0.95); border-left: 4px solid var(--info);">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-base"
             style="background: var(--info-bg); border: 1px solid var(--info-border);">
            ℹ️
        </div>
        <div class="flex-1 pt-0.5">
            <h5 class="f-display text-xs uppercase tracking-wider" style="color: var(--info);">Informasi Sistem</h5>
            <p class="text-xs font-medium mt-0.5 text-slate-300 leading-relaxed">{{ session('info') }}</p>
        </div>
        <button onclick="this.closest('.fm-toast-item').remove()" class="text-slate-500 hover:text-white transition-colors text-sm font-bold px-1.5 py-0.5">
            ✕
        </button>
        <div class="absolute bottom-0 left-0 h-[2px]" style="background: var(--info); width: 100%;">
            <div class="fm-toast-progress h-full" style="background: var(--info);"></div>
        </div>
    </div>
    @endif

    {{-- VALIDATION ERRORS TOAST --}}
    @if ($errors->any())
    <div class="fm-toast-item flex items-start gap-3.5 p-4 rounded-xl text-white relative overflow-hidden"
         style="background: rgba(18, 26, 36, 0.95); border-left: 4px solid var(--danger);">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-base"
             style="background: var(--danger-bg); border: 1px solid var(--danger-border);">
            ❌
        </div>
        <div class="flex-1 pt-0.5">
            <h5 class="f-display text-xs uppercase tracking-wider" style="color: var(--danger);">Validasi Gagal</h5>
            <ul class="text-xs font-medium mt-1 text-slate-300 space-y-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="this.closest('.fm-toast-item').remove()" class="text-slate-500 hover:text-white transition-colors text-sm font-bold px-1.5 py-0.5">
            ✕
        </button>
        <div class="absolute bottom-0 left-0 h-[2px]" style="background: var(--danger); width: 100%;">
            <div class="fm-toast-progress h-full" style="background: var(--danger);"></div>
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Otomatis hilangkan toast setelah 4.8 detik
        const toasts = document.querySelectorAll('.fm-toast-item');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px) scale(0.96)';
                setTimeout(() => toast.remove(), 300);
            }, 4500);
        });
    });
</script>
@endif