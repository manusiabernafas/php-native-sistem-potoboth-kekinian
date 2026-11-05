<?php
$css = 'dashboard';
require APPROOT . '/Views/layouts/header.php';

$isEdit = $isEdit ?? false;
$tpl = $template ?? null;
?>

<div class="dashboard-wrapper">
    <?php 
    $active_menu = 'template';
    require APPROOT . '/Views/layouts/sidebar.php'; 
    ?>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Topbar Header -->
        <header class="topbar">
            <div class="topbar-left">
                <a href="<?= BASEURL; ?>/template" class="btn btn-secondary" style="margin-right: 12px; text-decoration: none; padding: 6px 12px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 4px;">
                    <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
                    <span>Kembali</span>
                </a>
                <h3><?= $isEdit ? 'Edit Template' : 'Visual Editor Template'; ?></h3>
            </div>
            <div class="topbar-right">
                <div class="date-display">
                    <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                    <span><?= date('d M Y'); ?></span>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="content-body">
            <div class="editor-container">
                
                <!-- 1. Live Interactive Canvas Area (Left) -->
                <div class="canvas-area">
                    <div class="canvas-wrapper" id="canvasWrapper">
                        <div class="canvas-paper size-strip" id="canvasPaper">
                            <img id="bgLayer" src="" style="display:none;" alt="Background">
                            <div id="guideV" class="guide-line"></div>
                            <div id="guideH" class="guide-line"></div>
                            <!-- Slots are generated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- 2. Settings Control Panel (Right) -->
                <div class="editor-form-panel collapsed" id="editorFormPanel">
                    <div class="bottom-sheet-handle-container" id="bottomSheetHandle">
                        <div class="bottom-sheet-handle"></div>
                    </div>
                    <h3 id="panelHeader" style="margin-top: 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                        <span>Pengaturan Layout</span>
                        <span class="mobile-sheet-toggle-label" style="font-size: 0.65rem; font-family: 'Press Start 2P', monospace; color: var(--primary); display: none;">TAB TO OPEN</span>
                    </h3>
                    <form action="<?= BASEURL; ?>/template/<?= $isEdit ? 'update' : 'create'; ?>" method="POST" enctype="multipart/form-data" id="editorForm">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= intval($tpl['id']); ?>">
                        <?php endif; ?>
                        
                        <!-- JSON frame coordinates and slots count hidden fields -->
                        <input type="hidden" name="frame_data" id="frameData">
                        <input type="hidden" name="slots" id="slotsCountHidden" value="0">
                        <!-- Dimensions mapping for compatibility -->
                        <input type="hidden" name="width" id="width" value="200">
                        <input type="hidden" name="height" id="height" value="600">
                        <!-- CSRF Protection -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">

                        <div class="form-group">
                            <label>Background Template (PNG transparan)</label>
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <i data-lucide="image" class="file-upload-icon" style="width: 28px; height: 28px; color: #4b5563;"></i>
                                <span class="file-upload-text" id="uploadText">PILIH FILE GAMBAR</span>
                                <span class="file-upload-subtext">Klik atau seret gambar PNG transparan ke sini</span>
                                <input type="file" id="bgImage" name="bg_image" accept="image/*">
                            </div>
                            <?php if ($isEdit && !empty($tpl['image_url'])): ?>
                                <small style="color:var(--text-secondary); display:block; margin-top:6px;">Biarkan kosong jika tidak ingin merubah gambar latar.</small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="layout_type">Kategori / Ukuran Kertas</label>
                            <select id="layout_type" name="layout_type" class="form-control" required style="padding: 10px 12px;">
                                <option value="Strip (Vertical)" <?= ($isEdit && $tpl['layout_type'] === 'Strip (Vertical)') ? 'selected' : ''; ?>>Strip (Photo Strip)</option>
                                <option value="4R Grid" <?= ($isEdit && $tpl['layout_type'] === '4R Grid') ? 'selected' : ''; ?>>4R (Standard Photo)</option>
                                <option value="A4 Document" <?= ($isEdit && $tpl['layout_type'] === 'A4 Document') ? 'selected' : ''; ?>>A4 Document</option>
                                <option value="Kartu Pokemon" <?= ($isEdit && $tpl['layout_type'] === 'Kartu Pokemon') ? 'selected' : ''; ?>>Kartu Pokemon</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Template</label>
                            <input type="text" id="name" name="name" required placeholder="Contoh: Polaroid Classic 3 Slot" value="<?= $isEdit ? htmlspecialchars($tpl['name']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required style="padding: 10px 12px;">
                                <option value="Active" <?= ($isEdit && $tpl['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                <option value="Draft" <?= ($isEdit && $tpl['status'] === 'Draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>


                        <div class="form-group" style="margin-top: 8px; margin-bottom: 8px;">
                            <button type="button" class="btn btn-secondary btn-block" id="addSlotBtn" style="border: 3px dashed #1f2937; background: transparent; color: #1f2937; font-weight: 700; font-size: 0.8rem; justify-content: center; width: 100%;">
                                <i data-lucide="plus-square" style="width: 16px; height: 16px; margin-right: 4px;"></i>
                                <span>Tambah Slot Foto</span>
                            </button>
                        </div>

                        <!-- Selected Slot Properties -->
                        <div id="slotProps">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h4 id="slotPropTitle" style="font-size: 0.8rem; margin: 0; color: #f43f5e; display: flex; align-items: center; gap: 6px; font-family: 'Press Start 2P', monospace; font-weight: 700;">
                                    Slot 1
                                </h4>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <div class="dropdown-container">
                                        <button type="button" class="btn-icon-retro" id="slotOptionsBtn" style="width: 28px; height: 28px;" title="Opsi Layer">
                                            <i data-lucide="more-vertical" style="width: 14px; height: 14px;"></i>
                                        </button>
                                        <div id="slotOptionsMenu" class="glass-dropdown" style="display: none;">
                                            <button type="button" class="dropdown-item" id="layerFrontBtn">
                                                <i data-lucide="chevrons-up" style="width: 14px; height: 14px;"></i> Majukan
                                            </button>
                                            <button type="button" class="dropdown-item" id="layerBackBtn">
                                                <i data-lucide="chevrons-down" style="width: 14px; height: 14px;"></i> Mundurkan
                                            </button>
                                            <button type="button" class="dropdown-item" id="duplicateSlotBtn">
                                                <i data-lucide="copy" style="width: 14px; height: 14px;"></i> Duplikat
                                            </button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-icon-retro text-danger" id="deleteSlotBtn" style="width: 28px; height: 28px;" title="Hapus Slot">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 12px;">
                            <button type="submit" class="btn btn-primary btn-block" style="justify-content: center; width: 100%;" onclick="updateFrameData()">
                                <i data-lucide="save" class="btn-icon"></i>
                                <span><?= $isEdit ? 'Simpan Perubahan' : 'Simpan ke Database'; ?></span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- Mobile Bottom Navigation -->
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

<!-- Drag, Drop, Snap, Layering & Save JS Logic (Mintsnap Engine Clone) -->
<script>
const isEdit = <?= $isEdit ? 'true' : 'false'; ?>;
const initialTemplateData = <?= $isEdit ? json_encode($tpl) : 'null'; ?>;
const vibrantColors = ['#ef4444', '#eab308', '#22c55e', '#3b82f6', '#a855f7', '#f97316'];
let slotsCounter = 0;
let activeSlot = null;
let currentScale = 1;

function fitCanvas() {
    const area = document.querySelector('.canvas-area');
    const wrapper = document.getElementById('canvasWrapper');
    const paper = document.getElementById('canvasPaper');
    if (!area || !wrapper || !paper) return;
    
    wrapper.style.width = 'auto';
    wrapper.style.height = 'auto';
    paper.style.transform = 'none';
    paper.style.position = 'static';
    
    const areaWidth = area.clientWidth;
    const areaHeight = area.clientHeight;
    
    const areaStyles = window.getComputedStyle(area);
    const padX = parseFloat(areaStyles.paddingLeft) + parseFloat(areaStyles.paddingRight);
    const padY = parseFloat(areaStyles.paddingTop) + parseFloat(areaStyles.paddingBottom);
    
    const availableW = areaWidth - padX;
    const availableH = areaHeight - padY;
    
    const paperW = paper.offsetWidth;
    const paperH = paper.offsetHeight;
    
    const scale = Math.min(availableW / paperW, availableH / paperH, 1);
    currentScale = scale;
    
    wrapper.style.width = (paperW * scale) + 'px';
    wrapper.style.height = (paperH * scale) + 'px';
    wrapper.style.position = 'relative';
    wrapper.style.overflow = 'visible';
    
    paper.style.position = 'absolute';
    paper.style.left = '50%';
    paper.style.top = '50%';
    paper.style.transform = `translate(-50%, -50%) scale(${scale})`;
    paper.style.transformOrigin = 'center center';
}

window.addEventListener('resize', fitCanvas);

document.addEventListener('DOMContentLoaded', function() {
    const canvasPaper = document.getElementById('canvasPaper');
    const layoutType = document.getElementById('layout_type');
    const bgImageInput = document.getElementById('bgImage');
    const bgLayer = document.getElementById('bgLayer');

    const widthInput = document.getElementById('width');
    const heightInput = document.getElementById('height');

    const classMap = {
        'Strip (Vertical)': 'size-strip',
        '4R Grid': 'size-4r',
        'A4 Document': 'size-a4',
        'Kartu Pokemon': 'size-pokemon'
    };

    const dimensionMap = {
        'Strip (Vertical)': { w: 200, h: 600 },
        '4R Grid': { w: 400, h: 600 },
        'A4 Document': { w: 420, h: 594 },
        'Kartu Pokemon': { w: 250, h: 350 }
    };

    layoutType.addEventListener('change', function() {
        const type = this.value;
        canvasPaper.className = 'canvas-paper ' + (classMap[type] || 'size-strip');
        
        const dims = dimensionMap[type] || { w: 200, h: 600 };
        widthInput.value = dims.w;
        heightInput.value = dims.h;

        setTimeout(fitCanvas, 50);
        updateFrameData();
    });

    bgImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const uploadText = document.getElementById('uploadText');
        if (file) {
            uploadText.innerText = file.name.toUpperCase();
            const reader = new FileReader();
            reader.onload = function(event) {
                bgLayer.src = event.target.result;
                bgLayer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            uploadText.innerText = "PILIH FILE GAMBAR";
            bgLayer.style.display = 'none';
            bgLayer.src = '';
        }
    });

    // Drag, Drop, Snapping & Resizing Logic
    let isDragging = false;
    let isResizing = false;
    let currentSlotDrag = null;
    let currentSlotResize = null;
    let startX, startY, initialX, initialY;
    let startW, startH;

    const guideV = document.getElementById('guideV');
    const guideH = document.getElementById('guideH');
    const SNAP_THRESHOLD = 0; /* Snap disabled - free drag */

    function makeDraggable(el) {
        el.addEventListener('mousedown', (e) => {
            if (e.target.closest('.resize-handle')) return;
            isDragging = true;
            currentSlotDrag = el;
            startX = e.clientX;
            startY = e.clientY;
            initialX = el.offsetLeft;
            initialY = el.offsetTop;
            selectSlot(el); 
        });

        el.addEventListener('touchstart', (e) => {
            if (e.target.closest('.resize-handle')) return;
            const touch = e.targetTouches[0];
            isDragging = true;
            currentSlotDrag = el;
            startX = touch.clientX;
            startY = touch.clientY;
            initialX = el.offsetLeft;
            initialY = el.offsetTop;
            selectSlot(el);
            if (e.cancelable) e.preventDefault();
        }, { passive: false });
    }

    function makeResizable(el) {
        const handle = el.querySelector('.resize-handle');
        if (!handle) return;

        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            isResizing = true;
            currentSlotResize = el;
            startX = e.clientX;
            startY = e.clientY;
            startW = el.offsetWidth;
            startH = el.offsetHeight;
            selectSlot(el);
        });

        handle.addEventListener('touchstart', (e) => {
            e.stopPropagation();
            const touch = e.targetTouches[0];
            isResizing = true;
            currentSlotResize = el;
            startX = touch.clientX;
            startY = touch.clientY;
            startW = el.offsetWidth;
            startH = el.offsetHeight;
            selectSlot(el);
            if (e.cancelable) e.preventDefault();
        }, { passive: false });
    }

    function addDoubleTapListener(slot) {
        let lastTap = 0;
        
        // Mobile Double Tap
        slot.addEventListener('touchstart', (e) => {
            if (e.target.closest('.resize-handle')) return;
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            if (tapLength < 300 && tapLength > 0) {
                cycleSlotShape(slot);
                updateFrameData();
                e.preventDefault();
            }
            lastTap = currentTime;
        });

        // Desktop Double Click
        slot.addEventListener('dblclick', (e) => {
            if (e.target.closest('.resize-handle')) return;
            cycleSlotShape(slot);
            updateFrameData();
        });
    }

    function cycleSlotShape(slot) {
        if (slot.classList.contains('shape-square')) {
            slot.classList.replace('shape-square', 'shape-rounded-sm');
        } else if (slot.classList.contains('shape-rounded-sm')) {
            slot.classList.replace('shape-rounded-sm', 'shape-rounded-lg');
        } else if (slot.classList.contains('shape-rounded-lg')) {
            slot.classList.replace('shape-rounded-lg', 'shape-circle');
        } else {
            slot.classList.replace('shape-circle', 'shape-square');
        }
    }

    document.addEventListener('mousemove', (e) => {
        if (isResizing && currentSlotResize) {
            const dx = (e.clientX - startX) / currentScale;
            const dy = (e.clientY - startY) / currentScale;
            
            let newW = startW + dx;
            let newH = startH + dy;
            
            if (newW < 40) newW = 40;
            if (newH < 40) newH = 40;
            
            if (currentSlotResize.offsetLeft + newW > canvasPaper.offsetWidth) {
                newW = canvasPaper.offsetWidth - currentSlotResize.offsetLeft;
            }
            if (currentSlotResize.offsetTop + newH > canvasPaper.offsetHeight) {
                newH = canvasPaper.offsetHeight - currentSlotResize.offsetTop;
            }
            
            currentSlotResize.style.width = newW + 'px';
            currentSlotResize.style.height = newH + 'px';
            return;
        }

        if (!isDragging || !currentSlotDrag) return;
        
        const dx = (e.clientX - startX) / currentScale;
        const dy = (e.clientY - startY) / currentScale;
        
        let newX = initialX + dx;
        let newY = initialY + dy;
        
        if (newX < 0) newX = 0;
        if (newY < 0) newY = 0;
        if (newX + currentSlotDrag.offsetWidth > canvasPaper.offsetWidth) newX = canvasPaper.offsetWidth - currentSlotDrag.offsetWidth;
        if (newY + currentSlotDrag.offsetHeight > canvasPaper.offsetHeight) newY = canvasPaper.offsetHeight - currentSlotDrag.offsetHeight;
        
        // Snapping Guide Center Checks
        const canvasCenterX = canvasPaper.offsetWidth / 2;
        const canvasCenterY = canvasPaper.offsetHeight / 2;
        const slotCenterX = newX + (currentSlotDrag.offsetWidth / 2);
        const slotCenterY = newY + (currentSlotDrag.offsetHeight / 2);
        
        let snappedX = false;
        let snappedY = false;
        
        if (!e.altKey) {
            if (Math.abs(slotCenterX - canvasCenterX) < SNAP_THRESHOLD) {
                newX = canvasCenterX - (currentSlotDrag.offsetWidth / 2);
                snappedX = true;
            }
            if (Math.abs(slotCenterY - canvasCenterY) < SNAP_THRESHOLD) {
                newY = canvasCenterY - (currentSlotDrag.offsetHeight / 2);
                snappedY = true;
            }
        }
        
        guideV.style.display = snappedX ? 'block' : 'none';
        guideH.style.display = snappedY ? 'block' : 'none';
        
        currentSlotDrag.style.left = newX + 'px';
        currentSlotDrag.style.top = newY + 'px';
    });

    document.addEventListener('touchmove', (e) => {
        if ((!isDragging || !currentSlotDrag) && (!isResizing || !currentSlotResize)) return;
        const touch = e.targetTouches[0];

        if (isResizing && currentSlotResize) {
            const dx = (touch.clientX - startX) / currentScale;
            const dy = (touch.clientY - startY) / currentScale;
            
            let newW = startW + dx;
            let newH = startH + dy;
            
            if (newW < 40) newW = 40;
            if (newH < 40) newH = 40;
            
            if (currentSlotResize.offsetLeft + newW > canvasPaper.offsetWidth) {
                newW = canvasPaper.offsetWidth - currentSlotResize.offsetLeft;
            }
            if (currentSlotResize.offsetTop + newH > canvasPaper.offsetHeight) {
                newH = canvasPaper.offsetHeight - currentSlotResize.offsetTop;
            }
            
            currentSlotResize.style.width = newW + 'px';
            currentSlotResize.style.height = newH + 'px';
            if (e.cancelable) e.preventDefault();
            return;
        }
        
        const dx = (touch.clientX - startX) / currentScale;
        const dy = (touch.clientY - startY) / currentScale;
        
        let newX = initialX + dx;
        let newY = initialY + dy;
        
        if (newX < 0) newX = 0;
        if (newY < 0) newY = 0;
        if (newX + currentSlotDrag.offsetWidth > canvasPaper.offsetWidth) newX = canvasPaper.offsetWidth - currentSlotDrag.offsetWidth;
        if (newY + currentSlotDrag.offsetHeight > canvasPaper.offsetHeight) newY = canvasPaper.offsetHeight - currentSlotDrag.offsetHeight;
        
        const canvasCenterX = canvasPaper.offsetWidth / 2;
        const canvasCenterY = canvasPaper.offsetHeight / 2;
        const slotCenterX = newX + (currentSlotDrag.offsetWidth / 2);
        const slotCenterY = newY + (currentSlotDrag.offsetHeight / 2);
        
        let snappedX = false;
        let snappedY = false;
        
        if (!e.altKey) {
            if (Math.abs(slotCenterX - canvasCenterX) < SNAP_THRESHOLD) {
                newX = canvasCenterX - (currentSlotDrag.offsetWidth / 2);
                snappedX = true;
            }
            if (Math.abs(slotCenterY - canvasCenterY) < SNAP_THRESHOLD) {
                newY = canvasCenterY - (currentSlotDrag.offsetHeight / 2);
                snappedY = true;
            }
        }
        
        guideV.style.display = snappedX ? 'block' : 'none';
        guideH.style.display = snappedY ? 'block' : 'none';
        
        currentSlotDrag.style.left = newX + 'px';
        currentSlotDrag.style.top = newY + 'px';
        if (e.cancelable) e.preventDefault();
    }, { passive: false });

    function onDragEnd() {
        if (isDragging || isResizing) {
            updateFrameData();
            guideV.style.display = 'none';
            guideH.style.display = 'none';
        }
        isDragging = false;
        isResizing = false;
        currentSlotDrag = null;
        currentSlotResize = null;
    }

    document.addEventListener('mouseup', onDragEnd);
    document.addEventListener('touchend', onDragEnd);

    // Add Slot Click Event
    document.getElementById('addSlotBtn').addEventListener('click', () => {
        slotsCounter++;
        createSlotElement('square', 40, 40 + (slotsCounter * 15), 100, 100, 'front');
        updateFrameData();
    });

    function createSlotElement(shape, x, y, w, h, layer) {
        const slot = document.createElement('div');
        slot.className = `frame-slot shape-${shape}`;
        slot.id = 'slot_' + slotsCounter;
        
        const color = vibrantColors[(slotsCounter - 1) % vibrantColors.length];
        slot.style.backgroundColor = color;
        slot.style.border = 'none'; /* No border */
        
        slot.innerHTML = `<span class="slot-number">${slotsCounter}</span><div class="resize-handle"><i class="ri-arrow-down-right-line"></i></div>`;
        
        slot.style.left = x + 'px';
        slot.style.top = y + 'px';
        slot.style.width = w + 'px';
        slot.style.height = h + 'px';
        slot.style.zIndex = layer === 'back' ? '10' : '100'; 
        
        canvasPaper.appendChild(slot);
        makeDraggable(slot);
        makeResizable(slot);
        addDoubleTapListener(slot);
        selectSlot(slot);
    }

    // Deselect if clicking canvas blank space
    canvasPaper.addEventListener('mousedown', (e) => {
        if (e.target === canvasPaper || e.target === bgLayer) {
            deselectSlot();
        }
    });

    canvasPaper.addEventListener('touchstart', (e) => {
        if (e.target === canvasPaper || e.target === bgLayer) {
            deselectSlot();
        }
    });

    function deselectSlot() {
        if (activeSlot) {
            activeSlot.classList.remove('active-slot');
            activeSlot = null;
            document.getElementById('slotProps').style.display = 'none';
        }
    }

    const slotProps = document.getElementById('slotProps');
    const slotPropTitle = document.getElementById('slotPropTitle');
    const slotOptionsBtn = document.getElementById('slotOptionsBtn');
    const slotOptionsMenu = document.getElementById('slotOptionsMenu');
    
    const layerFrontBtn = document.getElementById('layerFrontBtn');
    const layerBackBtn = document.getElementById('layerBackBtn');
    const duplicateSlotBtn = document.getElementById('duplicateSlotBtn');
    const deleteSlotBtn = document.getElementById('deleteSlotBtn');

    function selectSlot(slot) {
        if (activeSlot) activeSlot.classList.remove('active-slot');
        activeSlot = slot;
        activeSlot.classList.add('active-slot');
        
        slotProps.style.display = 'block';
        slotOptionsMenu.style.display = 'none';
        
        const num = slot.querySelector('.slot-number').innerText;
        slotPropTitle.innerHTML = `Slot ${num}`;
    }

    slotOptionsBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        slotOptionsMenu.style.display = (slotOptionsMenu.style.display === 'none') ? 'block' : 'none';
    });

    document.addEventListener('click', (e) => {
        if (!slotOptionsBtn.contains(e.target) && !slotOptionsMenu.contains(e.target)) {
            slotOptionsMenu.style.display = 'none';
        }
    });

    // Bring forward
    layerFrontBtn.addEventListener('click', () => {
        if (!activeSlot) return;
        activeSlot.style.zIndex = '100';
        slotOptionsMenu.style.display = 'none';
        updateFrameData();
    });

    // Send backward
    layerBackBtn.addEventListener('click', () => {
        if (!activeSlot) return;
        activeSlot.style.zIndex = '10';
        slotOptionsMenu.style.display = 'none';
        updateFrameData();
    });

    // Duplicate slot
    duplicateSlotBtn.addEventListener('click', () => {
        if (!activeSlot) return;
        slotsCounter++;
        
        let newX = activeSlot.offsetLeft + 15;
        let newY = activeSlot.offsetTop + 15;
        if (newX + activeSlot.offsetWidth > canvasPaper.offsetWidth) newX = canvasPaper.offsetWidth - activeSlot.offsetWidth;
        if (newY + activeSlot.offsetHeight > canvasPaper.offsetHeight) newY = canvasPaper.offsetHeight - activeSlot.offsetHeight;
        
        let shape = 'square';
        if (activeSlot.classList.contains('shape-rounded-sm')) shape = 'rounded-sm';
        if (activeSlot.classList.contains('shape-rounded-lg')) shape = 'rounded-lg';
        if (activeSlot.classList.contains('shape-circle')) shape = 'circle';
        
        const layer = activeSlot.style.zIndex === '10' ? 'back' : 'front';

        createSlotElement(shape, newX, newY, activeSlot.offsetWidth, activeSlot.offsetHeight, layer);
        updateFrameData();
    });

    // Delete slot
    deleteSlotBtn.addEventListener('click', () => {
        if (activeSlot) {
            activeSlot.remove();
            activeSlot = null;
            slotProps.style.display = 'none';
            updateFrameData();
            reindexSlots();
        }
    });

    function reindexSlots() {
        const slots = document.querySelectorAll('.frame-slot');
        slots.forEach((s, idx) => {
            s.querySelector('.slot-number').innerText = (idx + 1);
        });
        slotsCounter = slots.length;
    }

    // Write coordinate JSON to hidden fields
    window.updateFrameData = function() {
        const slots = document.querySelectorAll('.frame-slot');
        document.getElementById('slotsCountHidden').value = slots.length;
        
        let data = [];
        slots.forEach((slot, index) => {
            let shape = 'square';
            if (slot.classList.contains('shape-rounded-sm')) shape = 'rounded-sm';
            if (slot.classList.contains('shape-rounded-lg')) shape = 'rounded-lg';
            if (slot.classList.contains('shape-circle')) shape = 'circle';
            
            data.push({
                id: index + 1,
                x: slot.offsetLeft,
                y: slot.offsetTop,
                width: slot.offsetWidth,
                height: slot.offsetHeight,
                shape: shape,
                layer: (slot.style.zIndex === '10') ? 'back' : 'front'
            });
        });
        document.getElementById('frameData').value = JSON.stringify(data);
    };

    // Load initial settings / existing data
    layoutType.dispatchEvent(new Event('change'));
    
    if (isEdit && initialTemplateData) {
        if (initialTemplateData.image_url) {
            let imgUrl = initialTemplateData.image_url;
            if (imgUrl.indexOf('http') !== 0) {
                const parts = imgUrl.split('/');
                const fileName = parts[parts.length - 1];
                imgUrl = (window.BASEURL || '') + '/assets/uploads/' + fileName;
            }
            bgLayer.src = imgUrl;
            bgLayer.style.display = 'block';
            const uploadText = document.getElementById('uploadText');
            if (uploadText) {
                const parts = initialTemplateData.image_url.split('/');
                uploadText.innerText = parts[parts.length - 1].toUpperCase();
            }
        }
        
        if (initialTemplateData.frame_coordinates) {
            try {
                const frames = JSON.parse(initialTemplateData.frame_coordinates);
                frames.forEach(frame => {
                    slotsCounter++;
                    createSlotElement(frame.shape, frame.x, frame.y, frame.width, frame.height, frame.layer);
                });
                updateFrameData();
            } catch (e) {
                console.error('Error loading initial frame coordinates:', e);
            }
        }
    }

    // Responsive Canva-style bottom sheet drawer toggle
    const editorFormPanel = document.getElementById('editorFormPanel');
    const panelHeader = document.getElementById('panelHeader');
    const bottomSheetHandle = document.getElementById('bottomSheetHandle');
    const toggleLabel = document.querySelector('.mobile-sheet-toggle-label');

    function toggleBottomSheet() {
        if (window.innerWidth <= 768) {
            const isCollapsed = editorFormPanel.classList.toggle('collapsed');
            if (toggleLabel) {
                toggleLabel.innerText = isCollapsed ? 'TAB TO OPEN' : 'TAB TO CLOSE';
            }
        }
    }

    if (panelHeader) {
        panelHeader.addEventListener('click', toggleBottomSheet);
    }
    if (bottomSheetHandle) {
        bottomSheetHandle.addEventListener('click', toggleBottomSheet);
    }

    // Auto expand panel when input is focused to make typing easy
    const formInputs = editorFormPanel.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('focus', () => {
            if (window.innerWidth <= 768 && editorFormPanel.classList.contains('collapsed')) {
                editorFormPanel.classList.remove('collapsed');
                if (toggleLabel) toggleLabel.innerText = 'TAB TO CLOSE';
            }
        });
    });
});
</script>

<?php
require APPROOT . '/Views/layouts/footer.php';
?>
