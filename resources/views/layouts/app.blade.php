<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            #toast-container { position: fixed; bottom: 24px; right: 24px; display: flex; flex-direction: column; gap: 10px; z-index: 9999; }
            .toast {
                background: #121a23; border: 1px solid rgba(238,241,234,.1); border-left: 4px solid #2f9e58;
                padding: 16px 20px; border-radius: 8px; font-size: 14px; color: #eef1ea;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 12px;
                animation: toastIn .3s ease;
            }
            .toast.err { border-left-color: #e2574c; }
            @keyframes toastIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- KONTAINER GLOBAL UNTUK TOAST -->
        <div id="toast-container"></div>

        <script>
            function showToast(type, msg) {
                const container = document.getElementById('toast-container');
                const box = document.createElement('div');
                box.className = 'toast ' + (type === 'err' ? 'err' : '');
                box.innerHTML = `
                    <span style="font-weight:900; font-size: 16px; color:${type === 'err' ? '#e2574c' : '#2f9e58'}">
                        ${type === 'err' ? '✕' : '✓'}
                    </span>
                    <span>${msg}</span>
                `;
                container.appendChild(box);
                
                // Hapus toast otomatis setelah 4 detik
                setTimeout(() => {
                    box.style.opacity = '0';
                    box.style.transition = 'opacity .4s';
                    setTimeout(() => box.remove(), 400);
                }, 4000);
            }

            // MENDENGARKAN FLASH MESSAGE DARI LARAVEL (Redirect)
            document.addEventListener('DOMContentLoaded', () => {
                @if(session('success'))
                    showToast('ok', "{{ session('success') }}");
                @endif

                @if(session('error'))
                    showToast('err', "{{ session('error') }}");
                @endif

                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        showToast('err', "{{ $error }}");
                    @endforeach
                @endif
            });
        </script>
    </body>
</html>