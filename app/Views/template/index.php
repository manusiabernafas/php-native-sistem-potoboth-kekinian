<?php
// Specify the page-specific stylesheet name
$css = 'dashboard';
require APPROOT . '/Views/layouts/header.php';
?>

<style>
@media (max-width: 768px) {
    .dashboard-content {
        padding: 16px !important;
    }
    
    /* Horizontal sliding scroll row for template list within a category */
    .templates-slider {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        scroll-snap-type: x mandatory;
        gap: 12px !important;
        margin-bottom: 24px !important;
        padding: 4px 4px 16px 4px !important;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    
    .templates-slider::-webkit-scrollbar {
        height: 6px;
    }
    .templates-slider::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    .templates-slider::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    /* Make template card smaller on mobile and snapped */
    .template-card {
        flex: 0 0 160px !important;
        min-width: 160px !important;
        max-width: 160px !important;
        scroll-snap-align: start;
        border: 3px solid #1f2937 !important;
        box-shadow: 3px 3px 0 0 rgba(31,41,55,0.1) !important;
    }
    
    .template-img {
        height: 120px !important;
        border-bottom: 3px solid #1f2937 !important;
    }
    
    .template-info {
        padding: 8px 10px !important;
    }
    
    .template-meta {
        margin-bottom: 8px !important;
    }
    
    .template-meta h3 {
        font-size: 0.85rem !important;
        margin-bottom: 2px !important;
    }
    
    .type-tag {
        font-size: 0.7rem !important;
    }
    
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        border: 2px solid #1f2937 !important;
        box-shadow: 1px 1px 0 0 #1f2937 !important;
    }
    
    .btn-action i {
        width: 16px !important;
        height: 16px !important;
    }
    
    /* Hide page description text on mobile */
    .template-header-text {
        display: none !important;
    }
    
    /* Sticky bottom button on mobile */
    #btn-tambah-template {
        position: fixed !important;
        bottom: 82px !important;
        left: 16px !important;
        right: 16px !important;
        width: calc(100% - 32px) !important;
        z-index: 998 !important;
        box-shadow: 4px 4px 0 0 #1f2937 !important;
        justify-content: center !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .content-body {
        padding-bottom: 150px !important;
    }
}
</style>

