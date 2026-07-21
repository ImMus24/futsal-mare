@if (session('success') || session('error') || session('info'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity.duration.300ms class="fixed top-5 right-5 z-50 max-w-sm w-full space-y-2">
    @if (session('success'))
        <div class="p-4 rounded-xl shadow-lg bg-emerald-900/90 border border-emerald-500 text-emerald-100 flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span class="text-xs font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="ml-3 opacity-70 hover:opacity-100 text-lg font-bold">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl shadow-lg bg-rose-900/90 border border-rose-500 text-rose-100 flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center gap-2">
                <span>⚠️</span>
                <span class="text-xs font-medium">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="ml-3 opacity-70 hover:opacity-100 text-lg font-bold">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div class="p-4 rounded-xl shadow-lg bg-sky-900/90 border border-sky-500 text-sky-100 flex items-center justify-between backdrop-blur-md">
            <div class="flex items-center gap-2">
                <span>ℹ️</span>
                <span class="text-xs font-medium">{{ session('info') }}</span>
            </div>
            <button @click="show = false" class="ml-3 opacity-70 hover:opacity-100 text-lg font-bold">&times;</button>
        </div>
    @endif
</div>
@endif