@if(session('success'))
    <div class="bg-emerald-950/80 border border-emerald-500 text-emerald-300 p-4 rounded-lg mb-4 text-xs font-black uppercase tracking-widest">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-950/80 border border-red-500 text-red-300 p-4 rounded-lg mb-4 text-xs font-black uppercase tracking-widest">
        {{ session('error') }}
    </div>
@endif