<div class="dashboard-wrapper">
    <?php 
    $active_menu = 'template';
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
                <h3>Kelola Template Portal</h3>
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

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div class="template-header-text">
                    <h2 style="font-size: 1.5rem; margin-bottom: 8px; font-weight: 700;">Templates Manager</h2>
                    <p style="color: #4b5563; font-weight: 600;">Kelola layout photobooth, stiker, dan desain cetakan.</p>
                </div>
                <button class="btn btn-primary" id="btn-tambah-template">
                    <i data-lucide="plus-circle" class="btn-icon"></i> Tambah Template
                </button>
            </div>

            <!-- Templates Filter -->
            <div class="filter-tabs" style="display: flex; gap: 10px; margin-bottom: 30px;">
                <button class="btn btn-primary filter-btn active" data-filter="all">Semua</button>
                <button class="btn btn-secondary filter-btn" data-filter="strip">Strip</button>
                <button class="btn btn-secondary filter-btn" data-filter="4r">4R</button>
                <button class="btn btn-secondary filter-btn" data-filter="a4">A4</button>
                <button class="btn btn-secondary filter-btn" data-filter="pokemon">Pokemon</button>
            </div>

            <!-- Group Templates Logic -->
            <?php
            $categories = [
                'strip' => ['name' => 'Photo Strip', 'items' => []],
                '4r' => ['name' => '4R Standard Photo', 'items' => []],
                'a4' => ['name' => 'A4 Document', 'items' => []],
                'pokemon' => ['name' => 'Kartu Pokemon', 'items' => []],
                'other' => ['name' => 'Lainnya', 'items' => []]
            ];
            
            $hasTemplates = false;
            if (!empty($templates)) {
                foreach ($templates as $tpl) {
                    $ltype = strtolower($tpl['layout_type']);
                    $cat = 'other';
                    if (strpos($ltype, 'strip') !== false) { $cat = 'strip'; }
                    elseif (strpos($ltype, '4r') !== false) { $cat = '4r'; }
                    elseif (strpos($ltype, 'a4') !== false) { $cat = 'a4'; }
                    elseif (strpos($ltype, 'pokemon') !== false) { $cat = 'pokemon'; }
                    
                    $categories[$cat]['items'][] = $tpl;
                    $hasTemplates = true;
                }
            }
            ?>

            <!-- Grouped Templates Sections -->
            <div class="templates-sections" id="templatesSections">
                <?php if ($hasTemplates): ?>
                    <?php foreach ($categories as $key => $cat): ?>
                        <?php if (count($cat['items']) > 0): ?>
                            <div class="category-section" data-category="<?= htmlspecialchars($key) ?>">
                                <div class="category-header">
                                    <h2 class="category-title" style="display:flex; align-items:center; gap:10px; margin-bottom:20px; font-size:1.5rem; font-weight:700;">
                                        <i data-lucide="folder" style="color: #1f2937;"></i>
                                        <?= htmlspecialchars($cat['name']) ?>
                                        <span class="status-badge status-active" style="margin-left:10px;"><?= count($cat['items']) ?> Template</span>
                                    </h2>
                                </div>
                                <div class="templates-slider" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap:16px; margin-bottom:40px;">
                                    <?php foreach ($cat['items'] as $tpl): ?>
                                        <?php 
                                            $baseW = intval($tpl['width'] ?? 200);
                                            $baseH = intval($tpl['height'] ?? 600);
                                            $scale = min(140 / ($baseW ?: 1), 105 / ($baseH ?: 1));
                                            $scaledW = $baseW * $scale;
                                            $scaledH = $baseH * $scale;
                                        ?>
                                        <div class="template-card template-item" data-category="<?= htmlspecialchars($key) ?>" style="border: 4px solid #1f2937; border-radius: var(--radius-lg); background-color: #ffffff; box-shadow: 4px 4px 0 0 rgba(31,41,55,0.1); overflow: hidden;">
                                            <div class="template-img" style="position: relative; display: flex; justify-content: center; align-items: center; overflow: hidden; background-color: #f1f5f9; height:170px; border-bottom: 4px solid #1f2937;">
                                                <!-- Wrapper untuk centering frame setelah di-scale dengan top left origin -->
                                                <div style="
                                                    width: <?= round($scaledW) ?>px;
                                                    height: <?= round($scaledH) ?>px;
                                                    position: relative;
                                                    flex-shrink: 0;
                                                ">
                                                    <div style="
                                                        width: <?= $baseW ?>px; 
                                                        height: <?= $baseH ?>px; 
                                                        transform: scale(<?= $scale ?>); 
                                                        transform-origin: top left; 
                                                        position: absolute;
                                                        top: 0;
                                                        left: 0;
                                                        background-color: #ffffff;
                                                        box-shadow: 4px 4px 0 0 #1f2937;
                                                        border: 2px solid #1f2937;
                                                    ">
                                                        <?php 
                                                        if(!empty($tpl['frame_coordinates'])) {
                                                            $frames = json_decode($tpl['frame_coordinates'], true);
                                                            if ($frames && is_array($frames)) {
                                                                $colors = ['#f43f5e', '#a855f7', '#3b82f6', '#0ea5e9', '#14b8a6', '#10b981', '#84cc16', '#eab308', '#f59e0b', '#f97316'];
                                                                foreach($frames as $idx => $frame) {
                                                                    $color = $colors[$idx % count($colors)];
                                                                    $br = '0';
                                                                    if(isset($frame['shape'])) {
                                                                        if($frame['shape'] == 'rounded-sm') $br = '8px';
                                                                        if($frame['shape'] == 'rounded-lg') $br = '24px';
                                                                        if($frame['shape'] == 'circle') $br = '50%';
                                                                    }
                                                                    // layer: 'back' = z-index 10 (di bawah frame image)
                                                                    // layer: 'front' = z-index 100 (di atas frame image)
                                                                    $layer = isset($frame['layer']) ? $frame['layer'] : 'back';
                                                                    $zIndex = ($layer === 'front') ? '100' : '10';
                                                                    
                                                                    echo "<div style='
                                                                        position: absolute;
                                                                        left: {$frame['x']}px;
                                                                        top: {$frame['y']}px;
                                                                        width: {$frame['width']}px;
                                                                        height: {$frame['height']}px;
                                                                        background-color: {$color};
                                                                        border-radius: {$br};
                                                                        opacity: 1;
                                                                        z-index: {$zIndex};
                                                                        border: 2px solid #1f2937;
                                                                        box-shadow: inset 0 0 0 4px rgba(255,255,255,0.3);
                                                                        display: flex;
                                                                        align-items: center;
                                                                        justify-content: center;
                                                                        color: white;
                                                                        font-size: 20px;
                                                                        font-weight: 700;
                                                                        text-shadow: 1px 1px 0 #1f2937;
                                                                    '>
                                                                        <span>" . ($idx + 1) . "</span>
                                                                    </div>";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <?php if(!empty($tpl['image_url'])): 
                                                            $imgUrl = $tpl['image_url'];
                                                            if (strpos($imgUrl, 'http') !== 0) {
                                                                $fileName = basename($imgUrl);
                                                                $imgUrl = BASEURL . '/assets/uploads/' . $fileName;
                                                            }
                                                        ?>
                                                        <!-- Frame image di z-index 50: di atas slot 'back' (10), di bawah slot 'front' (100) -->
                                                        <img src="<?= htmlspecialchars($imgUrl) ?>" 
                                                             style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; pointer-events: none; object-fit: fill;" 
                                                             alt="">
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="template-info" style="padding:12px 14px;">
                                                <div class="template-meta" style="margin-bottom:12px;">
                                                    <h3 style="margin-bottom:2px; font-weight: 700; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($tpl['name']) ?>"><?= htmlspecialchars($tpl['name']) ?></h3>
                                                    <span class="type-tag" style="font-size:0.75rem; color:var(--text-secondary); font-weight: 600;"><?= htmlspecialchars($tpl['layout_type']) ?> - <?= htmlspecialchars($tpl['slots']) ?> Slot</span>
                                                </div>
                                                <div class="template-actions" style="display:flex; gap:10px;">
                                                    <button type="button" class="btn-action btn-edit" title="Edit" onclick="window.location.href='<?= BASEURL ?>/template/edit/<?= $tpl['id'] ?>'">
                                                        <i data-lucide="edit-3"></i>
                                                    </button>
                                                    
                                                    <form action="<?= BASEURL ?>/template/delete/<?= intval($tpl['id']) ?>" method="POST" style="display:inline; margin-left:auto;">
                                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">
                                                        <button type="button" class="btn-action btn-delete delete-btn" title="Delete">
                                                            <i data-lucide="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state" style="text-align: center; padding: 60px 20px; border: 3px dashed #1f2937; border-radius: var(--radius-lg); background-color: #ffffff;">
                        <i data-lucide="layout" style="width: 48px; height: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
                        <h3 style="margin-bottom: 8px;">Belum Ada Template</h3>
                        <p style="margin-bottom: 24px; color: #64748b; font-weight: 600;">Klik tombol di atas untuk membuat template pertama Anda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Bottom Navigation for Mobile (Gojek Style) -->
<nav class="mobile-bottom-nav">
    <a href="<?= BASEURL; ?>/dashboard" class="mobile-nav-item">
        <i data-lucide="home"></i>
        <span>Home</span>
    </a>
    <a href="<?= BASEURL; ?>/template" class="mobile-nav-item active">
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


<!-- Modal: Tambah Template Setup Wizard -->
<div class="modal-overlay" id="modal-tambah-template">
    <div class="modal-card" style="max-width: 480px;">
        <div class="modal-header">
            <h3>Buat Template Baru</h3>
            <button class="btn-close-modal" id="btn-close-tambah"><i data-lucide="x"></i></button>
        </div>
        <form action="<?= BASEURL; ?>/template/create" method="POST" class="modal-form" style="padding: 20px;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label for="new_name" style="font-weight: 700; color: #1f2937; margin-bottom: 6px; display: block;">Nama Template</label>
                <input type="text" id="new_name" name="name" required placeholder="Contoh: Polaroid Classic 3 Slot" style="width: 100%; padding: 10px; border: 3px solid #1f2937; border-radius: var(--radius-md); box-shadow: inset 3px 3px 0 0 #f1f5f9;">
            </div>
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label for="new_layout_type" style="font-weight: 700; color: #1f2937; margin-bottom: 6px; display: block;">Kategori / Ukuran Kertas</label>
                <select id="new_layout_type" name="layout_type" class="form-control" required style="width: 100%; padding: 10px 12px; border: 3px solid #1f2937; border-radius: var(--radius-md); box-shadow: inset 3px 3px 0 0 #f1f5f9; appearance: none; background-color: #fff;">
                    <option value="Strip (Vertical)">Strip (Photo Strip)</option>
                    <option value="4R Grid">4R (Standard Photo)</option>
                    <option value="A4 Document">A4 Document</option>
                    <option value="Kartu Pokemon">Kartu Pokemon</option>
                </select>
            </div>
            
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
                <button type="button" class="btn btn-secondary btn-close-modal" id="btn-cancel-tambah">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <span>Lanjutkan ke Canvas</span>
                    <i data-lucide="arrow-right" class="btn-icon"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Filter logic
        const filterBtns = document.querySelectorAll('.filter-btn');
        const sections = document.querySelectorAll('.category-section');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-secondary');
                });
                btn.classList.add('active', 'btn-primary');
                btn.classList.remove('btn-secondary');
                
                const filterValue = btn.getAttribute('data-filter');
                
                sections.forEach(sec => {
                    if (filterValue === 'all') {
                        sec.style.display = 'block';
                    } else {
                        if (sec.getAttribute('data-category') === filterValue) {
                            sec.style.display = 'block';
                        } else {
                            sec.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Delete Confirm logic
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: 'HAPUS TEMPLATE?',
                    text: 'Template yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger)',
                    cancelButtonColor: '#cbd5e1',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });

        // Modal logic
        const btnTambah = document.getElementById('btn-tambah-template');
        const modalTambah = document.getElementById('modal-tambah-template');
        const btnCloseTambah = document.getElementById('btn-close-tambah');
        const btnCancelTambah = document.getElementById('btn-cancel-tambah');

        if (btnTambah && modalTambah) {
            btnTambah.addEventListener('click', () => {
                modalTambah.classList.add('open');
            });
        }

        const closeTambahModal = () => {
            if (modalTambah) modalTambah.classList.remove('open');
        };

        if (btnCloseTambah) btnCloseTambah.addEventListener('click', closeTambahModal);
        if (btnCancelTambah) btnCancelTambah.addEventListener('click', closeTambahModal);

        if (modalTambah) {
            modalTambah.addEventListener('click', (e) => {
                if (e.target === modalTambah) {
                    closeTambahModal();
                }
            });
        }
    });
</script>

<?php require APPROOT . '/Views/layouts/footer.php'; ?>
