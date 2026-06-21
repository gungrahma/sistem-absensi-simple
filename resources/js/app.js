import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

const iconFor = (key) => {
    if (key === 'success' || key === 'status') return 'success';
    if (key === 'error')   return 'error';
    if (key === 'warning') return 'warning';
    if (key === 'info')    return 'info';
    return 'info';
};

const fireToast = (key, message) => {
    if (!message) return;
    Toast.fire({ icon: iconFor(key), title: message });
};

document.addEventListener('DOMContentLoaded', () => {
    const flash = window.flashMessages || {};

    Object.entries(flash).forEach(([key, message]) => fireToast(key, message));

    const errs = window.validationErrors || {};
    if (Object.keys(errs).length) {
        const list = Object.values(errs).flat().map((m) => `<li>${m}</li>`).join('');
        Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            html: `<ul class="text-left text-sm list-disc pl-5">${list}</ul>`,
        });
    }

    if (window.appDebug && window.appError) {
        Swal.fire({ icon: 'error', title: 'Error', text: window.appError });
    }

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const message = form.dataset.confirm || 'Yakin?';
            const title   = form.dataset.confirmTitle || 'Konfirmasi';
            const icon    = form.dataset.confirmIcon  || 'warning';
            const btnText = form.dataset.confirmText  || 'Ya, lanjutkan';
            Swal.fire({
                title,
                text: message,
                icon,
                showCancelButton: true,
                confirmButtonText: btnText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
