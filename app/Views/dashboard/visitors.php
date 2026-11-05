<?php
// Specify the page-specific stylesheet name
$css = 'dashboard';
require APPROOT . '/Views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php 
    $active_menu = 'visitors';
    require APPROOT . '/Views/layouts/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button id="sidebar-toggle" class="sidebar-toggle-btn">
                    <i data-lucide="menu"></i>
                </button>
                <h3>Kelola Pengunjung</h3>
            </div>
            <div class="topbar-right">
                <div class="date-display">
                    <i data-lucide="calendar" class="icon-sm"></i>
                    <span><?= date('d M Y'); ?></span>
                </div>
            </div>
        </header>

        <!-- Inner Content -->
        <div class="content-body">
            
            <section class="table-card">
                <div class="table-header">
                    <div>
                        <h3>Daftar Player Terdaftar</h3>
                        <p>Daftar pengguna yang telah menautkan akun Google SSO</p>
                    </div>
                    <button class="btn btn-primary" onclick="location.reload();">
                        <i data-lucide="refresh-cw" class="btn-icon"></i>
                        <span>Refresh Data</span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Akun</th>
                                <th>Metode Login</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($visitors)): ?>
                                <tr>
                                    <td colspan="4" class="text-center empty-row">
                                        <i data-lucide="inbox" class="empty-icon"></i>
                                        <p>Belum ada data pendaftar yang tercatat.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($visitors as $v): ?>
                                <tr>
                                    <td><strong>#<?= $no++; ?></strong></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600;">
                                            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" style="width: 18px; height: 18px;" alt="Google">
                                            <?= htmlspecialchars($v['name']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active" style="background-color: #dbeafe; color: #1e40af; border-color: #93c5fd;">Google SSO</span>
                                    </td>
                                    <td style="color: #64748b; font-size: 0.9rem;">
                                        <?= date('d M Y, H:i', strtotime($v['created_at'])); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </main>
</div>

<!-- Bottom Navigation for Mobile -->
<nav class="mobile-bottom-nav">
    <a href="<?= BASEURL; ?>/dashboard" class="mobile-nav-item">
        <i data-lucide="home"></i>
        <span>Home</span>
    </a>
    <a href="<?= BASEURL; ?>/template" class="mobile-nav-item">
        <i data-lucide="layout"></i>
        <span>Template</span>
    </a>
    <a href="<?= BASEURL; ?>/dashboard/donation" class="mobile-nav-item">
        <i data-lucide="heart"></i>
        <span>Donasi</span>
    </a>
    <a href="<?= BASEURL; ?>/dashboard/visitors" class="mobile-nav-item active">
        <i data-lucide="users"></i>
        <span>Pengunjung</span>
    </a>
    <a href="#" class="mobile-nav-item" id="mobile-btn-profile">
        <i data-lucide="user"></i>
        <span>Profile</span>
    </a>
</nav>

<!-- Modal: Profile -->
<div class="modal-overlay" id="modal-profile">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Profil Player</h3>
            <button class="btn-close-modal"><i data-lucide="x"></i></button>
        </div>
        <div class="modal-form" style="padding: 24px 16px;">
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 20px;">
                <div class="user-avatar" style="width: 64px; height: 64px; margin-bottom: 12px; background-color: #fef08a; border: 3px solid #1f2937; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 0 0 #1f2937;">
                    <i data-lucide="user" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 4px; color: #1f2937;"><?= htmlspecialchars($user['name']); ?></h4>
                <p style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-secondary); font-weight: 700; letter-spacing: 0.05em;"><?= htmlspecialchars($user['role']); ?></p>
            </div>
            
            <div style="border: 3px solid #1f2937; padding: 14px; background-color: #fafafa; margin-bottom: 20px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.85rem; font-weight: 600;">
                    <span style="color: #6b7280;">Username:</span>
                    <strong style="color: #1f2937;"><?= htmlspecialchars($user['username']); ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 600;">
                    <span style="color: #6b7280;">Status Akun:</span>
                    <strong style="color: #16a34a;">ACTIVE [ONLINE]</strong>
                </div>
            </div>

            <!-- Edit Password Form -->
            <form action="<?= BASEURL; ?>/dashboard/updateProfile" method="POST" style="border: 3px solid #1f2937; padding: 16px; background-color: #f8fafc; box-shadow: 4px 4px 0 0 #1f2937; margin-bottom: 20px; text-align: left;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">
                <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'dashboard'); ?>">
                
                <h4 style="font-family: var(--font-primary); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; margin-bottom: 12px; border-bottom: 2px dashed #1f2937; padding-bottom: 6px;">Ubah Password</h4>
                
                <div class="form-group" style="margin-bottom: 12px; text-align: left;">
                    <label for="new_password" style="font-size: 0.75rem; font-weight: 700; display: block; margin-bottom: 4px;">PASSWORD BARU</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="new_password" name="new_password" required style="width: 100%; border: 2.5px solid #1f2937; padding: 8px 36px 8px 10px; font-family: var(--font-primary); font-size: 0.9rem; box-sizing: border-box;">
                        <button type="button" class="btn-toggle-pwd" data-target="new_password" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; color: #4b5563; display: flex; align-items: center; padding: 0;">
                            <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px; text-align: left;">
                    <label for="confirm_password" style="font-size: 0.75rem; font-weight: 700; display: block; margin-bottom: 4px;">KONFIRMASI PASSWORD</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; border: 2.5px solid #1f2937; padding: 8px 36px 8px 10px; font-family: var(--font-primary); font-size: 0.9rem; box-sizing: border-box;">
                        <button type="button" class="btn-toggle-pwd" data-target="confirm_password" style="position: absolute; right: 8px; background: none; border: none; cursor: pointer; color: #4b5563; display: flex; align-items: center; padding: 0;">
                            <i data-lucide="eye" style="width: 18px; height: 18px;"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 8px; font-size: 0.85rem; font-weight: 700; box-shadow: 2px 2px 0 0 #1f2937;">
                    <i data-lucide="save" style="width: 16px; height: 16px; margin-right: 6px;"></i> Simpan Password Baru
                </button>
            </form>
            
            <div class="modal-footer" style="justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-secondary btn-close-modal">Tutup</button>
                <a href="<?= BASEURL; ?>/login/logout" class="btn btn-danger" style="text-decoration: none; display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="log-out" style="width: 16px; height: 16px;"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/Views/layouts/footer.php'; ?>
