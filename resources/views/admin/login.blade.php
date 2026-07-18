<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Futsal Mare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    {{-- Selaras dengan design system Futsal Mare (landing page & dashboard admin) --}}
    <style>
        .fm-scope {
            --color-primary:      #e2601f;
            --color-primary-dark: #b8481a;
            --color-secondary:    #f5c518;
            --color-bg-main:      #121a23;
            --color-bg-card:      #0a0f14;
            --color-bg-raised:    #1a2431;
            --color-text-main:    #ffffff;
            --color-text-muted:   #94a3b8;
            --color-text-meta:    #5c6979;
            --line: rgba(238, 241, 234, 0.08);
            --line-2: rgba(238, 241, 234, 0.14);
            --danger: #EF4444; --danger-bg: rgba(239, 68, 68, 0.1);

            font-family: 'Work Sans', sans-serif;
            color: var(--color-text-main);
        }
        .fm-scope .f-display { font-family: 'Anton', sans-serif; font-weight: 400; letter-spacing: .01em; }
        .fm-scope .f-mono { font-family: 'JetBrains Mono', monospace; }
        .fm-glow {
            background: radial-gradient(ellipse 700px 400px at 50% -10%, rgba(226,96,31,0.14), transparent 60%), var(--color-bg-main);
        }
        .fm-live-pip { animation: fm-pulse 1.6s infinite; }
        @keyframes fm-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .3; } }
        @media (prefers-reduced-motion: reduce) { .fm-live-pip { animation: none; } }
        .fm-scope input:focus { border-color: var(--color-primary) !important; }
        .fm-scope button:focus-visible, .fm-scope a:focus-visible { outline: 2px solid var(--color-secondary); outline-offset: 2px; }
        .fm-btn-primary { background: var(--color-primary); color: #fff; transition: background .18s ease; }
        .fm-btn-primary:hover { background: var(--color-primary-dark); }

        .fm-divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; }
        .fm-divider::before, .fm-divider::after { content: ""; flex: 1; height: 1px; background: var(--line); }
        .fm-divider span { font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; color: var(--color-text-meta); }

        .fm-btn-google {
            display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%;
            background: var(--color-bg-raised); border: 1px solid var(--line); color: var(--color-text-main);
            padding: 12px; border-radius: 10px; font-size: 13px; font-weight: 600; transition: border-color .18s ease, background .18s ease;
        }
        .fm-btn-google:hover { border-color: var(--line-2); background: #212c39; }
    </style>
</head>
<body class="fm-scope fm-glow flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        <!-- BADGE / IDENTITAS -->
        <div class="flex flex-col items-center mb-6">
            <div class="w-14 h-14 rounded-lg flex items-center justify-center f-display text-2xl shadow-lg"
                 style="background: var(--color-primary); color: #fff; transform: rotate(-2deg);">
                M
            </div>
            <h1 class="f-display text-sm uppercase tracking-widest mt-4" style="color: var(--color-text-main);">
                Futsal Mare HQ
            </h1>
            <p class="text-[10px] font-medium uppercase tracking-[0.2em] mt-1" style="color: var(--color-text-meta); font-family: 'JetBrains Mono', monospace;">
                Akses Konsol Admin
            </p>
        </div>

        <!-- KARTU LOGIN -->
        <div class="rounded-2xl shadow-2xl p-8 relative overflow-hidden" style="background: var(--color-bg-card); border: 1px solid var(--line);">
            <div class="absolute top-0 left-0 right-0 h-[3px]" style="background: linear-gradient(90deg, var(--color-primary), var(--color-secondary), transparent 85%);"></div>

            <div class="flex items-center justify-between mb-6">
                <h2 class="f-display text-xl uppercase tracking-wide" style="color: var(--color-text-main);">Login Admin</h2>
                <span class="inline-flex items-center gap-1.5 text-[9px] font-semibold px-2.5 py-1.5 rounded-md uppercase tracking-widest"
                      style="background: var(--color-bg-main); border: 1px solid var(--line); color: var(--color-secondary); font-family: 'JetBrains Mono', monospace;">
                    <span class="w-1.5 h-1.5 rounded-full fm-live-pip" style="background: var(--color-secondary);"></span> Aman
                </span>
            </div>

            @if(session('error'))
                <div class="flex items-start gap-2 px-4 py-3 rounded-lg mb-5 text-sm" style="background: var(--danger-bg); border: 1px solid rgba(239,68,68,0.3); color: var(--danger);">
                    <span class="mt-0.5">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-[10px] font-semibold uppercase tracking-widest mb-2" style="color: var(--color-text-meta);">Email</label>
                    <input type="email" id="email" name="email" required autofocus
                           class="w-full px-3.5 py-2.5 rounded-lg text-sm outline-none transition"
                           style="background: rgba(255,255,255,0.03); border: 1px solid var(--line); color: var(--color-text-main);"
                           placeholder="nama@futsalmare.com">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-[10px] font-semibold uppercase tracking-widest mb-2" style="color: var(--color-text-meta);">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3.5 py-2.5 rounded-lg text-sm outline-none transition"
                           style="background: rgba(255,255,255,0.03); border: 1px solid var(--line); color: var(--color-text-main);"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="fm-btn-primary w-full py-3 f-display text-sm uppercase tracking-widest rounded-lg transition">
                    Masuk Konsol
                </button>
            </form>

            <div class="fm-divider"><span>Atau</span></div>

            <a href="{{ route('auth.google') }}" class="fm-btn-google">
                <svg width="16" height="16" viewBox="0 0 48 48" aria-hidden="true">
                    <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.6-6 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.2-.1-2.4-.4-3.5z"/>
                    <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 15.9 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 7 29.6 5 24 5c-7.6 0-14.1 4.3-17.3 10.6z"/>
                    <path fill="#4CAF50" d="M24 44c5.5 0 10.4-1.9 14.2-5.1l-6.6-5.4C29.4 35.4 26.8 36 24 36c-5.3 0-9.7-3.4-11.3-8.1l-6.5 5C9.9 39.6 16.4 44 24 44z"/>
                    <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l6.6 5.4C41.5 35.8 44 30.4 44 24c0-1.2-.1-2.4-.4-3.5z"/>
                </svg>
                Masuk dengan Akun Google
            </a>
            <p class="text-[10px] mt-3 text-center" style="color: var(--color-text-meta);">
                Hanya akun dengan akses admin yang akan diarahkan ke konsol.
            </p>
        </div>

        <p class="text-center text-[10px] uppercase tracking-widest mt-6" style="color: var(--color-text-meta); font-family: 'JetBrains Mono', monospace;">
            &copy; {{ date('Y') }} Futsal Mare &middot; Akses Terbatas
        </p>
    </div>

</body>
</html>