import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Logika Global untuk Modal dan Toast
document.addEventListener('alpine:init', () => {
    Alpine.data('golaUI', () => ({
        // Fungsi Modal
        openModal(id) {
            document.getElementById(id).classList.add('show');
        },
        closeModal(id) {
            document.getElementById(id).classList.remove('show');
        },

        // Fungsi Toast
        showToast(type, msg) {
            const container = document.getElementById('toast-container');
            if (!container) return;
            const box = document.createElement('div');
            box.className = `toast ${type}`;
            box.innerHTML = `<span class="t-ic">${type === 'ok' ? '✓' : '✕'}</span><span>${msg}</span>`;
            container.appendChild(box);
            setTimeout(() => {
                box.style.opacity = '0';
                box.style.transition = 'opacity .3s';
                setTimeout(() => box.remove(), 300);
            }, 3000);
        },

        // Fungsi Spinner Tombol
        runButtonSpinner(btnId, originalText = 'Konfirmasi') {
            const btn = document.getElementById(btnId);
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Memproses...';
            // Simulasi proses - Anda bisa mengganti ini dengan fetch() ke server
            setTimeout(() => {
                btn.innerHTML = '<span class="check-ic">✓</span> Berhasil';
                btn.disabled = false;
                setTimeout(() => { btn.innerHTML = originalText; }, 1000);
            }, 1400);
        }
    }));
});

Alpine.start();