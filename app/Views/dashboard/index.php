<?php
// Specify the page-specific stylesheet name
$css = 'dashboard';
require APPROOT . '/Views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php 
    $active_menu = 'dashboard';
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
                <h3>Kelola Booth Portal</h3>
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
            
            <!-- Toast / Alerts -->
            <?php if (!empty($success)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            title: 'BERHASIL!',
                            text: <?= json_encode($success); ?>,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                            title: 'GAGAL!',
                            text: <?= json_encode($error); ?>,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>
            <?php endif; ?>

            <!-- Stats Overview Cards -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon icon-blue">
                        <i data-lucide="server"></i>
                    </div>
                    <div class="stat-details">
                        <p class="stat-label">Total Booth</p>
                        <h3 class="stat-value"><?= count($items); ?></h3>
                    </div>
                </div>

                <div class="stat-card">
                    <?php 
                        $activeCount = 0;
                        foreach($items as $item) {
                            if (strtolower($item['status'] ?? '') === 'active') $activeCount++;
                        }
                    ?>
                    <div class="stat-icon icon-green">
                        <i data-lucide="play-circle"></i>
                    </div>
                    <div class="stat-details">
                        <p class="stat-label">Booth Aktif</p>
                        <h3 class="stat-value"><?= $activeCount; ?></h3>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon icon-orange">
                        <i data-lucide="wrench"></i>
                    </div>
                    <div class="stat-details">
                        <p class="stat-label">Dalam Perbaikan</p>
                        <h3 class="stat-value"><?= count($items) - $activeCount; ?></h3>
                    </div>
                </div>
            </section>

            <!-- Data Table Section -->
            <section class="table-card">
                <div class="table-header">
                    <div>
                        <h3>Daftar Booth Terdaftar</h3>
                        <p>Kelola data booth dan statusnya dari file JSON</p>
                    </div>
                    <button class="btn btn-primary" id="btn-add-booth">
                        <i data-lucide="plus-circle" class="btn-icon"></i>
                        <span>Tambah Booth</span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama Booth</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th style="width: 150px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="6" class="text-center empty-row">
                                        <i data-lucide="folder-open" class="empty-icon"></i>
                                        <p>Belum ada data booth. Silakan tambahkan baru!</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($items as $item): ?>
                                    <tr>
                                        <td><strong>#<?= $item['id']; ?></strong></td>
                                        <td>
                                            <div class="item-name"><?= htmlspecialchars($item['name']); ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($item['location']); ?></td>
                                        <td>
                                            <?php 
                                                $statusClass = 'status-' . strtolower($item['status'] ?? 'active');
                                            ?>
                                            <span class="status-badge <?= $statusClass; ?>">
                                                <?= htmlspecialchars($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="notes-text" title="<?= htmlspecialchars($item['notes'] ?? ''); ?>">
                                                <?= htmlspecialchars(substr($item['notes'] ?? '-', 0, 50)) . (strlen($item['notes'] ?? '') > 50 ? '...' : ''); ?>
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn-action btn-edit" 
                                                    data-id="<?= $item['id']; ?>"
                                                    data-name="<?= htmlspecialchars($item['name']); ?>"
                                                    data-location="<?= htmlspecialchars($item['location']); ?>"
                                                    data-status="<?= htmlspecialchars($item['status']); ?>"
                                                    data-notes="<?= htmlspecialchars($item['notes'] ?? ''); ?>">
                                                <i data-lucide="edit-3"></i>
                                            </button>
                                            <a href="<?= BASEURL; ?>/dashboard/delete/<?= $item['id']; ?>" 
                                               class="btn-action btn-delete delete-booth-btn" 
                                               data-name="<?= htmlspecialchars($item['name']); ?>">
                                                <i data-lucide="trash-2"></i>
                                            </a>
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

<!-- Modal: Tambah Booth -->
<div class="modal-overlay" id="modal-add">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Tambah Booth Baru</h3>
            <button class="btn-close-modal"><i data-lucide="x"></i></button>
        </div>
        <form action="<?= BASEURL; ?>/dashboard/create" method="POST" class="modal-form">
            <div class="form-group">
                <label for="add-name">Nama Booth</label>
                <input type="text" id="add-name" name="name" placeholder="Contoh: Booth VIP Lobby" required>
            </div>
            <div class="form-group">
                <label for="add-location">Lokasi</label>
                <input type="text" id="add-location" name="location" placeholder="Contoh: Gedung A Lt. 2" required>
            </div>
            <div class="form-group">
                <label for="add-status">Status</label>
                <select id="add-status" name="status">
                    <option value="Active">Active</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>
            <div class="form-group">
                <label for="add-notes">Keterangan / Detail Alat</label>
                <textarea id="add-notes" name="notes" rows="3" placeholder="Contoh: Menggunakan kamera DSLR dan Printer DNP RX1..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close-modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Booth -->
<div class="modal-overlay" id="modal-edit">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Edit Data Booth</h3>
            <button class="btn-close-modal"><i data-lucide="x"></i></button>
        </div>
        <form action="<?= BASEURL; ?>/dashboard/update" method="POST" class="modal-form">
            <input type="hidden" id="edit-id" name="id">
            
            <div class="form-group">
                <label for="edit-name">Nama Booth</label>
                <input type="text" id="edit-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit-location">Lokasi</label>
                <input type="text" id="edit-location" name="location" required>
            </div>
            <div class="form-group">
                <label for="edit-status">Status</label>
                <select id="edit-status" name="status">
                    <option value="Active">Active</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit-notes">Keterangan / Detail Alat</label>
                <textarea id="edit-notes" name="notes" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close-modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Bottom Navigation for Mobile (Gojek Style) -->
<nav class="mobile-bottom-nav">
    <a href="<?= BASEURL; ?>/dashboard" class="mobile-nav-item active">
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
    <a href="<?= BASEURL; ?>/dashboard/visitors" class="mobile-nav-item">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-toggle-pwd').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i data-lucide="eye-off" style="width: 18px; height: 18px;"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i data-lucide="eye" style="width: 18px; height: 18px;"></i>';
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
});
</script>

<?php
require APPROOT . '/Views/layouts/footer.php';
?>
