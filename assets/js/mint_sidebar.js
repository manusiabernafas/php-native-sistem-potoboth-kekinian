document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const closeSidebar = document.getElementById('closeSidebar');
    
    if (!sidebar) return;

    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    const style = document.createElement('style');
    style.innerHTML = `
        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);
            z-index: 95; display: none; opacity: 0; transition: opacity 0.3s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }
    `;
    document.head.appendChild(style);

    const toggleSidebar = () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    };

    if (menuToggle) menuToggle.addEventListener('click', toggleSidebar);
    if (closeSidebar) closeSidebar.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);

    // Sidebar active item logic
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            if(item.getAttribute('href') === '#') {
                e.preventDefault();
            }
            // We don't remove active classes on real navigation, but just in case
            if (window.innerWidth <= 1024 && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });
    });

    // Logout button handler with dynamic SweetAlert support
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            const executeLogout = () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Keluar dari Sistem?',
                        text: 'Anda harus memasukkan username dan password kembali untuk masuk.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Keluar',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('logoutForm');
                            if (form) form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin keluar dari sistem?')) {
                        const form = document.getElementById('logoutForm');
                        if (form) form.submit();
                    }
                }
            };

            if (typeof Swal === 'undefined') {
                // Dynamically inject SweetAlert2 script & styles
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
                document.head.appendChild(link);

                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                script.onload = executeLogout;
                document.head.appendChild(script);
            } else {
                executeLogout();
            }
        });
    }
});
