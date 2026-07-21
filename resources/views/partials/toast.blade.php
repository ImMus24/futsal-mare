@if (session('success') || session('error') || session('info'))
<div class="fixed top-5 right-5 z-50 max-w-sm w-full transition-all duration-300">
    @if (session('success'))
        <div class="p-4 rounded-xl shadow-lg bg-emerald-900/90 border border-emerald-500 text-emerald-100 flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-3 opacity-70 hover:opacity-100">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl shadow-lg bg-rose-900/90 border border-rose-500 text-rose-100 flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-3 opacity-70 hover:opacity-100">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div class="p-4 rounded-xl shadow-lg bg-sky-900/90 border border-sky-500 text-sky-100 flex items-center justify-between">
            <span>{{ session('info') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-3 opacity-70 hover:opacity-100">&times;</button>
        </div>
    @endif
</div>
@endif