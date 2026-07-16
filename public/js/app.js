document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (t) => {
                    t.onmouseenter = Swal.stopTimer;
                    t.onmouseleave = Swal.resumeTimer;
                }
            });
            var flash = document.getElementById('flash-data');
            if (flash) {
                var type = flash.dataset.type || 'success';
                var msg = flash.dataset.message;
                if (msg) {
                    Toast.fire({
                        icon: type,
                        title: msg
                    });
                    if (type === 'success') {
                        setTimeout(function() {
                            confetti({
                                particleCount: 80,
                                spread: 70,
                                origin: {
                                    y: 0.6
                                },
                                colors: ['#16382b', '#5fa876', '#d9822b']
                            });
                        }, 300);
                    }
                }
            }
        });
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-hapus');
            if (!btn) return;
            e.preventDefault();
            var form = btn.closest('form');
            if (!form) return;
            Swal.fire({
                title: 'Hapus ' + (btn.dataset.label || 'item ini') + '?',
                html: 'Data yang dihapus <b>tidak bisa</b> dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0503c',
                cancelButtonColor: '#8a9480',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) form.submit();
            });
        });
        document.addEventListener('submit', function(e) {
            var form = e.target.closest('form[data-confirm]');
            if (!form) return;
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                html: form.dataset.confirm || 'Yakin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16382b',
                cancelButtonColor: '#8a9480',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) form.submit();
            });
        });
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-confirm]');
            if (!btn) return;
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                html: btn.dataset.confirm || 'Yakin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16382b',
                cancelButtonColor: '#8a9480',
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then(function(r) {
                if (r.isConfirmed) {
                    var f = btn.closest('form');
                    if (f) f.submit();
                }
            });
        });