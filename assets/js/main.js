/**
 * Mobooth Client Interactivity Logic
 * Decoupled client script to handle Modals, Sidebar Toggle, and Form population.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Mobile Sidebar Toggle ---
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    
    if (sidebar && sidebarToggle) {
        // Dynamically create an overlay to dim out the content body on mobile sidebar activation
        const sidebarOverlay = document.createElement('div');
        sidebarOverlay.className = 'sidebar-active-overlay';
        document.body.appendChild(sidebarOverlay);

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        });
    }

    // --- 2. Add / Edit Booth Modal Handlers ---
    const btnAddBooth = document.getElementById('btn-add-booth');
    const modalAdd = document.getElementById('modal-add');
    if (btnAddBooth && modalAdd) {
        btnAddBooth.addEventListener('click', function() {
            modalAdd.classList.add('open');
        });
    }

    const editBtns = document.querySelectorAll('.btn-edit');
    const modalEdit = document.getElementById('modal-edit');
    if (modalEdit) {
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const location = this.getAttribute('data-location');
                const status = this.getAttribute('data-status');
                const notes = this.getAttribute('data-notes');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-location').value = location;
                document.getElementById('edit-status').value = status;
                document.getElementById('edit-notes').value = notes;

                modalEdit.classList.add('open');
            });
        });
    }

    // --- 3. Delete Booth Confirmation ---
    const deleteBoothBtns = document.querySelectorAll('.delete-booth-btn');
    deleteBoothBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const boothName = this.getAttribute('data-name');
            Swal.fire({
                title: 'HAPUS BOOTH?',
                text: `Apakah Anda yakin ingin menghapus booth "${boothName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });

    // --- 3b. Donasi Modal Trigger ---
    const btnShowDonasi = document.getElementById('btn-show-donasi');
    const mobileBtnDonasi = document.getElementById('mobile-btn-donasi');
    const modalDonasi = document.getElementById('modal-donasi');

    if (modalDonasi) {
        if (btnShowDonasi) {
            btnShowDonasi.addEventListener('click', function(e) {
                e.preventDefault();
                modalDonasi.classList.add('open');
            });
        }
        if (mobileBtnDonasi) {
            mobileBtnDonasi.addEventListener('click', function(e) {
                e.preventDefault();
                modalDonasi.classList.add('open');
            });
        }
    }

    // --- 3c. Profile Modal Trigger ---
    const btnShowProfile = document.getElementById('btn-show-profile');
    const mobileBtnProfile = document.getElementById('mobile-btn-profile');
    const modalProfile = document.getElementById('modal-profile');

    if (modalProfile) {
        if (btnShowProfile) {
            btnShowProfile.addEventListener('click', function(e) {
                e.preventDefault();
                modalProfile.classList.add('open');
            });
        }
        if (mobileBtnProfile) {
            mobileBtnProfile.addEventListener('click', function(e) {
                e.preventDefault();
                modalProfile.classList.add('open');
            });
        }
    }

    // --- 4. Close Modal Interactions (Common) ---
    const btnCloseModals = document.querySelectorAll('.btn-close-modal');
    btnCloseModals.forEach(button => {
        button.addEventListener('click', function() {
            const activeModal = document.querySelector('.modal-overlay.open');
            if (activeModal) {
                activeModal.classList.remove('open');
            }
        });
    });

    // Close modal if clicking directly on the overlay backdrop
    const modalOverlays = document.querySelectorAll('.modal-overlay');
    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', function(event) {
            if (event.target === this) {
                this.classList.remove('open');
            }
        });
    });

    // --- 5. Auto Dismiss Alerts after 4 seconds ---
    const dismissibleAlerts = document.querySelectorAll('.alert-dismissible');
    dismissibleAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 4000);
    });

    // --- 6. Inactivity Auto Logout (15 Minutes) ---
    if (!window.location.pathname.includes('/login')) {
        let inactivityTimeout;
        const TIMEOUT_DURATION = 15 * 60 * 1000; // 15 minutes in milliseconds

        function resetInactivityTimer() {
            clearTimeout(inactivityTimeout);
            inactivityTimeout = setTimeout(logoutDueToInactivity, TIMEOUT_DURATION);
        }

        function logoutDueToInactivity() {
            // Show alert box to notify user
            Swal.fire({
                title: 'SESI BERAKHIR!',
                text: 'Anda telah tidak aktif selama 15 menit. Sistem akan logout secara otomatis demi keamanan.',
                icon: 'warning',
                confirmButtonColor: 'var(--danger)',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                window.location.href = (window.BASEURL || '') + '/login/logout';
            });

            // Fallback redirect after 5 seconds if Swal is ignored
            setTimeout(() => {
                window.location.href = (window.BASEURL || '') + '/login/logout';
            }, 5000);
        }

        // List of events to listen for activity
        const activityEvents = ['mousemove', 'mousedown', 'keypress', 'scroll', 'touchstart'];
        activityEvents.forEach(eventName => {
            document.addEventListener(eventName, resetInactivityTimer, true);
        });

        // Initialize timer on load
        resetInactivityTimer();
    }

});
