/* =====================
   LibraBase — app.js
   ===================== */

// Live clock
function updateClock() {
    const el = document.getElementById('live-time');
    if (!el) return;
    const now = new Date();
    el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}
updateClock();
setInterval(updateClock, 1000);

// Update sidebar total count after DataTable initializes
function updateSidebarCount() {
    const totalEl = document.getElementById('totalBooks');
    if (!totalEl) return;
    const table = document.getElementById('booksTable');
    if (table) {
        const rows = table.querySelectorAll('tbody tr');
        totalEl.textContent = rows.length;
    }
}

// Initialize DataTable
$(document).ready(function () {
    if ($('#booksTable').length) {
        var table = $('#booksTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            responsive: true,
            columnDefs: [
                { orderable: false, targets: -1 } // disable sort on Actions column
            ],
            language: {
                search: 'Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_–_END_ of _TOTAL_ books',
                infoEmpty: 'No books available',
                emptyTable: 'No books in the library yet',
                paginate: {
                    previous: '&lsaquo;',
                    next: '&rsaquo;'
                }
            },
            initComplete: function () {
                updateSidebarCount();
            }
        });
    }

    // ---- Flash / Session Notifications ----
    const flashEl = document.querySelector('.flash-data');
    if (flashEl) {
        const type = flashEl.dataset.type;
        const msg = flashEl.dataset.msg;

        const iconMap = { success: 'success', error: 'error', warning: 'warning' };

        Swal.fire({
            icon: iconMap[type] || 'info',
            title: type === 'success' ? 'Success!' : 'Oops!',
            text: msg,
            timer: 2800,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
    }

    // ---- Delete Confirmation ----
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Delete Book?',
            html: `Are you sure you want to delete <strong>${title}</strong>? This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = 'index.php?action=delete&id=' + id;
            }
        });
    });

    // ---- Client-side form validation ----
    const form = document.getElementById('bookForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            let valid = true;

            const required = [
                { id: 'title', label: 'Title' },
                { id: 'author', label: 'Author' },
                { id: 'genre', label: 'Genre' },
                { id: 'isbn', label: 'ISBN' },
                { id: 'year', label: 'Year' },
            ];

            required.forEach(function (field) {
                const el = document.getElementById(field.id);
                if (el && el.value.trim() === '') {
                    valid = false;
                    el.closest('.form-group').classList.add('has-error');
                    let err = el.closest('.form-group').querySelector('.error-msg');
                    if (!err) {
                        err = document.createElement('span');
                        err.className = 'error-msg';
                        el.closest('.form-group').appendChild(err);
                    }
                    err.textContent = field.label + ' is required.';
                } else if (el) {
                    el.closest('.form-group').classList.remove('has-error');
                    const err = el.closest('.form-group').querySelector('.error-msg');
                    if (err) err.textContent = '';
                }
            });

            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'Please fill in all required fields.',
                    confirmButtonText: 'OK'
                });
            }
        });

        // Live clear errors on input
        form.querySelectorAll('.form-control').forEach(function (el) {
            el.addEventListener('input', function () {
                el.closest('.form-group').classList.remove('has-error');
                const err = el.closest('.form-group').querySelector('.error-msg');
                if (err) err.textContent = '';
            });
        });
    }
});
