    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i data-lucide="aperture" class="brand-icon"></i>
            <span>Mobooth</span>
        </div>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i data-lucide="user"></i>
            </div>
            <div class="user-info">
                <h4><?= htmlspecialchars($user['name'] ?? 'Admin'); ?></h4>
                <p><?= htmlspecialchars($user['role'] ?? 'Administrator'); ?></p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= BASEURL; ?>/dashboard" class="nav-item <?= ($active_menu ?? '') === 'dashboard' ? 'active' : ''; ?>">
                <i data-lucide="home" class="nav-icon"></i>
                <span>Home</span>
            </a>
            <a href="<?= BASEURL; ?>/template" class="nav-item <?= ($active_menu ?? '') === 'template' ? 'active' : ''; ?>">
                <i data-lucide="layout" class="nav-icon"></i>
                <span>Kelola Template</span>
            </a>
            <a href="<?= BASEURL; ?>/dashboard/donation" class="nav-item <?= ($active_menu ?? '') === 'donation' ? 'active' : ''; ?>">
                <i data-lucide="heart" class="nav-icon"></i>
                <span>Donasi</span>
            </a>
            <a href="<?= BASEURL; ?>/dashboard/visitors" class="nav-item <?= ($active_menu ?? '') === 'visitors' ? 'active' : ''; ?>">
                <i data-lucide="users" class="nav-icon"></i>
                <span>Data Pengunjung</span>
            </a>
            <a href="#" class="nav-item" id="btn-show-profile">
                <i data-lucide="user" class="nav-icon"></i>
                <span>Profile</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= BASEURL; ?>/login/logout" class="btn btn-danger btn-block">
                <i data-lucide="log-out" class="btn-icon"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>
