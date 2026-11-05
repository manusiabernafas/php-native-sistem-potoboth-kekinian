<?php
// Load layouts header
require APPROOT . '/Views/layouts/header.php';
?>

<!-- Cozy Diamond Pattern Background -->
<div class="bg-grid-overlay"></div>

<div class="retro-monitor-container">
    <div class="retro-monitor powering-on arcade-theme arcade-theme-hide-scanlines">
        <!-- Monitor Header -->
        <div class="monitor-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="<?= BASEURL; ?>/" class="btn-monitor-back page-link" data-sound="select">
                    <i data-lucide="arrow-left" style="width: 24px; height: 24px;"></i>
                </a>
                <h1 class="monitor-title" style="margin: 0;">MOBOOTH PLAYGROUND</h1>
            </div>
            <span class="monitor-subtitle">
                <?php 
                    if (isset($_SESSION['player_name'])) {
                        if (strtolower($_SESSION['player_type'] ?? '') === 'guest') {
                            echo "HAY BRUHH, SIAP MULAI GAME?";
                        } else {
                            echo "HALLO " . strtoupper(htmlspecialchars($_SESSION['player_name'])) . ", SIAP MULAI GAME?";
                        }
                    } else {
                        echo "MODE: FREE / PUBLIC";
                    }
                ?>
            </span>
        </div>
        
        <!-- Monitor Body -->
        <div class="monitor-body">
            <!-- Step 1: Template Selection (Playful Arcade Pop Theme) -->
            <div id="template-selection-layout" class="arcade-selection-layout">
                
                <!-- Left: Sidebar controls -->
                <div class="arcade-sidebar">
                    
                    <?php
                        // Get unique categories (layout_types) for ACTIVE templates only
                        $categories = [];
                        if (!empty($templates)) {
                            foreach ($templates as $t) {
                                if ($t['status'] === 'Active' && !empty($t['layout_type'])) {
                                    $cat = strtoupper(htmlspecialchars($t['layout_type']));
                                    if (!in_array($cat, $categories)) {
                                        $categories[] = $cat;
                                    }
                                }
                            }
                        }
                    ?>
                    
                    <!-- Category Filter Area -->
                    <div class="arcade-sidebar-header">
                        <div class="arcade-filter-container">
                            <button class="arcade-filter-btn active" data-filter="all">ALL</button>
                            <?php foreach ($categories as $cat): ?>
                                <button class="arcade-filter-btn" data-filter="<?= $cat ?>"><?= $cat ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Scrollable Grid Area -->
                    <div class="arcade-sidebar-body">
                        <div class="arcade-template-grid" id="template-grid">
                            <?php if (!empty($templates)): ?>
                                <?php foreach ($templates as $t): ?>
                                    <?php if ($t['status'] === 'Active'): 
                                        $imgUrl = $t['image_url'];
                                        if (!empty($imgUrl) && strpos($imgUrl, 'http') !== 0) {
                                            $fileName = basename($imgUrl);
                                            $imgUrl = BASEURL . '/assets/uploads/' . $fileName;
                                        }
                                        $catValue = strtoupper(htmlspecialchars($t['layout_type'] ?? ''));
                                    ?>
                                        <div class="arcade-template-card template-card" 
                                             data-id="<?= $t['id'] ?>" 
                                             data-category="<?= $catValue ?>"
                                             data-slots="<?= htmlspecialchars($t['slots'] ?? 3) ?>" 
                                             data-image="<?= htmlspecialchars($imgUrl) ?>"
                                             data-name="<?= htmlspecialchars($t['name']) ?>"
                                             data-width="<?= htmlspecialchars($t['width'] ?? 200) ?>"
                                             data-height="<?= htmlspecialchars($t['height'] ?? 600) ?>"
                                             data-frame-coords="<?= htmlspecialchars($t['frame_coordinates'] ?? '[]') ?>">
                                            
                                            <?php 
                                                // Cute video game icons & playful colors
                                                $icons = ['gamepad-2', 'ghost', 'heart', 'star', 'sword', 'sparkles', 'bomb', 'skull'];
                                                $colors = ['#ec4899', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'];
                                                
                                                $iconIndex = $t['id'] % count($icons);
                                                $colorIndex = $t['id'] % count($colors);
                                                
                                                $iconName = $icons[$iconIndex];
                                                $iconColor = $colors[$colorIndex];
                                            ?>
                                            
                                            <i data-lucide="<?= $iconName ?>" style="color: <?= $iconColor ?>;" class="thumb-icon" data-basecolor="<?= $iconColor ?>"></i>
                                            
                                            <!-- Badge -->
                                            <div class="card-badge">
                                                <?= htmlspecialchars($t['slots']) ?>P
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="arcade-empty-grid">
                                    <p>NO FRAMES FOUND.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Button Area -->
                    <div class="arcade-sidebar-footer">
                        <button type="button" id="btn-confirm-template" class="arcade-btn-confirm" disabled>
                            <span>USE THIS FRAME</span>
                            <span>►</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Large Preview Area -->
                <div class="arcade-preview-area">
                    <div class="arcade-preview-container">
                        <!-- Mock Clipboard Clip -->
                        <div class="arcade-clipboard-clip"></div>

                        <!-- Canvas-style preview exactly like the editor -->
                        <div id="template-canvas-wrapper" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; position:relative;">
                            <!-- This div will be the "canvas-paper" equivalent -->
                            <div id="template-canvas-paper" style="display:none; position:relative; overflow:hidden; background:#ffffff; border: 3px solid #1f2937;">
                                <!-- Background image layer -->
                                <img id="template-bg-layer" src="" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; display:none; pointer-events:none;">
                                <!-- Slots are injected here by JS -->
                            </div>

                            <!-- Empty state -->
                            <div id="template-preview-empty" class="arcade-preview-empty">
                                <p class="q-marks">? ? ?</p>
                                <p class="pick-text">PICK A FRAME</p>
                            </div>
                        </div>

                        <!-- Info Badge -->
                        <div id="template-info-badge" class="template-info-badge" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Camera & Preview -->
            <style>
                #template-live-paper .preview-slot-box img { width: 100%; height: 100%; object-fit: cover; border-radius: inherit; }
            </style>
            <div id="playground-layout" class="arcade-pg-layout" style="display: none;">
                <style>
                    /* Arcade Playground Layout Styles */
                    .arcade-pg-layout {
                        position: relative; width: 100%; flex: 1; min-height: 0;
                        background-color: transparent;
                        display: flex; flex-direction: column;
                        padding: 16px 26px 14px 20px;
                        overflow: visible;
                        box-sizing: border-box;
                    }
                    
                    .arcade-pg-content { display: flex; gap: 20px; flex: 1; min-height: 0; z-index: 10; position: relative; justify-content: flex-start; align-items: stretch; }
                    
                    /* Left: Camera Box */
                    .arcade-cam-box { flex: 1; min-width: 0; background-color: #ffffff; border: 4px solid #1f2937; border-radius: 16px; padding: 12px; display: flex; flex-direction: column; position: relative; box-shadow: 6px 6px 0 #1f2937; box-sizing: border-box; }
                    .arcade-cam-header { position: absolute; top: 20px; left: 20px; right: 20px; display: flex; justify-content: space-between; z-index: 20; pointer-events: none; }
                    .arcade-badge-red { background-color: #ef4444; color: #fff; border-radius: 6px; padding: 6px 10px; font-size: 0.6rem; display: flex; align-items: center; gap: 6px; font-weight: 900; border: 2px solid #1f2937; box-shadow: 2px 2px 0 #1f2937; }
                    .arcade-cam-controls { display: flex; gap: 8px; pointer-events: auto; }
                    .arcade-cam-btn { background-color: rgba(31, 41, 55, 0.85); color: #fff; border: 2px solid #1f2937; border-radius: 8px; padding: 6px 10px; font-size: 0.55rem; display: flex; align-items: center; gap: 4px; font-family: 'Press Start 2P', monospace; }
                    
                    .arcade-cam-viewport { flex: 1; background-color: #d1d5db; border-radius: 10px; overflow: hidden; position: relative; border: 3px solid #1f2937; }
                    .arcade-cam-viewport video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
                    .arcade-countdown { position: absolute; top:0; left:0; width:100%; height:100%; display:flex; justify-content:center; align-items:center; font-size: 6rem; color:#fff; text-shadow: 4px 4px 0 #1f2937; z-index: 30; }
                    .arcade-shutter-flash { position: absolute; top:0; left:0; width:100%; height:100%; background:#fff; opacity:0; pointer-events:none; z-index:40; }
                    .arcade-shutter-flash.flash-active { animation: flash-anim 0.2s ease-out forwards; }
                    .arcade-cam-placeholder { position: absolute; top:0; left:0; width:100%; height:100%; display:flex; flex-direction:column; justify-content:center; align-items:center; background:#1f2937; color:#bae6fd; z-index:10; }
                    .arcade-crosshair { display: none !important; }
                    .arcade-crosshair::before, .arcade-crosshair::after { display: none !important; }
                    .arcade-crosshair::before { top: -10px; bottom: -10px; left: 28px; width: 2px; }
                    .arcade-crosshair::after { left: -10px; right: -10px; top: 28px; height: 2px; }
                    
                    .arcade-cam-footer { position: absolute; bottom: 20px; left: 20px; right: 20px; display: flex; justify-content: space-between; z-index: 20; pointer-events: none; }
                    .arcade-badge-dark { background-color: rgba(31, 41, 55, 0.85); color: #facc15; border-radius: 6px; padding: 6px 12px; font-size: 0.6rem; border: 2px solid #1f2937; font-weight: 900; }
                    .face-badge { color: #4ade80; }
                    
                    /* Right: Inventory Box */
                    .arcade-inv-box { flex: 0 0 auto; width: auto; background-color: #f1f5f9; border: 4px solid #1f2937; border-radius: 16px; display: flex; flex-direction: column; box-shadow: 6px 6px 0 #1f2937; overflow: hidden; box-sizing: border-box; }
                    .arcade-inv-header { display: flex; justify-content: space-between; align-items: center; background-color: #1f2937; padding: 12px 16px; border-bottom: 3px solid #1f2937; }
                    .arcade-inv-title { color: #fff; font-size: 0.8rem; display: flex; align-items: center; gap: 8px; letter-spacing: 1px; font-family: 'Press Start 2P', monospace; }
                    .arcade-inv-count { background-color: #4ade80; color: #1f2937; border-radius: 6px; padding: 4px 8px; font-size: 0.7rem; border: 2px solid #1f2937; font-weight: 900; }
                    
                    .arcade-inv-body { flex: 1; padding: 16px; display: flex; justify-content: center; align-items: center; background-image: repeating-linear-gradient(45deg, rgba(31,41,55,0.05) 0, rgba(31,41,55,0.05) 2px, transparent 2px, transparent 8px); }
                    
                    /* Bottom Actions */
                    .arcade-bottom-actions { display: flex; justify-content: center; gap: 20px; margin-top: 24px; z-index: 10; position: relative; padding-bottom: 12px; }
                    .arcade-action-btn { border: 4px solid #1f2937; border-radius: 12px; padding: 16px 36px; font-size: 1rem; font-family: 'Press Start 2P', monospace; display: flex; align-items: center; gap: 12px; cursor: pointer; box-shadow: 5px 5px 0 #1f2937; transition: transform 0.1s, box-shadow 0.1s; }
                    .arcade-action-btn:active { transform: translate(5px, 5px); box-shadow: 0 0 0 #1f2937; }
                </style>

                <!-- Main Content -->
                <div class="arcade-pg-content">
                    <!-- Left: Camera Box -->
                    <div class="arcade-cam-box">
                        <div class="arcade-cam-header">
                            <div class="arcade-cam-controls">
                                <button id="btn-cam-mirror" class="arcade-cam-btn"><i data-lucide="flip-horizontal" style="width:14px;height:14px;"></i> <span>MIRROR</span></button>
                                <button id="btn-cam-flip" class="arcade-cam-btn"><i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> <span>FLIP</span></button>
                                <button id="btn-cam-flash" class="arcade-cam-btn"><i data-lucide="zap" style="width:14px;height:14px;"></i> <span>FLASH</span></button>
                                <button id="btn-cam-live" class="arcade-cam-btn"><i data-lucide="radio" style="width:14px;height:14px;"></i> <span>LIVE</span></button>
                                <select id="timer-select" class="arcade-cam-btn" style="appearance:none; border:none; padding: 6px 10px; cursor:pointer; font-size:0.6rem; text-align:center; text-align-last:center; display:inline-block; background:rgba(31,41,55,0.85); color:#fff; font-family:'Press Start 2P',monospace;">
                                    <option value="3" style="background:#facc15; color:#1f2937;">⏱ 3s</option>
                                    <option value="5" style="background:#facc15; color:#1f2937;">⏱ 5s</option>
                                    <option value="10" style="background:#facc15; color:#1f2937;">⏱ 10s</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="arcade-cam-viewport" style="position:relative;">
                            <!-- Header overlay inside video viewport -->
                            <div style="position:absolute; top:12px; left:12px; right:12px; display:flex; justify-content:space-between; z-index:20; pointer-events:none;">
                                <div id="cam-live-badge" class="cam-live-badge" style="display:none;">
                                    <span class="live-dot"></span>
                                    <span>LIVE REC</span>
                                </div>
                                <div id="cam-shot-counter" class="cam-shot-counter" style="display:none;">
                                    SHOT: <span id="shot-current">0</span>/<span id="shot-total">3</span>
                                </div>
                            </div>
                            <video id="webcam" autoplay playsinline muted></video>
                            <div id="countdown" class="arcade-countdown" style="display: none;">3</div>
                            <div id="shutter-flash" class="arcade-shutter-flash"></div>
                            <div id="camera-placeholder" class="arcade-cam-placeholder">
                                <i data-lucide="camera-off" style="width: 56px; height: 56px; opacity:0.6; margin-bottom:16px;"></i>
                                <p style="font-size:0.7rem; font-weight:700;">KAMERA BELUM AKTIF</p>
                            </div>
                            <div class="arcade-crosshair"></div>
                        </div>
            
                        <div class="arcade-cam-footer" style="display:none;"></div>
                    </div>
            
                    <!-- Right: Inventory Box -->
                    <div class="arcade-inv-box">
                        <div class="arcade-inv-header">
                            <div class="arcade-inv-title" style="flex:1;">
                                BAG
                            </div>
                            <i data-lucide="chevron-down" class="inv-mobile-chevron" style="display:none; color:#fff; width:20px; height:20px;"></i>
                            <div class="arcade-inv-count"><span id="inv-shot-current">0</span>/<span id="inv-shot-total">3</span></div>
                        </div>
                        
                        <div class="arcade-inv-body">
                            <div id="template-live-wrapper" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; position:relative;">
                                <div id="template-live-paper" style="display:none; position:relative; overflow:hidden; background:#ffffff; border: 3px solid #1f2937;">
                                    <img id="template-live-bg" src="" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:fill; display:none; pointer-events:none;">
                                    <!-- Slots injected here by JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Bottom Actions -->
                <div class="arcade-bottom-actions">
                    <select id="layout-select" style="display: none;">
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="6">6</option>
                    </select>
                    
                    <div id="action-panel" style="display: none; width: 100%; flex: 1; justify-content: center;">
                        <button id="btn-next" class="arcade-action-btn" style="background-color: #22c55e; color: #fff; width: 100%; justify-content: center;">
                            <i data-lucide="chevron-right" style="width:24px;height:24px;"></i> LANJUTKAN
                        </button>
                    </div>

            
                    <button id="btn-start-camera" class="arcade-action-btn" style="background-color: #facc15; color: #1f2937;">
                        <i data-lucide="power" style="width:24px;height:24px;"></i> START KAMERA
                    </button>
                    
                    <button id="btn-upload-photo" class="arcade-action-btn" style="background-color: #ef4444; color: #fff; display:none;">
                        <i data-lucide="upload" style="width:24px;height:24px;"></i> UPLOAD
                    </button>
                    <input type="file" id="upload-file-input" accept="image/*" multiple style="display: none;">
                    
                    <button id="btn-take-photo" class="arcade-action-btn" style="background-color: #facc15; color: #1f2937; display:none;">
                        <i data-lucide="aperture" style="width:24px;height:24px;"></i> CAPTURE
                    </button>
                </div>
        </div>
        
        <!-- Step 3: Preview & Edit Layout (Hidden initially) -->
        <style>
            .filter-btn-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(64px, 1fr)); gap: 12px; width: 100%; justify-content: center; }
            .final-filter-btn { display: flex; flex-direction: column; align-items: center; gap: 8px; background: transparent; border: none; cursor: pointer; padding: 0; }
            .filter-thumb { width: 100%; aspect-ratio: 1; border-radius: 8px; border: 3px solid #1f2937; transition: transform 0.1s, box-shadow 0.1s; box-shadow: 3px 3px 0 #1f2937; }
            .final-filter-btn:active .filter-thumb { transform: translate(3px,3px); box-shadow: 0 0 0 #1f2937; }
            .final-filter-btn.active-filter .filter-thumb { outline: 3px solid #facc15; outline-offset: 2px; }
            .final-filter-btn span { color: #1f2937; font-family: 'Press Start 2P', monospace; font-size: 0.4rem; text-align: center; line-height: 1.4; }
            
            .preview-tab-container { display: flex; justify-content: center; gap: 8px; margin-bottom: 16px; width: 100%; flex-wrap: wrap; }
            .preview-tab-btn { background: #374151; color: #9ca3af; border: 2px solid #1f2937; border-radius: 20px; padding: 8px 16px; font-family: 'Press Start 2P', monospace; font-size: 0.5rem; cursor: pointer; transition: all 0.2s; box-shadow: 2px 2px 0 #1f2937; }
            .preview-tab-btn.active { background: #facc15; color: #1f2937; }
            .preview-tab-btn:active { transform: translate(2px,2px); box-shadow: 0 0 0 #1f2937; }
            
            /* iOS-like Live Photo Badge */
            .ios-live-badge {
                position: absolute;
                top: 16px;
                left: 16px;
                background: rgba(31, 41, 55, 0.75);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                color: #ffffff;
                border: 2px solid #1f2937;
                border-radius: 20px;
                padding: 6px 12px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-family: 'Press Start 2P', monospace;
                font-size: 0.45rem;
                font-weight: bold;
                z-index: 100;
                pointer-events: none;
                box-shadow: 3px 3px 0 #1f2937;
                transition: all 0.2s ease;
            }
            .ios-live-badge.active {
                background: #facc15;
                color: #1f2937;
                border-color: #1f2937;
            }
            .ios-live-icon {
                position: relative;
                width: 14px;
                height: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .ios-live-icon .ring {
                position: absolute;
                border-radius: 50%;
                border: 1.5px solid currentColor;
                box-sizing: border-box;
            }
            .ios-live-icon .ring-1 {
                width: 14px;
                height: 14px;
                border-style: dotted;
            }
            .ios-live-icon .ring-2 {
                width: 9px;
                height: 9px;
                border-style: dashed;
            }
            .ios-live-icon .ring-3 {
                width: 4px;
                height: 4px;
                background-color: currentColor;
            }
            .ios-live-badge.active .ios-live-icon .ring-1 {
                animation: spin-clockwise 4s linear infinite;
            }
            .ios-live-badge.active .ios-live-icon .ring-2 {
                animation: spin-counter-clockwise 3s linear infinite;
            }
            @keyframes spin-clockwise {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            @keyframes spin-counter-clockwise {
                from { transform: rotate(360deg); }
                to { transform: rotate(0deg); }
            }
            
            @media (max-width: 600px), (orientation: portrait) {
                .filter-btn-grid { display: flex; flex-direction: row; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 12px; -webkit-overflow-scrolling: touch; gap: 8px; justify-content: flex-start; }
                .final-filter-btn { flex: 0 0 auto; width: 48px; }
                .final-filter-btn span { font-size: 0.32rem; }
            }
        </style>
        <div id="preview-edit-layout" class="arcade-selection-layout" style="display: none;">
            
            <!-- Left (visually left on desktop): Main Preview Area -->
            <div class="arcade-preview-area" style="background-image: repeating-linear-gradient(45deg, rgba(31,41,55,0.05) 0, rgba(31,41,55,0.05) 2px, transparent 2px, transparent 8px); display:flex; flex-direction:column; padding: 20px;">
                
                <!-- Format Selection Tabs -->
                <div class="preview-tab-container">
                    <button class="preview-tab-btn active" data-format="strip">STRIP</button>
                    <button class="preview-tab-btn" data-format="raw">RAW</button>
                    <button class="preview-tab-btn" data-format="gif">GIF</button>
                    <button class="preview-tab-btn" id="tab-live" data-format="live" style="display:none;">LIVE</button>
                </div>

                <div class="arcade-preview-container" style="flex:1; justify-content: center; align-items: center; overflow:hidden; position:relative;">
                    
                    <!-- Strip Canvas -->
                    <div id="canvas-zoom-wrapper" style="width:100%; height:100%; overflow:hidden; position:relative; display:flex; align-items:center; justify-content:center;">
                        <canvas id="final-strip-canvas" style="max-height:100%; max-width:100%; object-fit:contain; border:4px solid #1f2937; box-shadow:10px 10px 0 rgba(0,0,0,0.5); cursor:pointer; touch-action:none;"></canvas>
                        
                        <!-- Live Photo Badge Overlay -->
                        <div id="live-photo-badge" class="ios-live-badge" style="display: none;">
                            <div class="ios-live-icon">
                                <span class="ring ring-1"></span>
                                <span class="ring ring-2"></span>
                                <span class="ring ring-3"></span>
                            </div>
                            <span class="ios-live-text">LIVE</span>
                        </div>
                    </div>
                    <!-- Tap hint -->
                    <div id="canvas-tap-hint" style="position:absolute; bottom:8px; left:50%; transform:translateX(-50%); background:rgba(31,41,55,0.75); color:#facc15; font-family:'Press Start 2P',monospace; font-size:0.4rem; padding:5px 10px; border-radius:20px; pointer-events:none; white-space:nowrap;">✦ GESER & CUBIT FOTO UNTUK ADJUST</div>
                    
                    <!-- Raw Photos Grid (Hidden initially) -->
                    <div id="raw-preview-wrapper" style="display:none; width:100%; height:100%; position:relative; align-items:center; justify-content:center;">
                        <button id="raw-prev-btn" style="position:absolute; left:10px; z-index:10; background:#facc15; border:4px solid #1f2937; border-radius:50%; width:40px; height:40px; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow: 2px 2px 0 rgba(0,0,0,0.5);"><i data-lucide="chevron-left" style="width:24px;height:24px;color:#1f2937;"></i></button>
                        <div id="final-raw-container" style="width:100%; height:100%; overflow-x:auto; overflow-y:hidden; scroll-snap-type: x mandatory; scroll-behavior: smooth; transition: filter 0.3s; padding-bottom: 20px; display:flex;"></div>
                        <button id="raw-next-btn" style="position:absolute; right:10px; z-index:10; background:#facc15; border:4px solid #1f2937; border-radius:50%; width:40px; height:40px; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow: 2px 2px 0 rgba(0,0,0,0.5);"><i data-lucide="chevron-right" style="width:24px;height:24px;color:#1f2937;"></i></button>
                    </div>
                    
                    <!-- Placeholder for GIF/Live (Hidden initially) -->
                    <div id="final-media-placeholder" style="display:none; width:100%; height:100%; align-items:center; justify-content:center; flex-direction:column; gap:16px;">
                        <i data-lucide="video" style="width:48px;height:48px; color:#1f2937;"></i>
                        <p style="color:#1f2937; font-family:'Press Start 2P',monospace; font-size:0.6rem; text-align:center; line-height:1.5;">MEDIA NOT<br>RECORDED YET</p>
                    </div>

                </div>
            </div>

            <!-- Right (visually right on desktop): Filters & Action Sidebar -->
            <div class="arcade-sidebar">
                <div class="arcade-sidebar-header" style="border-bottom: 4px solid #1f2937; padding-bottom:12px;">
                    <div style="color:#1f2937; font-family:'Press Start 2P',monospace; font-size:0.8rem; text-align:center;">PILIH FILTER</div>
                </div>
                
                <div class="arcade-sidebar-body" style="padding: 16px; display:flex; flex-direction:column; flex: 1; overflow-y: auto; gap: 12px;">
                    <div class="filter-btn-grid">
                        <button class="final-filter-btn active-filter" data-filter="none">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #facc15, #f43f5e);"></div>
                            <span>NORMAL</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(100%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #9ca3af, #374151);"></div>
                            <span>B&W</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(100%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #d97706, #78350f);"></div>
                            <span>SEPIA</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.2) saturate(1.5)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #ef4444, #3b82f6);"></div>
                            <span>VIVID</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(50%) hue-rotate(-30deg) saturate(140%) contrast(1.1)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #8b5cf6, #ec4899);"></div>
                            <span>VINTAGE</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(40%) contrast(1.5) brightness(0.9) saturate(1.5) hue-rotate(-10deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #ec4899, #facc15);"></div>
                            <span>RETRO</span>
                        </button>
                        <button class="final-filter-btn" data-filter="hue-rotate(180deg) saturate(120%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #3b82f6, #06b6d4);"></div>
                            <span>COLD</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(30%) hue-rotate(-15deg) saturate(120%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #f97316, #facc15);"></div>
                            <span>WARM</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.8) brightness(1.2) saturate(0.8)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #a8a29e, #d6d3d1);"></div>
                            <span>FADED</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(20%) contrast(1.2) brightness(1.1) saturate(1.3) hue-rotate(-5deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #eab308, #b91c1c);"></div>
                            <span>KODAK</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.1) saturate(1.4) hue-rotate(10deg) brightness(1.05)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #10b981, #3b82f6);"></div>
                            <span>FUJI</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(30%) contrast(1.1) brightness(1.1) saturate(1.1) hue-rotate(-15deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fde047, #fbcfe8);"></div>
                            <span>POLAROID</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.5) saturate(1.5) brightness(0.9)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #ec4899, #8b5cf6);"></div>
                            <span>LOMO</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.3) saturate(0.8) brightness(0.9) sepia(20%) hue-rotate(180deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #0ea5e9, #f97316);"></div>
                            <span>CINEMA</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.8) brightness(1.15) saturate(0.8) sepia(10%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fbcfe8, #bfdbfe);"></div>
                            <span>PASTEL</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.4) saturate(2) hue-rotate(300deg) brightness(1.1)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #ec4899, #06b6d4);"></div>
                            <span>CYBER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(100%) contrast(1.5) brightness(0.8)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #111827, #4b5563);"></div>
                            <span>NOIR</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.8) brightness(1.1) sepia(10%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #d6d3d1, #f5f5f4);"></div>
                            <span>MATTE</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.3) saturate(0.5) brightness(0.8) sepia(20%) hue-rotate(200deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #312e81, #0f172a);"></div>
                            <span>GOTHIC</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.1) saturate(1.6) hue-rotate(330deg) brightness(1.05) sepia(10%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #f43f5e, #fbbf24);"></div>
                            <span>AGFA</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.2) saturate(1.2) brightness(1.0) sepia(15%) hue-rotate(-10deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #b45309, #78350f);"></div>
                            <span>ILFORD</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.9) saturate(1.3) brightness(1.1) hue-rotate(5deg) sepia(5%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #059669, #d97706);"></div>
                            <span>VELVIA</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(80%) contrast(1.2) brightness(0.9) sepia(30%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #92400e, #78716c);"></div>
                            <span>ANALOG</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.15) saturate(1.5) brightness(1.0) hue-rotate(-20deg) sepia(10%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #7c3aed, #db2777);"></div>
                            <span>SLIDE</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.05) saturate(0.9) brightness(1.2) sepia(5%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #e2e8f0, #94a3b8);"></div>
                            <span>CLEAN</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.75) brightness(1.3) saturate(0.6) sepia(15%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fef3c7, #fde68a);"></div>
                            <span>DREAM</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.4) saturate(0.0) brightness(0.85) sepia(40%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #44403c, #1c1917);"></div>
                            <span>SILVER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.3) saturate(1.2) brightness(0.85) hue-rotate(10deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #065f46, #0f172a);"></div>
                            <span>FOREST</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.1) saturate(1.8) brightness(1.1) hue-rotate(-5deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #f59e0b, #ef4444);"></div>
                            <span>SUMMER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.9) saturate(0.7) brightness(1.0) sepia(20%) hue-rotate(180deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #7dd3fc, #a5b4fc);"></div>
                            <span>WINTER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.2) saturate(1.4) brightness(0.9) hue-rotate(-30deg) sepia(25%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #dc2626, #f97316);"></div>
                            <span>GOLDEN</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.0) saturate(1.6) brightness(1.05) hue-rotate(200deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #2563eb, #7c3aed);"></div>
                            <span>OCEAN</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.3) saturate(0.3) brightness(1.1) sepia(50%) hue-rotate(350deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fcd34d, #fef3c7);"></div>
                            <span>FADE90</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(30%) contrast(1.3) saturate(0.8) brightness(0.9)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #475569, #1e293b);"></div>
                            <span>DUSK</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.2) saturate(2.0) brightness(1.1) hue-rotate(270deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #a855f7, #3b82f6);"></div>
                            <span>NEON</span>
                        </button>
                        <button class="final-filter-btn" data-filter="sepia(60%) contrast(1.3) brightness(0.85) saturate(1.2)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #92400e, #fbbf24);"></div>
                            <span>COPPER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.1) saturate(1.1) brightness(1.15) hue-rotate(15deg) sepia(8%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #bbf7d0, #bfdbfe);"></div>
                            <span>AIRY</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.6) saturate(1.8) brightness(0.85)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #7f1d1d, #1e3a5f);"></div>
                            <span>PUNCH</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(50%) sepia(50%) contrast(1.1) brightness(1.0) hue-rotate(-10deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #d4a76a, #8b6914);"></div>
                            <span>MOCHA</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(0.85) brightness(1.2) saturate(1.2) hue-rotate(30deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fde68a, #bbf7d0);"></div>
                            <span>SPRING</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.3) saturate(0.6) brightness(0.8) hue-rotate(240deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #1e40af, #0f172a);"></div>
                            <span>NIGHT</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.1) saturate(1.3) brightness(1.0) sepia(25%) hue-rotate(-25deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #f97316, #854d0e);"></div>
                            <span>AMBER</span>
                        </button>
                        <button class="final-filter-btn" data-filter="grayscale(100%) contrast(1.8) brightness(1.1) invert(100%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #ffffff, #1f2937);"></div>
                            <span>X-RAY</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.0) saturate(1.0) brightness(1.0) hue-rotate(120deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #22c55e, #84cc16);"></div>
                            <span>MATRIX</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.2) saturate(1.5) brightness(0.95) sepia(30%) hue-rotate(-40deg)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #c026d3, #7c3aed);"></div>
                            <span>GRAPE</span>
                        </button>
                        <button class="final-filter-btn" data-filter="contrast(1.05) saturate(0.8) brightness(1.25) sepia(5%)">
                            <div class="filter-thumb" style="background: linear-gradient(45deg, #fef9c3, #fef3c7);"></div>
                            <span>GLOW</span>
                        </button>
                    </div>
                </div>
                
                <div class="arcade-sidebar-footer" style="display:flex; flex-direction:row; gap:8px; padding-top: 12px; border-top: 4px solid #1f2937;">
                    <button id="btn-final-download" class="arcade-action-btn" style="background:#22c55e; color:#fff; flex:1; justify-content:center; padding:12px; font-size:0.6rem;">
                        <i data-lucide="download" style="width:18px;height:18px; margin-right:4px;"></i> SIMPAN
                    </button>
                    <button id="btn-end-game" class="arcade-action-btn" style="background:#ef4444; color:#fff; flex:1; justify-content:center; padding:12px; font-size:0.6rem;">
                        <i data-lucide="power" style="width:18px;height:18px; margin-right:4px;"></i> END GAME
                    </button>
                </div>
            </div>
        </div>
</div>

<!-- Download Options Modal -->
<div id="download-modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center; backdrop-filter:blur(5px);">
    <div style="background:#1f2937; padding:24px; border-radius:12px; width:90%; max-width:800px; border:4px solid #facc15; box-shadow:8px 8px 0 rgba(0,0,0,0.5);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:2px solid #374151; padding-bottom:12px;">
            <h2 style="color:#facc15; font-family:'Press Start 2P',monospace; font-size:1rem; margin:0;">PILIH FORMAT DOWNLOAD</h2>
            <button id="btn-close-download-modal" style="background:none; border:none; color:#ef4444; cursor:pointer;"><i data-lucide="x" style="width:32px;height:32px;"></i></button>
        </div>
        
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:16px;">
            <!-- Card Strip -->
            <div class="download-card" style="background:#374151; border-radius:8px; padding:16px; text-align:center; display:flex; flex-direction:column; justify-content:space-between; border:2px solid transparent;">
                <div style="color:#bae6fd; font-family:'Press Start 2P',monospace; font-size:0.7rem; margin-bottom:12px;">FOTO STRIP</div>
                <div style="flex:1; display:flex; justify-content:center; align-items:center; margin-bottom:16px; background:#111827; border-radius:4px; padding:12px;">
                    <i data-lucide="image" style="width:48px;height:48px; color:#9ca3af;"></i>
                </div>
                <button id="btn-dl-strip" class="arcade-action-btn" style="background:#3b82f6; color:#fff; width:100%; justify-content:center; font-size:0.6rem; padding:12px;">DOWNLOAD</button>
            </div>

            <!-- Card RAW -->
            <div class="download-card" style="background:#374151; border-radius:8px; padding:16px; text-align:center; display:flex; flex-direction:column; justify-content:space-between; border:2px solid transparent;">
                <div style="color:#bae6fd; font-family:'Press Start 2P',monospace; font-size:0.7rem; margin-bottom:12px;">FOTO RAW</div>
                <div style="flex:1; display:flex; justify-content:center; align-items:center; margin-bottom:16px; background:#111827; border-radius:4px; padding:12px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:4px;">
                        <div style="width:20px;height:20px;background:#9ca3af;border-radius:2px;"></div>
                        <div style="width:20px;height:20px;background:#9ca3af;border-radius:2px;"></div>
                        <div style="width:20px;height:20px;background:#9ca3af;border-radius:2px;"></div>
                        <div style="width:20px;height:20px;background:#9ca3af;border-radius:2px;"></div>
                    </div>
                </div>
                <button id="btn-dl-raw" class="arcade-action-btn" style="background:#10b981; color:#fff; width:100%; justify-content:center; font-size:0.6rem; padding:12px;">DOWNLOAD</button>
            </div>

            <!-- Card GIF -->
            <div class="download-card" style="background:#374151; border-radius:8px; padding:16px; text-align:center; display:flex; flex-direction:column; justify-content:space-between; border:2px solid transparent;">
                <div style="color:#bae6fd; font-family:'Press Start 2P',monospace; font-size:0.7rem; margin-bottom:12px;">ANIMASI GIF</div>
                <div style="flex:1; display:flex; justify-content:center; align-items:center; margin-bottom:16px; background:#111827; border-radius:4px; padding:12px;">
                    <i data-lucide="film" style="width:48px;height:48px; color:#9ca3af;"></i>
                </div>
                <button id="btn-dl-gif" class="arcade-action-btn" style="background:#a855f7; color:#fff; width:100%; justify-content:center; font-size:0.6rem; padding:12px;">DOWNLOAD</button>
            </div>

            <!-- Card Live Photo -->
            <div class="download-card" style="background:#374151; border-radius:8px; padding:16px; text-align:center; display:flex; flex-direction:column; justify-content:space-between; border:2px solid transparent;">
                <div style="color:#bae6fd; font-family:'Press Start 2P',monospace; font-size:0.7rem; margin-bottom:12px;">LIVE PHOTO</div>
                <div style="flex:1; display:flex; justify-content:center; align-items:center; margin-bottom:16px; background:#111827; border-radius:4px; padding:12px;">
                    <div style="position:relative; width:48px; height:48px; border:2px dashed #9ca3af; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <div style="width:24px; height:24px; background:#9ca3af; border-radius:50%;"></div>
                    </div>
                </div>
                <button id="btn-dl-live" class="arcade-action-btn" style="background:#ef4444; color:#fff; width:100%; justify-content:center; font-size:0.6rem; padding:12px;">DOWNLOAD</button>
            </div>
            
        </div>
    </div>
</div>

<!-- Replace Slot Modal Styles -->
<style>
    .replace-modal-box {
        background: #1e293b;
        border: 4px solid #facc15;
        border-radius: 16px;
        padding: 40px 30px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .replace-modal-title {
        color: #facc15;
        font-size: 1rem;
        margin-bottom: 12px;
        line-height: 1.6;
    }
    .replace-modal-subtitle {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-bottom: 32px;
    }
    .replace-btn-group {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: nowrap;
    }
    .replace-modal-btn {
        font-family: 'Press Start 2P', monospace;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 20px;
        border: 3px solid #1f2937;
        border-radius: 12px;
        font-size: 0.75rem;
        flex: 1;
        transition: transform 0.1s, box-shadow 0.1s;
        box-shadow: 4px 4px 0 #000;
    }
    .replace-modal-btn:active {
        transform: translate(4px, 4px);
        box-shadow: 0 0 0 #000;
    }
    .replace-modal-btn i {
        width: 20px !important;
        height: 20px !important;
    }
    @media (max-width: 900px), (orientation: portrait) {
        .replace-modal-box { padding: 24px 16px; max-width: 320px; border-width: 3px; }
        .replace-modal-title { font-size: 0.65rem; margin-bottom: 8px; }
        .replace-modal-subtitle { font-size: 0.5rem; margin-bottom: 24px; }
        .replace-btn-group { flex-wrap: wrap; gap: 8px; }
        .replace-modal-btn { padding: 12px 14px; font-size: 0.45rem; border-width: 2px; box-shadow: 2px 2px 0 #000; gap: 6px; }
        .replace-modal-btn:active { transform: translate(2px, 2px); }
        .replace-modal-btn i { width: 16px !important; height: 16px !important; }
    }
</style>

<!-- Replace Slot Modal — outside all fixed containers -->
<div id="replace-slot-modal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.75); z-index:9999; align-items:center; justify-content:center;">
    <div class="replace-modal-box">
        <p class="replace-modal-title">GANTI FOTO</p>
        <p class="replace-modal-subtitle">Slot #<span id="replace-slot-num">1</span></p>
        <div class="replace-btn-group">
            <button id="replace-camera-btn" class="replace-modal-btn" style="background:#facc15; color:#1f2937;">
                <i data-lucide="aperture"></i> KAMERA
            </button>
            <button id="replace-upload-btn" class="replace-modal-btn" style="background:#38bdf8; color:#fff;">
                <i data-lucide="upload"></i> GALERI
            </button>
            <button id="replace-cancel-btn" class="replace-modal-btn" style="background:#ef4444; color:#fff;">
                <i data-lucide="x"></i> BATAL
            </button>
        </div>
    </div>
</div>
<input type="file" id="replace-file-input" accept="image/*" style="display:none;">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gifshot/0.3.2/gifshot.min.js"></script>

<script>
function drawVideoCover(ctx, video, dWidth, dHeight) {
    const sWidth = video.videoWidth || video.width;
    const sHeight = video.videoHeight || video.height;
    if (!sWidth || !sHeight) {
        ctx.drawImage(video, 0, 0, dWidth, dHeight);
        return;
    }
    const sRatio = sWidth / sHeight;
    const dRatio = dWidth / dHeight;
    let sx = 0, sy = 0, sw = sWidth, sh = sHeight;
    if (sRatio > dRatio) {
        sw = sHeight * dRatio;
        sx = (sWidth - sw) / 2;
    } else {
        sh = sWidth / dRatio;
        sy = (sHeight - sh) / 2;
    }
    ctx.drawImage(video, sx, sy, sw, sh, 0, 0, dWidth, dHeight);
}

// Web Audio API Retro 8-bit sound synthesizer
let _audioCtx = null;
function getAudioCtx() {
    // Prevent Chrome warning if there hasn't been a user gesture yet
    if (navigator.userActivation && !navigator.userActivation.hasBeenActive) {
        return null;
    }
    if (!_audioCtx) {
        const AC = window.AudioContext || window.webkitAudioContext;
        if (!AC) return null;
        _audioCtx = new AC();
    }
    if (_audioCtx.state === 'suspended') {
        _audioCtx.resume().catch(() => {});
    }
    return _audioCtx;
}

function playRetroSound(type) {
    try {
        const ctx = getAudioCtx();
        if (!ctx || ctx.state === 'suspended') return;
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        
        if (type === 'beep') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
            osc.start();
            osc.stop(ctx.currentTime + 0.1);
        } else if (type === 'shutter') {
            // White noise burst for camera shutter click
            const bufferSize = ctx.sampleRate * 0.15;
            const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = Math.random() * 2 - 1;
            }
            const noise = ctx.createBufferSource();
            noise.buffer = buffer;
            const noiseFilter = ctx.createBiquadFilter();
            noiseFilter.type = 'bandpass';
            noiseFilter.frequency.value = 1000;
            noise.connect(noiseFilter);
            noiseFilter.connect(gain);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
            noise.start();
            noise.stop(ctx.currentTime + 0.15);
        } else if (type === 'select') {
            osc.type = 'square';
            osc.frequency.setValueAtTime(300, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(700, ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.16);
            osc.start();
            osc.stop(ctx.currentTime + 0.16);
        }
    } catch (e) {}
}

// Resume AudioContext on first user interaction (Chrome autoplay policy)
document.addEventListener('click', () => { getAudioCtx(); }, { once: false });

document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('webcam');
    const btnStartCamera = document.getElementById('btn-start-camera');
    const btnTakePhoto = document.getElementById('btn-take-photo');
    const cameraPlaceholder = document.getElementById('camera-placeholder');
    const layoutSelect = document.getElementById('layout-select');
    const stripPreview = document.getElementById('strip-preview');
    const countdown = document.getElementById('countdown');
    const shutterFlash = document.getElementById('shutter-flash');
    const btnDownload = document.getElementById('btn-download'); // may be null
    const btnRetake = document.getElementById('btn-retake');     // may be null
    const btnNext = document.getElementById('btn-next');
    const actionPanel = document.getElementById('action-panel');
    const invShotCurrent = document.getElementById('inv-shot-current');
    const invShotTotal = document.getElementById('inv-shot-total');
    
    // New UI elements
    const camLiveBadge = document.getElementById('cam-live-badge');
    const camShotCounter = document.getElementById('cam-shot-counter');
    const shotCurrent = document.getElementById('shot-current');
    const shotTotal = document.getElementById('shot-total');
    
    // Layout views
    const templateSelectionLayout = document.getElementById('template-selection-layout');
    const playgroundLayout = document.getElementById('playground-layout');
    
    // Preview Elements
    const templateCards = document.querySelectorAll('.template-card');
    const previewEmpty = document.getElementById('template-preview-empty');
    const btnConfirmTemplate = document.getElementById('btn-confirm-template');
    
    // --- sessionStorage keys ---
    const SS_STEP = 'pg_step';
    const SS_SLOTS = 'pg_slots';
    const SS_IMG = 'pg_img';
    const SS_COORDS = 'pg_coords';
    const SS_W = 'pg_w';
    const SS_H = 'pg_h';
    const SS_NAME = 'pg_name';
    const SS_IMAGES = 'pg_images'; // stores capturedImages as JSON
    const SS_LIVE = 'pg_live';
    const SS_GIF = 'pg_gif';
    const SS_LIVE_PHOTOS = 'pg_live_photos';

    let capturedLivePhotos = [];
    let liveFrameBuffer = [];
    let liveFrameInterval = null;
    let liveAnimationFrameIndex = -1;
    let livePlayTimer = null;
    const imgCache = { bg: null, photos: [], livePhotos: [] };

    try {
        capturedLivePhotos = JSON.parse(sessionStorage.getItem(SS_LIVE_PHOTOS) || '[]');
    } catch(e) {
        capturedLivePhotos = [];
    }

    // ---- Helper: save selected template to session ----
    function saveTemplateSession(slots, imgUrl, coords, w, h, name) {
        sessionStorage.setItem(SS_SLOTS, slots);
        sessionStorage.setItem(SS_IMG, imgUrl || '');
        sessionStorage.setItem(SS_COORDS, coords || '[]');
        sessionStorage.setItem(SS_W, w);
        sessionStorage.setItem(SS_H, h);
        sessionStorage.setItem(SS_NAME, name || '');
    }

    function clearTemplateSession() {
        [SS_STEP, SS_SLOTS, SS_IMG, SS_COORDS, SS_W, SS_H, SS_NAME, SS_IMAGES, SS_LIVE, SS_GIF, SS_LIVE_PHOTOS].forEach(k => sessionStorage.removeItem(k));
        capturedLivePhotos = [];
    }

    function saveImages() {
        try { sessionStorage.setItem(SS_IMAGES, JSON.stringify(capturedImages)); } catch(e) {}
    }

    function saveLivePhotos() {
        try { sessionStorage.setItem(SS_LIVE_PHOTOS, JSON.stringify(capturedLivePhotos)); } catch(e) {}
    }

    // Filtering logic
    const filterBtns = document.querySelectorAll('.arcade-filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            playRetroSound('select');
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filterValue = btn.getAttribute('data-filter');
            templateCards.forEach(card => {
                card.style.display = (filterValue === 'all' || card.getAttribute('data-category') === filterValue) ? 'flex' : 'none';
            });
        });
    });

    let stream = null;
    let capturedImages = [];
    let isCapturing = false;
    let selectedTemplateUrl = '';

    // Add inline styles for animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }
        .template-card:hover { transform: translateY(-2px); }
        @keyframes popIn { 0% { transform: translate(-50%, 10px) scale(0.8); opacity: 0; } 100% { transform: translate(-50%, 0) scale(1); opacity: 1; } }
    `;
    document.head.appendChild(style);

    // ---- Preview render function (shared) ----
    function renderTemplatePreview(frameCoords, tplW, tplH, imgUrl, slots) {
        const canvasPaper = document.getElementById('template-canvas-paper');
        const bgLayer = document.getElementById('template-bg-layer');
        const canvasWrapper = document.getElementById('template-canvas-wrapper');
        const infoBadge = document.getElementById('template-info-badge');
        const previewEmptyEl = document.getElementById('template-preview-empty');

        const hasCoords = frameCoords && frameCoords !== '[]' && frameCoords !== '';
        if (imgUrl || hasCoords) {
            previewEmptyEl.style.display = 'none';
            canvasPaper.style.display = 'block';

            const wrapperW = canvasWrapper.offsetWidth;
            const wrapperH = canvasWrapper.offsetHeight;
            const scale = Math.min(wrapperW / tplW, wrapperH / tplH, 1);
            canvasPaper.style.width = (tplW * scale) + 'px';
            canvasPaper.style.height = (tplH * scale) + 'px';

            canvasPaper.querySelectorAll('.preview-slot-box').forEach(el => el.remove());

            const slotColors = ['#ef4444', '#eab308', '#22c55e', '#3b82f6', '#a855f7', '#f97316'];
            try {
                const frames = JSON.parse(frameCoords || '[]');
                frames.forEach((f, idx) => {
                    const box = document.createElement('div');
                    box.className = 'preview-slot-box';
                    box.style.cssText = `position:absolute;left:${f.x*scale}px;top:${f.y*scale}px;width:${f.width*scale}px;height:${f.height*scale}px;background:${slotColors[idx%slotColors.length]};border:3px solid rgba(255,255,255,0.4);opacity:1;display:flex;align-items:center;justify-content:center;font-family:'Press Start 2P',monospace;font-size:${Math.max(8,Math.min(f.width,f.height)*scale*0.3)}px;color:#fff;font-weight:700;text-shadow:1px 1px 2px rgba(0,0,0,0.5);box-sizing:border-box;pointer-events:none;z-index:${(f.layer||'back')==='front'?100:10};border-radius:${{square:'0','rounded-sm':'8px','rounded-lg':'24px','circle':'50%'}[f.shape]||'0'};`;
                    box.textContent = f.id || (idx + 1);
                    canvasPaper.appendChild(box);
                });
            } catch(e) {}

            if (imgUrl) {
                bgLayer.src = imgUrl;
                bgLayer.style.display = 'block';
                bgLayer.style.zIndex = '50';
                bgLayer.style.objectFit = 'fill';
            } else {
                bgLayer.style.display = 'none';
                bgLayer.src = '';
            }

            if (infoBadge) {
                infoBadge.textContent = slots + ' PHOTO SLOTS';
                infoBadge.style.display = 'block';
                infoBadge.style.animation = 'none';
                void infoBadge.offsetWidth;
                infoBadge.style.animation = 'popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
            }
        } else {
            previewEmptyEl.style.display = 'block';
            canvasPaper.style.display = 'none';
            bgLayer.style.display = 'none';
            bgLayer.src = '';
            if (infoBadge) infoBadge.style.display = 'none';
        }
    }

    // Handle template card clicks
    templateCards.forEach(card => {
        card.addEventListener('click', () => {
            playRetroSound('select');
            
            templateCards.forEach(c => {
                c.style.borderColor = '#1f2937';
                c.style.background = '#ffffff';
                const icon = c.querySelector('.thumb-icon');
                if(icon) icon.style.color = '#94a3b8';
            });
            
            card.style.borderColor = '#1f2937';
            card.style.background = '#ef4444';
            const selIcon = card.querySelector('.thumb-icon');
            if(selIcon) selIcon.style.color = '#ffffff';
            
            const slots = card.getAttribute('data-slots');
            selectedTemplateUrl = card.getAttribute('data-image');
            const frameCoords = card.getAttribute('data-frame-coords');
            const tplW = parseFloat(card.getAttribute('data-width') || 200);
            const tplH = parseFloat(card.getAttribute('data-height') || 600);
            const frameName = card.getAttribute('data-name');

            layoutSelect.value = slots;
            renderTemplatePreview(frameCoords, tplW, tplH, selectedTemplateUrl, slots);
            
            btnConfirmTemplate.disabled = false;
            btnConfirmTemplate.style.opacity = '1';
            btnConfirmTemplate.style.filter = 'none';
            btnConfirmTemplate.style.cursor = 'pointer';

            // Save to session in case of confirm
            saveTemplateSession(slots, selectedTemplateUrl, frameCoords, tplW, tplH, frameName);
        });
    });

    // Handle confirm template button
    btnConfirmTemplate.addEventListener('click', () => {
        if (btnConfirmTemplate.disabled) return;
        playRetroSound('select');
        sessionStorage.setItem(SS_STEP, 'camera');
        templateSelectionLayout.style.display = 'none';
        playgroundLayout.style.display = 'flex';
        renderPreviewPlaceholders();
        if (shotTotal) shotTotal.textContent = layoutSelect.value;
        if (btnStartCamera) setTimeout(() => btnStartCamera.click(), 100);
    });

    function updateSlotVisual(idx, dataUrl, element = null) {
        const box = element || document.getElementById(`strip-item-${idx}`);
        if (!box) return;
        
        box.className = 'preview-slot-box';
        box.style.cursor = 'pointer';
        box.setAttribute('data-slot-idx', idx);
        box.innerHTML = `<img src="${dataUrl}" alt="Photo ${idx + 1}" style="width:100%;height:100%;object-fit:cover;display:block;pointer-events:none;">`;
    }

    // Render Preview Strip placeholders dynamically
    function renderPreviewPlaceholders(resetPhotos = false) {
        const slots = parseInt(layoutSelect.value || sessionStorage.getItem(SS_SLOTS) || '3');
        const imgUrl = sessionStorage.getItem(SS_IMG) || '';
        const coords = sessionStorage.getItem(SS_COORDS) || '[]';
        const tplW = parseFloat(sessionStorage.getItem(SS_W) || 200);
        const tplH = parseFloat(sessionStorage.getItem(SS_H) || 600);
        const name = sessionStorage.getItem(SS_NAME) || 'MARIO BROS';
        
        if (invShotTotal) invShotTotal.innerText = slots;
        if (invShotCurrent && resetPhotos) invShotCurrent.innerText = '0';
        
        if (resetPhotos) {
            capturedImages = [];
            capturedLivePhotos = [];
            imgCache.photos = [];
            imgCache.livePhotos = [];
            saveImages();
            saveLivePhotos();
        }
        
        const canvasPaper = document.getElementById('template-live-paper');
        const bgLayer = document.getElementById('template-live-bg');
        const canvasWrapper = document.getElementById('template-live-wrapper');
        
        if (!canvasPaper || !canvasWrapper) return;
        
        canvasPaper.style.display = 'block';
        const invBody = document.querySelector('.arcade-inv-body');
        const availableH = invBody ? (invBody.clientHeight - 32) : canvasWrapper.offsetHeight;
        const scale = availableH / tplH;
        
        canvasPaper.style.width = (tplW * scale) + 'px';
        canvasPaper.style.height = availableH + 'px';
        canvasWrapper.style.width = (tplW * scale) + 'px';
        
        canvasPaper.querySelectorAll('.preview-slot-box').forEach(el => el.remove());
        
        if (imgUrl) {
            bgLayer.src = imgUrl;
            bgLayer.style.display = 'block';
            bgLayer.style.zIndex = '50';
            bgLayer.style.pointerEvents = 'none';
        } else {
            bgLayer.style.display = 'none';
        }
        
        try {
            const frames = JSON.parse(coords || '[]');
            const slotColors = ['#ef4444', '#eab308', '#22c55e', '#3b82f6', '#a855f7', '#f97316'];
            frames.forEach((f, idx) => {
                if (idx >= slots) return;
                const box = document.createElement('div');
                box.id = `strip-item-${idx}`; 
                box.style.cssText = `position:absolute;left:${f.x*scale}px;top:${f.y*scale}px;width:${f.width*scale}px;height:${f.height*scale}px;background:${slotColors[idx%slotColors.length]};border:none;display:flex;align-items:center;justify-content:center;font-family:'Press Start 2P',monospace;font-size:${Math.max(8,Math.min(f.width,f.height)*scale*0.3)}px;color:#fff;font-weight:700;box-sizing:border-box;z-index:${(f.layer||'back')==='front'?100:10};border-radius:${{square:'0','rounded-sm':'8px','rounded-lg':'24px','circle':'50%'}[f.shape]||'0'};overflow:hidden;`;
                
                if (capturedImages[idx]) {
                    updateSlotVisual(idx, capturedImages[idx], box);
                } else {
                    box.className = 'preview-slot-box placeholder-photo';
                    box.innerText = f.id || (idx + 1);
                    box.style.cursor = 'default';
                }
                
                canvasPaper.appendChild(box);
            });
        } catch(e) {}
    }
    
    renderPreviewPlaceholders(true);

    // Event delegation: clicking a captured slot opens retake modal
    const livePaper = document.getElementById('template-live-paper');
    if (livePaper) {
        livePaper.addEventListener('click', function(e) {
            const box = e.target.closest('[data-slot-idx]');
            if (!box) return;
            const idx = parseInt(box.getAttribute('data-slot-idx'));
            openReplaceModal(idx);
        });
    }
    layoutSelect.addEventListener('change', () => {
        if (!isCapturing) renderPreviewPlaceholders(true);
    });

    // Mobile Bottom Sheet Toggle
    const invHeader = document.querySelector('.arcade-inv-header');
    const invBox = document.querySelector('.arcade-inv-box');
    const bottomActions = document.querySelector('.arcade-bottom-actions');
    if (invHeader && invBox) {
        invHeader.addEventListener('click', () => {
            const isMobileTablet = window.matchMedia('(max-width: 900px), (orientation: portrait)').matches;
            if (isMobileTablet) {
                invBox.classList.toggle('sheet-open');
                const isOpen = invBox.classList.contains('sheet-open');
                if (bottomActions) {
                    bottomActions.style.display = isOpen ? 'none' : 'flex';
                }
                if (isOpen) {
                    // Re-render specifically when opening just in case bounds changed
                    setTimeout(() => { if (!isCapturing) renderPreviewPlaceholders(); }, 350);
                }
            }
        });
    }

    // Re-render preview on resize (responsive layout changes)
    if (window.ResizeObserver) {
        const resizeObs = new ResizeObserver(() => {
            if (!isCapturing) renderPreviewPlaceholders();
        });
        const invBody = document.querySelector('.arcade-inv-body');
        if (invBody) resizeObs.observe(invBody);
    } else {
        window.addEventListener('resize', () => {
            if (!isCapturing) renderPreviewPlaceholders();
        });
    }

    // Start Camera Connection
    let isMirrored = true;    // default: mirrored (selfie mode)
    let currentFacing = 'user'; // 'user' = front, 'environment' = back
    let isFlashOn = false;
    let isLiveRecording = sessionStorage.getItem(SS_LIVE) === '1';

    // Style the LIVE button initially based on restore state
    const btnCamLive = document.getElementById('btn-cam-live');
    if (btnCamLive) {
        btnCamLive.style.background = isLiveRecording ? '#ef4444' : 'rgba(31,41,55,0.85)';
        btnCamLive.style.color = '#fff';
    }

    if (camLiveBadge && isLiveRecording) camLiveBadge.style.display = 'flex';

    // Live frame recording functions
    function startLiveFrameBuffer() {
        liveFrameBuffer = [];
        if (liveFrameInterval) clearInterval(liveFrameInterval);
        
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = 320;
        captureCanvas.height = 240;
        const captureCtx = captureCanvas.getContext('2d');
        
        liveFrameInterval = setInterval(() => {
            const videoEl = document.getElementById('webcam');
            if (!stream || !videoEl || videoEl.paused || videoEl.ended) return;
            
            captureCtx.save();
            if (isMirrored) {
                captureCtx.translate(320, 0);
                captureCtx.scale(-1, 1);
            }
            drawVideoCover(captureCtx, videoEl, 320, 240);
            captureCtx.restore();
            
            const frameData = captureCanvas.toDataURL('image/jpeg', 0.6);
            liveFrameBuffer.push(frameData);
            if (liveFrameBuffer.length > 30) { // 3.0 seconds @ 10fps
                liveFrameBuffer.shift();
            }
        }, 100);
    }

    function stopLiveFrameBuffer() {
        if (liveFrameInterval) {
            clearInterval(liveFrameInterval);
            liveFrameInterval = null;
        }
    }

    async function startCamera(facingMode = currentFacing) {
        playRetroSound('select');
        if (stream) stream.getTracks().forEach(t => t.stop());
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: 640, height: 480, facingMode },
                audio: false
            });
            currentFacing = facingMode;
            video.srcObject = stream;
            video.style.transform = isMirrored ? 'scaleX(-1)' : 'scaleX(1)';
            cameraPlaceholder.style.display = 'none';
            btnStartCamera.style.display = 'none';
            btnTakePhoto.style.display = 'inline-flex';
            const btnUploadPhoto = document.getElementById('btn-upload-photo');
            if (btnUploadPhoto) btnUploadPhoto.style.display = 'inline-flex';
            if (camLiveBadge) camLiveBadge.style.display = isLiveRecording ? 'flex' : 'none';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    btnStartCamera.addEventListener('click', () => startCamera());

    // MIRROR — flip video horizontally
    const btnCamMirror = document.getElementById('btn-cam-mirror');
    const btnMirrorOverlay = document.getElementById('btn-mirror-overlay');

    function applyMirrorState() {
        video.style.transform = isMirrored ? 'scaleX(-1)' : 'scaleX(1)';
        // Header btn
        if (btnCamMirror) {
            btnCamMirror.style.background = isMirrored ? '#facc15' : 'rgba(31,41,55,0.85)';
            btnCamMirror.style.color = isMirrored ? '#1f2937' : '#fff';
        }
        // Overlay btn
        if (btnMirrorOverlay) {
            btnMirrorOverlay.style.background = isMirrored ? 'rgba(250,204,21,0.85)' : 'rgba(0,0,0,0.55)';
            const icon = btnMirrorOverlay.querySelector('i');
            if (icon) icon.style.color = isMirrored ? '#1f2937' : '#fff';
        }
    }

    if (btnCamMirror) btnCamMirror.addEventListener('click', () => {
        isMirrored = !isMirrored;
        applyMirrorState();
        playRetroSound('select');
    });

    if (btnMirrorOverlay) btnMirrorOverlay.addEventListener('click', () => {
        isMirrored = !isMirrored;
        applyMirrorState();
        playRetroSound('select');
    });

    // Sync initial state
    applyMirrorState();

    // FLIP — switch front/back camera
    const btnCamFlip = document.getElementById('btn-cam-flip');
    if (btnCamFlip) btnCamFlip.addEventListener('click', () => {
        if (!stream) return;
        const newFacing = currentFacing === 'user' ? 'environment' : 'user';
        startCamera(newFacing);
    });

    // FLASH — full-white flash overlay on viewport
    const btnCamFlash = document.getElementById('btn-cam-flash');
    if (btnCamFlash) btnCamFlash.addEventListener('click', () => {
        isFlashOn = !isFlashOn;
        btnCamFlash.style.background = isFlashOn ? '#facc15' : 'rgba(31,41,55,0.85)';
        btnCamFlash.style.color = isFlashOn ? '#1f2937' : '#fff';
        playRetroSound('select');
        if (isFlashOn) {
            // Simulate torch: keep a semi-white overlay visible
            shutterFlash.style.opacity = '0.18';
            shutterFlash.style.transition = 'none';
        } else {
            shutterFlash.style.opacity = '0';
        }
    });

    // LIVE — toggle REC badge visibility
    if (btnCamLive) btnCamLive.addEventListener('click', () => {
        isLiveRecording = !isLiveRecording;
        sessionStorage.setItem(SS_LIVE, isLiveRecording ? '1' : '0');
        if (camLiveBadge) camLiveBadge.style.display = isLiveRecording ? 'flex' : 'none';
        btnCamLive.style.background = isLiveRecording ? '#ef4444' : 'rgba(31,41,55,0.85)';
        btnCamLive.style.color = '#fff';
        playRetroSound('select');
    });

    // Start Capture Sequence

    btnTakePhoto.addEventListener('click', async () => {
        if (isCapturing) return;
        isCapturing = true;
        btnTakePhoto.disabled = true;
        layoutSelect.disabled = true;
        if (actionPanel) actionPanel.style.display = 'none';
        
        renderPreviewPlaceholders();
        
        const photoCount = parseInt(layoutSelect.value);
        
        // Show shot counter
        if (camShotCounter) camShotCounter.style.display = 'inline';
        if (shotTotal) shotTotal.textContent = photoCount;
        if (invShotTotal) invShotTotal.textContent = photoCount;
        
        const startStep = capturedImages.length;
        for (let step = startStep; step < photoCount; step++) {
            if (shotCurrent) shotCurrent.textContent = step + 1;
            if (invShotCurrent) invShotCurrent.textContent = step + 1;
            
            countdown.style.display = 'flex';
            countdown.innerText = 'READY?';
            countdown.style.fontSize = '2rem';
            
            if (isLiveRecording) {
                startLiveFrameBuffer();
            }
            
            await new Promise(r => setTimeout(r, 2000)); // 2 second pause
            
            countdown.style.fontSize = '5rem';
            const timerVal = parseInt(document.getElementById('timer-select').value) || 3;
            for (let c = timerVal; c > 0; c--) {
                countdown.innerText = c;
                playRetroSound('beep');
                await new Promise(r => setTimeout(r, 1000));
            }
            
            countdown.style.display = 'none';
            playRetroSound('shutter');
            
            shutterFlash.classList.add('flash-active');
            setTimeout(() => shutterFlash.classList.remove('flash-active'), 200);
            
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = 640;
            tempCanvas.height = 480;
            const tempCtx = tempCanvas.getContext('2d');
            if (isMirrored) {
                tempCtx.translate(640, 0);
                tempCtx.scale(-1, 1);
            }
            drawVideoCover(tempCtx, video, 640, 480);
            
            const dataUrl = tempCanvas.toDataURL('image/jpeg');
            capturedImages.push(dataUrl);
            saveImages();
            
            if (isLiveRecording) {
                stopLiveFrameBuffer();
                capturedLivePhotos[step] = [...liveFrameBuffer];
                saveLivePhotos();
            } else {
                capturedLivePhotos[step] = null;
                saveLivePhotos();
            }
            
            updateSlotVisual(step, dataUrl);
            
            await new Promise(r => setTimeout(r, 1000));
        }
        
        finishSequence();
    });

    function finishSequence() {
        if (capturedImages.length >= parseInt(layoutSelect.value)) {
            isCapturing = false;
            btnTakePhoto.style.display = 'none';
            const btnUploadPhoto = document.getElementById('btn-upload-photo');
            if (btnUploadPhoto) btnUploadPhoto.style.display = 'none';
            if (actionPanel) actionPanel.style.display = 'flex';
            if (camShotCounter) camShotCounter.style.display = 'none';
            // Auto-open inventory so user sees photos
            if (invBox && window.matchMedia('(max-width: 900px), (orientation: portrait)').matches) {
                invBox.classList.add('sheet-open');
                if (bottomActions) bottomActions.style.display = 'none';
                setTimeout(() => { renderPreviewPlaceholders(); }, 350);
            }
        }
    }

    // UPLOAD Photo logic
    const btnUploadPhoto = document.getElementById('btn-upload-photo');
    const uploadFileInput = document.getElementById('upload-file-input');
    
    if (btnUploadPhoto && uploadFileInput) {
        btnUploadPhoto.addEventListener('click', () => {
            if (isCapturing) return;
            uploadFileInput.click();
        });
        
        uploadFileInput.addEventListener('change', async (e) => {
            const files = Array.from(e.target.files);
            if (!files.length) return;
            
            const photoCount = parseInt(layoutSelect.value);
            const remainingSlots = photoCount - capturedImages.length;
            const filesToProcess = files.slice(0, remainingSlots);
            
            for (let i = 0; i < filesToProcess.length; i++) {
                const file = filesToProcess[i];
                const dataUrl = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => resolve(e.target.result);
                    reader.readAsDataURL(file);
                });
                
                // Draw to 640x480 canvas for consistency
                const img = new Image();
                img.src = dataUrl;
                await new Promise(r => img.onload = r);
                
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = 640;
                tempCanvas.height = 480;
                const tempCtx = tempCanvas.getContext('2d');
                
                // Cover behavior
                const ratio = Math.max(640 / img.width, 480 / img.height);
                const drawW = img.width * ratio;
                const drawH = img.height * ratio;
                const drawX = (640 - drawW) / 2;
                const drawY = (480 - drawH) / 2;
                tempCtx.drawImage(img, drawX, drawY, drawW, drawH);
                
                const finalDataUrl = tempCanvas.toDataURL('image/jpeg');
                const step = capturedImages.length;
                capturedImages.push(finalDataUrl);
                saveImages();
                
                capturedLivePhotos[step] = null;
                saveLivePhotos();
                
                if (invShotCurrent) invShotCurrent.textContent = step + 1;
                
                updateSlotVisual(step, finalDataUrl);
            }
            uploadFileInput.value = ''; // Reset
            finishSequence();
        });
    }

    // Retake / Replace individual slot logic
    let replaceSlotIndex = -1;
    const replaceModal = document.getElementById('replace-slot-modal');
    const replaceFileInput = document.getElementById('replace-file-input');
    const replaceCameraBtn = document.getElementById('replace-camera-btn');
    const replaceUploadBtn = document.getElementById('replace-upload-btn');
    const replaceCancelBtn = document.getElementById('replace-cancel-btn');
    const replaceSlotNum = document.getElementById('replace-slot-num');

    function openReplaceModal(idx) {
        replaceSlotIndex = idx;
        if (replaceSlotNum) replaceSlotNum.textContent = idx + 1;
        if (replaceModal) replaceModal.style.display = 'flex';
    }
    function closeReplaceModal() {
        if (replaceModal) replaceModal.style.display = 'none';
        replaceSlotIndex = -1;
    }

    if (replaceCancelBtn) replaceCancelBtn.addEventListener('click', closeReplaceModal);

    if (replaceCameraBtn) {
        replaceCameraBtn.addEventListener('click', async () => {
            const idx = replaceSlotIndex;
            closeReplaceModal();
            if (idx < 0) return;
            
            // Close mobile inventory sheet so user can see the camera!
            if (invBox) invBox.classList.remove('sheet-open');
            if (bottomActions) bottomActions.style.display = 'flex';
            
            // Make sure camera is active before retake
            const videoEl = document.getElementById('webcam');
            if (!stream || !videoEl || !videoEl.srcObject) {
                await startCamera();
                // wait for video to be ready
                await new Promise(r => {
                    if (videoEl.readyState >= 2) return r();
                    videoEl.onloadeddata = r;
                    setTimeout(r, 3000); // fallback
                });
            }
            
            // Countdown & capture
            const countdown = document.getElementById('countdown');
            const shutterFlash = document.getElementById('shutter-flash');
            countdown.style.display = 'flex';
            countdown.innerText = 'READY?';
            countdown.style.fontSize = '2rem';
            
            if (isLiveRecording) {
                startLiveFrameBuffer();
            }
            
            await new Promise(r => setTimeout(r, 1500));
            
            countdown.style.fontSize = '5rem';
            const timerVal = parseInt(document.getElementById('timer-select').value) || 3;
            for (let c = timerVal; c > 0; c--) {
                countdown.innerText = c;
                playRetroSound('beep');
                await new Promise(r => setTimeout(r, 1000));
            }
            countdown.style.display = 'none';
            playRetroSound('shutter');
            shutterFlash.classList.add('flash-active');
            setTimeout(() => shutterFlash.classList.remove('flash-active'), 200);
 
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = 640; tempCanvas.height = 480;
            const tempCtx = tempCanvas.getContext('2d');
            if (isMirrored) { tempCtx.translate(640, 0); tempCtx.scale(-1, 1); }
            drawVideoCover(tempCtx, videoEl, 640, 480);
            const dataUrl = tempCanvas.toDataURL('image/jpeg');
            capturedImages[idx] = dataUrl;
            // Invalidate image cache so next render picks up new photo
            imgCache.photos[idx] = null;
            saveImages();
            
            if (isLiveRecording) {
                stopLiveFrameBuffer();
                capturedLivePhotos[idx] = [...liveFrameBuffer];
                if (imgCache.livePhotos) imgCache.livePhotos[idx] = null;
                saveLivePhotos();
            } else {
                capturedLivePhotos[idx] = null;
                if (imgCache.livePhotos) imgCache.livePhotos[idx] = null;
                saveLivePhotos();
            }
 
            updateSlotVisual(idx, dataUrl);
            
            // Auto-open inventory back
            if (invBox && window.matchMedia('(max-width: 900px), (orientation: portrait)').matches) {
                setTimeout(() => {
                    invBox.classList.add('sheet-open');
                    if (bottomActions) bottomActions.style.display = 'none';
                }, 500);
            }
        });
    }

    if (replaceUploadBtn) {
        replaceUploadBtn.addEventListener('click', () => {
            // Do not reset index yet, file input needs it
            if (replaceModal) replaceModal.style.display = 'none';
            if (replaceFileInput) replaceFileInput.click();
        });
    }

    if (replaceFileInput) {
        replaceFileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file || replaceSlotIndex < 0) return;
            const idx = replaceSlotIndex;
            const dataUrl = await new Promise(resolve => {
                const reader = new FileReader();
                reader.onload = ev => resolve(ev.target.result);
                reader.readAsDataURL(file);
            });
            const img = new Image();
            img.src = dataUrl;
            await new Promise(r => img.onload = r);
            const tc = document.createElement('canvas');
            tc.width = 640; tc.height = 480;
            const tx = tc.getContext('2d');
            const ratio = Math.max(640 / img.width, 480 / img.height);
            const dw = img.width * ratio, dh = img.height * ratio;
            tx.drawImage(img, (640 - dw) / 2, (480 - dh) / 2, dw, dh);
            const finalUrl = tc.toDataURL('image/jpeg');
            capturedImages[idx] = finalUrl;
            imgCache.photos[idx] = null;
            capturedLivePhotos[idx] = null;
            if (imgCache.livePhotos) imgCache.livePhotos[idx] = null;
            saveImages();
            saveLivePhotos();
            updateSlotVisual(idx, finalUrl);
            replaceFileInput.value = '';
            replaceSlotIndex = -1;
        });
    }

    // LANJUTKAN button - go to step 3 (Preview & Edit)
    const previewEditLayout = document.getElementById('preview-edit-layout');
    let currentFinalFilter = 'none';
    
    if (btnNext) {
        btnNext.addEventListener('click', () => {
            playRetroSound('select');
            sessionStorage.setItem(SS_STEP, 'preview');
            playgroundLayout.style.display = 'none';
            previewEditLayout.style.display = 'flex';
            closeReplaceModal();
            if (window.lucide) window.lucide.createIcons();
            
            const tabLive = document.getElementById('tab-live');
            if (tabLive) {
                tabLive.style.display = sessionStorage.getItem(SS_LIVE) === '1' ? 'inline-block' : 'none';
            }
            
            // Build the canvas
            buildFinalCanvas();

            // Generate GIF
            generateAutoGif();
        });
    }

    // BACK TO CAMERA logic is now handled by the top-left main back button

    // Filter Buttons logic
    document.querySelectorAll('.final-filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            playRetroSound('select');
            // Remove active class from all buttons
            document.querySelectorAll('.final-filter-btn').forEach(b => b.classList.remove('active-filter'));
            // Add active class to clicked one
            e.currentTarget.classList.add('active-filter');
            
            currentFinalFilter = e.currentTarget.getAttribute('data-filter');
            const finalCanvas = document.getElementById('final-strip-canvas');
            const finalRaw = document.getElementById('final-raw-container');
            const finalMedia = document.getElementById('final-media-placeholder');
            
            if (finalCanvas) {
                finalCanvas.style.filter = '';
                buildFinalCanvas();
            }
            if (finalRaw) {
                finalRaw.querySelectorAll('img').forEach(img => {
                    img.style.filter = currentFinalFilter === 'none' ? '' : currentFinalFilter;
                });
            }
            if (finalMedia) {
                const mediaImg = finalMedia.querySelector('img');
                if (mediaImg) mediaImg.style.filter = currentFinalFilter === 'none' ? '' : currentFinalFilter;
            }
        });
    });

    // Preview Tabs logic (Strip, Raw, GIF, Live)
    let currentFormat = 'strip';
    document.querySelectorAll('.preview-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            playRetroSound('select');
            document.querySelectorAll('.preview-tab-btn').forEach(b => b.classList.remove('active'));
            e.currentTarget.classList.add('active');
            
            currentFormat = e.currentTarget.getAttribute('data-format');
            
            const finalCanvas = document.getElementById('final-strip-canvas');
            const finalRawWrapper = document.getElementById('raw-preview-wrapper');
            const mediaPlaceholder = document.getElementById('final-media-placeholder');
            const livePhotoBadge = document.getElementById('live-photo-badge');
            
            const canvasWrapper = document.getElementById('canvas-zoom-wrapper');
            const tapHint = document.getElementById('canvas-tap-hint');
            if (canvasWrapper) canvasWrapper.style.display = 'none';
            if (finalRawWrapper) finalRawWrapper.style.display = 'none';
            if (mediaPlaceholder) mediaPlaceholder.style.display = 'none';
            if (tapHint) tapHint.style.display = 'none';
            if (livePhotoBadge) livePhotoBadge.style.display = 'none';
            
            // Stop any playing animation
            stopLivePhotoAnimation();
            
            if (currentFormat === 'strip' || currentFormat === 'live') {
                if (canvasWrapper) canvasWrapper.style.display = 'flex';
                if (tapHint) {
                    tapHint.style.display = 'block';
                    if (currentFormat === 'strip') {
                        tapHint.textContent = '✦ GESER & CUBIT FOTO UNTUK ADJUST';
                    } else {
                        tapHint.textContent = '✦ TEKAN & TAHAN UNTUK PUTAR LIVE PHOTO';
                        if (livePhotoBadge) livePhotoBadge.style.display = 'flex';
                        // Auto-play one preview bounce after canvas is ready
                        setTimeout(() => playLivePhotoAnimation(true), 400);
                    }
                }
            } else if (currentFormat === 'raw') {
                if (finalRawWrapper) {
                    finalRawWrapper.style.display = 'flex';
                    renderRawPreview();
                }
            } else if (currentFormat === 'gif') {
                if (mediaPlaceholder) {
                    mediaPlaceholder.style.display = 'flex';
                    renderGifPreview();
                }
            }
        });
    });
    
    function generateAutoGif() {
        if (typeof gifshot === 'undefined' || capturedImages.length === 0) return;
        
        const placeholder = document.getElementById('final-media-placeholder');
        if (placeholder && currentFormat === 'gif') {
            placeholder.innerHTML = '<i data-lucide="loader-2" class="animate-spin" style="width:48px;height:48px; color:#1f2937; animation: spin 1s linear infinite;"></i><p style="color:#1f2937; font-family:\'Press Start 2P\',monospace; font-size:0.6rem; text-align:center; line-height:1.5; margin-top:16px;">GENERATING GIF...</p>';
            if (window.lucide) window.lucide.createIcons();
        }

        gifshot.createGIF({
            images: capturedImages,
            gifWidth: 640,
            gifHeight: 480,
            interval: 0.5,
            numFrames: capturedImages.length,
            sampleInterval: 10
        }, function(obj) {
            if (!obj.error) {
                sessionStorage.setItem(SS_GIF, obj.image);
                if (currentFormat === 'gif') {
                    renderGifPreview();
                }
            }
        });
    }

    function renderGifPreview() {
        const gifUrl = sessionStorage.getItem(SS_GIF);
        const placeholder = document.getElementById('final-media-placeholder');
        if (!placeholder) return;
        
        if (gifUrl) {
            placeholder.innerHTML = `<img src="${gifUrl}" style="max-width:90%; max-height:90%; object-fit:contain; border:4px solid #1f2937; border-radius:8px; box-shadow:6px 6px 0 rgba(0,0,0,0.5); filter:${currentFinalFilter === 'none' ? 'none' : currentFinalFilter};">`;
        } else {
            placeholder.innerHTML = '<i data-lucide="loader-2" style="width:48px;height:48px; color:#1f2937; animation: spin 1s linear infinite;"></i><p style="color:#1f2937; font-family:\'Press Start 2P\',monospace; font-size:0.6rem; text-align:center; line-height:1.5; margin-top:16px;">GENERATING GIF...</p>';
            if (window.lucide) window.lucide.createIcons();
            
            // Trigger generation if not already present
            generateAutoGif();
        }
    }
    
    function renderRawPreview() {
        const container = document.getElementById('final-raw-container');
        if (!container) return;
        container.innerHTML = '';
        
        capturedImages.forEach((src, idx) => {
            const wrapper = document.createElement('div');
            wrapper.style.flex = '0 0 100%';
            wrapper.style.height = '100%';
            wrapper.style.display = 'flex';
            wrapper.style.flexDirection = 'column';
            wrapper.style.alignItems = 'center';
            wrapper.style.justifyContent = 'center';
            wrapper.style.scrollSnapAlign = 'center';
            wrapper.style.position = 'relative';

            const img = document.createElement('img');
            img.src = src;
            img.style.maxWidth = '90%';
            img.style.maxHeight = '90%';
            img.style.objectFit = 'contain';
            img.style.border = '4px solid #1f2937';
            img.style.borderRadius = '8px';
            img.style.boxShadow = '6px 6px 0 rgba(0,0,0,0.5)';
            if (typeof currentFinalFilter !== 'undefined' && currentFinalFilter !== 'none') {
                img.style.filter = currentFinalFilter;
            }
            
            const badge = document.createElement('div');
            badge.textContent = `${idx + 1} / ${capturedImages.length}`;
            badge.style.position = 'absolute';
            badge.style.bottom = '0px';
            badge.style.background = '#facc15';
            badge.style.color = '#1f2937';
            badge.style.padding = '8px 16px';
            badge.style.borderRadius = '20px';
            badge.style.border = '2px solid #1f2937';
            badge.style.fontFamily = '"Press Start 2P", monospace';
            badge.style.fontSize = '0.55rem';
            badge.style.boxShadow = '2px 2px 0 rgba(0,0,0,0.5)';

            wrapper.appendChild(img);
            wrapper.appendChild(badge);
            container.appendChild(wrapper);
        });
    }

    // ---- Per-Slot Photo Adjustment (Direct Canvas Interaction) ----
    // Stores adjustments per captured image index: { scale, ox, oy }
    const photoAdjustments = {};
    // imgCache is declared at the top of this DOMContentLoaded block
    
    async function preloadImagesForCanvas() {
        const imgUrl = sessionStorage.getItem(SS_IMG) || '';
        if (imgUrl && (!imgCache.bg || imgCache.bg.src !== imgUrl)) {
            imgCache.bg = new Image();
            imgCache.bg.src = imgUrl;
            await new Promise(r => { imgCache.bg.onload = r; imgCache.bg.onerror = r; });
        }
        for (let i = 0; i < capturedImages.length; i++) {
            if (capturedImages[i] && (!imgCache.photos[i] || imgCache.photos[i].src !== capturedImages[i])) {
                const img = new Image();
                img.src = capturedImages[i];
                await new Promise(r => { img.onload = r; img.onerror = r; });
                imgCache.photos[i] = img;
            }
        }
        // Preload live photos if present
        for (let i = 0; i < capturedImages.length; i++) {
            if (capturedLivePhotos[i] && Array.isArray(capturedLivePhotos[i])) {
                if (!imgCache.livePhotos[i]) imgCache.livePhotos[i] = [];
                for (let j = 0; j < capturedLivePhotos[i].length; j++) {
                    const src = capturedLivePhotos[i][j];
                    if (src && (!imgCache.livePhotos[i][j] || imgCache.livePhotos[i][j].src !== src)) {
                        const img = new Image();
                        img.src = src;
                        await new Promise(r => { img.onload = r; img.onerror = r; });
                        imgCache.livePhotos[i][j] = img;
                    }
                }
            }
        }
    }

    let liveRafId = null;
    let liveIsHolding = false;

    function playLivePhotoAnimation(autoPlay = false) {
        if (liveRafId) cancelAnimationFrame(liveRafId);
        if (livePlayTimer) { clearInterval(livePlayTimer); livePlayTimer = null; }

        let maxLen = 0;
        for (let i = 0; i < capturedImages.length; i++) {
            if (imgCache.livePhotos[i] && imgCache.livePhotos[i].length > maxLen) {
                maxLen = imgCache.livePhotos[i].length;
            }
        }
        if (maxLen === 0) return;

        const badge = document.getElementById('live-photo-badge');
        if (badge) badge.classList.add('active');

        // iPhone-style: play forward then reverse back (bounce/spring)
        // frameDuration = time per frame in ms (60fps target)
        const frameDurationMs = 80; // ~12fps feels natural like iPhone
        let startTime = null;
        let phase = 'forward'; // 'forward' | 'reverse'
        let looping = !autoPlay; // hold = loop, auto = one shot

        function tick(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            const rawIndex = elapsed / frameDurationMs;

            if (phase === 'forward') {
                liveAnimationFrameIndex = Math.min(Math.floor(rawIndex), maxLen - 1);
                fastRebuildFinalCanvas();

                if (rawIndex >= maxLen) {
                    // Reached end — now reverse
                    phase = 'reverse';
                    startTime = timestamp;
                    liveRafId = requestAnimationFrame(tick);
                } else {
                    liveRafId = requestAnimationFrame(tick);
                }
            } else {
                // Reverse: play backwards at 1.5x speed (snappier)
                const revIndex = maxLen - 1 - Math.floor(rawIndex * 1.5);
                liveAnimationFrameIndex = Math.max(0, revIndex);
                fastRebuildFinalCanvas();

                if (revIndex <= 0) {
                    // Finished one full bounce cycle
                    if (looping && liveIsHolding) {
                        // Loop again if still holding
                        phase = 'forward';
                        startTime = timestamp;
                        liveRafId = requestAnimationFrame(tick);
                    } else {
                        // Auto-play done or released — settle on still
                        liveAnimationFrameIndex = -1;
                        fastRebuildFinalCanvas();
                        if (badge) badge.classList.remove('active');
                        liveRafId = null;
                    }
                } else {
                    liveRafId = requestAnimationFrame(tick);
                }
            }
        }

        liveRafId = requestAnimationFrame(tick);
    }

    function stopLivePhotoAnimation() {
        // Mark as not holding — the rAF loop will cleanly finish the reverse phase
        liveIsHolding = false;
        // Also clear any old setInterval just in case
        if (livePlayTimer) { clearInterval(livePlayTimer); livePlayTimer = null; }
        // If nothing was playing, reset immediately
        if (!liveRafId) {
            liveAnimationFrameIndex = -1;
            const badge = document.getElementById('live-photo-badge');
            if (badge) badge.classList.remove('active');
            fastRebuildFinalCanvas();
        }
    }

    // Direct Interaction Handler
    (function() {
        const canvas = document.getElementById('final-strip-canvas');
        if (!canvas) return;

        let activeSlotIndex = -1;
        let isDragging = false;
        let lastX = 0, lastY = 0;
        let lastDist = null;

        function getCanvasCoords(clientX, clientY) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY,
                scaleX, scaleY
            };
        }

        function findSlot(x, y) {
            const frames = JSON.parse(sessionStorage.getItem(SS_COORDS) || '[]');
            for (let i = 0; i < frames.length; i++) {
                const f = frames[i];
                if (x >= f.x && x <= f.x + f.width && y >= f.y && y <= f.y + f.height) {
                    return i;
                }
            }
            return -1;
        }

        // Pointer Events for Dragging / Live Photo Playback
        canvas.addEventListener('pointerdown', (e) => {
            if (currentFormat === 'live') {
                e.preventDefault();
                liveIsHolding = true;
                playLivePhotoAnimation();
                return;
            }
            if (e.pointerType === 'touch' && !e.isPrimary) return; // Handle multi-touch separately
            const coords = getCanvasCoords(e.clientX, e.clientY);
            activeSlotIndex = findSlot(coords.x, coords.y);
            if (activeSlotIndex !== -1) {
                isDragging = true;
                lastX = e.clientX;
                lastY = e.clientY;
                if (!photoAdjustments[activeSlotIndex]) photoAdjustments[activeSlotIndex] = { scale: 1, ox: 0, oy: 0 };
                canvas.setPointerCapture(e.pointerId);
                canvas.style.cursor = 'grabbing';
            }
        });

        canvas.addEventListener('pointermove', (e) => {
            if (currentFormat === 'live') return;
            if (!isDragging || activeSlotIndex === -1) return;
            // Map pointer movement to canvas pixels
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            
            const dx = (e.clientX - lastX) * scaleX;
            const dy = (e.clientY - lastY) * scaleY;
            
            photoAdjustments[activeSlotIndex].ox += dx;
            photoAdjustments[activeSlotIndex].oy += dy;
            
            lastX = e.clientX;
            lastY = e.clientY;
            
            fastRebuildFinalCanvas();
        });

        canvas.addEventListener('pointerup', (e) => {
            if (currentFormat === 'live') {
                stopLivePhotoAnimation();
                return;
            }
            isDragging = false;
            activeSlotIndex = -1;
            canvas.releasePointerCapture(e.pointerId);
            canvas.style.cursor = 'pointer';
        });
        
        canvas.addEventListener('pointercancel', (e) => {
            if (currentFormat === 'live') {
                stopLivePhotoAnimation();
                return;
            }
            isDragging = false;
            activeSlotIndex = -1;
            canvas.releasePointerCapture(e.pointerId);
            canvas.style.cursor = 'pointer';
        });

        canvas.addEventListener('pointerleave', (e) => {
            if (currentFormat === 'live') {
                stopLivePhotoAnimation();
            }
        });

        // Wheel for Zoom
        canvas.addEventListener('wheel', (e) => {
            if (currentFormat === 'live') return;
            e.preventDefault();
            const coords = getCanvasCoords(e.clientX, e.clientY);
            const slot = findSlot(coords.x, coords.y);
            if (slot !== -1) {
                if (!photoAdjustments[slot]) photoAdjustments[slot] = { scale: 1, ox: 0, oy: 0 };
                const adj = photoAdjustments[slot];
                const delta = e.deltaY < 0 ? 0.1 : -0.1;
                adj.scale = Math.max(1, Math.min(5, adj.scale + delta));
                fastRebuildFinalCanvas();
            }
        }, { passive: false });

        // Touch Events for Pinch
        canvas.addEventListener('touchstart', (e) => {
            if (currentFormat === 'live') {
                e.preventDefault();
                liveIsHolding = true;
                playLivePhotoAnimation();
                return;
            }
            if (e.touches.length === 2) {
                isDragging = false; // Disable single finger drag
                const coords = getCanvasCoords(e.touches[0].clientX, e.touches[0].clientY);
                const slot = findSlot(coords.x, coords.y);
                if (slot !== -1) {
                    activeSlotIndex = slot;
                    lastDist = Math.hypot(
                        e.touches[0].clientX - e.touches[1].clientX,
                        e.touches[0].clientY - e.touches[1].clientY
                    );
                    if (!photoAdjustments[activeSlotIndex]) photoAdjustments[activeSlotIndex] = { scale: 1, ox: 0, oy: 0 };
                }
            }
        }, { passive: true });

        canvas.addEventListener('touchmove', (e) => {
            if (currentFormat === 'live') return;
            if (e.touches.length === 2 && activeSlotIndex !== -1) {
                e.preventDefault();
                const dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                if (lastDist) {
                    const factor = dist / lastDist;
                    const adj = photoAdjustments[activeSlotIndex];
                    adj.scale = Math.max(1, Math.min(5, adj.scale * factor));
                    fastRebuildFinalCanvas();
                }
                lastDist = dist;
            }
        }, { passive: false });

        canvas.addEventListener('touchend', (e) => {
            if (currentFormat === 'live') {
                stopLivePhotoAnimation();
                return;
            }
            if (e.touches.length < 2) {
                lastDist = null;
            }
        });

        // Double Click/Tap to Reset
        let lastTap = 0;
        canvas.addEventListener('touchend', (e) => {
            if (currentFormat === 'live') return;
            if(e.changedTouches.length === 1){
                const now = Date.now();
                if(now - lastTap < 300) {
                     const coords = getCanvasCoords(e.changedTouches[0].clientX, e.changedTouches[0].clientY);
                     const slot = findSlot(coords.x, coords.y);
                     if (slot !== -1) {
                          photoAdjustments[slot] = { scale: 1, ox: 0, oy: 0 };
                          fastRebuildFinalCanvas();
                     }
                }
                lastTap = now;
            }
        });
        canvas.addEventListener('dblclick', (e) => {
            if (currentFormat === 'live') return;
            const coords = getCanvasCoords(e.clientX, e.clientY);
            const slot = findSlot(coords.x, coords.y);
            if (slot !== -1) {
                photoAdjustments[slot] = { scale: 1, ox: 0, oy: 0 };
                fastRebuildFinalCanvas();
            }
        });

    })();

    // RAW Preview Arrow Logic
    const rawPrevBtn = document.getElementById('raw-prev-btn');
    const rawNextBtn = document.getElementById('raw-next-btn');
    if (rawPrevBtn) rawPrevBtn.addEventListener('click', () => {
        playRetroSound('select');
        const container = document.getElementById('final-raw-container');
        if (container) container.scrollBy({ left: -(container.clientWidth * 0.9), behavior: 'smooth' });
    });
    if (rawNextBtn) rawNextBtn.addEventListener('click', () => {
        playRetroSound('select');
        const container = document.getElementById('final-raw-container');
        if (container) container.scrollBy({ left: container.clientWidth * 0.9, behavior: 'smooth' });
    });

    // Generate Final Canvas based on Template settings
    async function buildFinalCanvas() {
        await preloadImagesForCanvas();
        fastRebuildFinalCanvas();
    }
    
    function fastRebuildFinalCanvas() {
        const finalCanvas = document.getElementById('final-strip-canvas');
        if (!finalCanvas) return;
        const ctx = finalCanvas.getContext('2d');
        
        const tplW = parseFloat(sessionStorage.getItem(SS_W) || 200);
        const tplH = parseFloat(sessionStorage.getItem(SS_H) || 600);
        const coordsStr = sessionStorage.getItem(SS_COORDS) || '[]';
        const frames = JSON.parse(coordsStr);
        
        // Use offscreen canvas to prevent flicker
        const offscreen = document.createElement('canvas');
        offscreen.width = tplW;
        offscreen.height = tplH;
        const oCtx = offscreen.getContext('2d');
        
        // Background color
        oCtx.fillStyle = '#ffffff';
        oCtx.fillRect(0, 0, tplW, tplH);
        
        // Separate frames by layer
        const backFrames = frames.filter(f => (f.layer || 'back') === 'back');
        const frontFrames = frames.filter(f => (f.layer || 'back') === 'front');
        
        // 1. Draw back frames (photos)
        fastDrawFrames(oCtx, backFrames, frames);
        
        // 2. Draw template image
        if (imgCache.bg) {
            oCtx.drawImage(imgCache.bg, 0, 0, tplW, tplH);
        }
        
        // 3. Draw front frames (photos)
        fastDrawFrames(oCtx, frontFrames, frames);
        
        // Swap to visible canvas synchronously
        finalCanvas.width = tplW;
        finalCanvas.height = tplH;
        ctx.drawImage(offscreen, 0, 0);
    }
    
    function fastDrawFrames(ctx, frameList, allFrames) {
        for (let i = 0; i < frameList.length; i++) {
            const f = frameList[i];
            const originalIndex = allFrames.findIndex(orig => orig.id === f.id || orig.x === f.x && orig.y === f.y);
            
            if (originalIndex >= 0) {
                let img = imgCache.photos[originalIndex];
                
                // If live photo is active and playing
                if (liveAnimationFrameIndex >= 0 && imgCache.livePhotos[originalIndex] && imgCache.livePhotos[originalIndex].length > 0) {
                    const framesCount = imgCache.livePhotos[originalIndex].length;
                    const frameIdx = Math.min(liveAnimationFrameIndex, framesCount - 1);
                    if (imgCache.livePhotos[originalIndex][frameIdx]) {
                        img = imgCache.livePhotos[originalIndex][frameIdx];
                    }
                }
                
                if (img) {
                
                // Crop & center (object-fit cover equivalent) + user adjustments
                const targetW = f.width;
                const targetH = f.height;
                const adj = photoAdjustments[originalIndex] || { scale: 1, ox: 0, oy: 0 };
                
                // Base cover scale, then multiply by user's zoom (clamped to min 1)
                adj.scale = Math.max(1, adj.scale);
                const baseRatio = Math.max(targetW / img.width, targetH / img.height);
                const ratio = baseRatio * adj.scale;
                const drawW = img.width  * ratio;
                const drawH = img.height * ratio;
                
                // Calculate raw draw position
                const centerX = f.x + (targetW - drawW) / 2;
                const centerY = f.y + (targetH - drawH) / 2;
                let drawX = centerX + adj.ox;
                let drawY = centerY + adj.oy;
                
                // Clamp draw position so background never shows
                const minX = f.x + targetW - drawW;
                const maxX = f.x;
                const minY = f.y + targetH - drawH;
                const maxY = f.y;
                drawX = Math.max(minX, Math.min(maxX, drawX));
                drawY = Math.max(minY, Math.min(maxY, drawY));
                
                // Write back clamped values so dragging doesn't "slip"
                adj.ox = drawX - centerX;
                adj.oy = drawY - centerY;
                
                // Use clipping path for border radius or shapes if needed
                ctx.save();
                if (typeof currentFinalFilter !== 'undefined' && currentFinalFilter !== 'none') {
                    ctx.filter = currentFinalFilter;
                }
                ctx.beginPath();
                if (f.shape === 'circle') {
                    ctx.arc(f.x + targetW/2, f.y + targetH/2, Math.min(targetW, targetH)/2, 0, Math.PI * 2);
                } else if (f.shape === 'rounded-lg') {
                    ctx.roundRect(f.x, f.y, targetW, targetH, 24);
                } else if (f.shape === 'rounded-sm') {
                    ctx.roundRect(f.x, f.y, targetW, targetH, 8);
                } else {
                    ctx.rect(f.x, f.y, targetW, targetH);
                }
                ctx.clip();
                
                ctx.drawImage(img, drawX, drawY, drawW, drawH);
                ctx.restore();
                }
            }
        }
    }
    async function getFilteredImages() {
        if (currentFinalFilter === 'none') return capturedImages;
        const filtered = [];
        for (let i = 0; i < capturedImages.length; i++) {
            const img = new Image();
            img.src = capturedImages[i];
            await new Promise(r => { img.onload = r; img.onerror = r; });
            const cvs = document.createElement('canvas');
            cvs.width = 640; cvs.height = 480;
            const ctx = cvs.getContext('2d');
            ctx.filter = currentFinalFilter;
            ctx.drawImage(img, 0, 0, 640, 480);
            filtered.push(cvs.toDataURL('image/jpeg', 0.9));
        }
        return filtered;
    }

    // Download Logic refactored for Modal
    function downloadLivePhoto() {
        const finalCanvas = document.getElementById('final-strip-canvas');
        if (!finalCanvas) return;
        
        if (window.Swal) Swal.fire({ title: 'MENYIMPAN...', text: 'Membuat animasi Live Photo...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        
        stopLivePhotoAnimation();
        const prevFrameIdx = liveAnimationFrameIndex;
        
        let maxLen = 0;
        for (let i = 0; i < capturedImages.length; i++) {
            if (imgCache.livePhotos[i] && imgCache.livePhotos[i].length > maxLen) {
                maxLen = imgCache.livePhotos[i].length;
            }
        }
        if (maxLen === 0) maxLen = 1;
        
        const animatedFrames = [];
        for (let f = 0; f < maxLen; f++) {
            liveAnimationFrameIndex = f;
            fastRebuildFinalCanvas();
            animatedFrames.push(finalCanvas.toDataURL('image/png'));
        }
        // Reverse phase to create boomerang effect
        for (let f = maxLen - 2; f > 0; f--) {
            liveAnimationFrameIndex = f;
            fastRebuildFinalCanvas();
            animatedFrames.push(finalCanvas.toDataURL('image/png'));
        }
        
        liveAnimationFrameIndex = prevFrameIdx;
        fastRebuildFinalCanvas();
        
        gifshot.createGIF({
            images: animatedFrames,
            gifWidth: finalCanvas.width,
            gifHeight: finalCanvas.height,
            interval: 0.08,
            numFrames: animatedFrames.length,
            sampleInterval: 10
        }, function(obj) {
            if (window.Swal) Swal.close();
            if (!obj.error) {
                const link = document.createElement('a');
                link.download = `mobooth-live-${Date.now()}.gif`;
                link.href = obj.image;
                link.click();
            } else {
                alert('Gagal membuat Live Photo GIF: ' + obj.error);
            }
        });
    }

    function downloadGif() {
        if (currentFinalFilter !== 'none') {
            if (window.Swal) Swal.fire({ title: 'MENYIMPAN...', text: 'Menerapkan filter pada animasi GIF...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            getFilteredImages().then(filteredImages => {
                gifshot.createGIF({
                    images: filteredImages, gifWidth: 640, gifHeight: 480, interval: 0.5, numFrames: filteredImages.length, sampleInterval: 10
                }, function(obj) {
                    if (window.Swal) Swal.close();
                    if (!obj.error) {
                        const link = document.createElement('a'); link.download = `mobooth-gif-${Date.now()}.gif`; link.href = obj.image; link.click();
                    }
                });
            });
        } else {
            const gifUrl = sessionStorage.getItem(SS_GIF);
            if (gifUrl) {
                const link = document.createElement('a'); link.download = `mobooth-gif-${Date.now()}.gif`; link.href = gifUrl; link.click();
            } else {
                alert('Mobooth GIF tidak tersedia.');
            }
        }
    }

    function downloadRawPhotos() {
        if (currentFinalFilter !== 'none') {
            if (window.Swal) Swal.fire({ title: 'MENYIMPAN...', text: 'Menerapkan filter pada foto RAW...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            getFilteredImages().then(filteredImages => {
                if (window.Swal) Swal.close();
                filteredImages.forEach((img, idx) => {
                    const link = document.createElement('a'); link.download = `mobooth-raw-${Date.now()}-${idx+1}.jpg`; link.href = img; link.click();
                });
            });
        } else {
            capturedImages.forEach((img, idx) => {
                const link = document.createElement('a'); link.download = `mobooth-raw-${Date.now()}-${idx+1}.jpg`; link.href = img; link.click();
            });
        }
    }

    function downloadStrip() {
        const finalCanvas = document.getElementById('final-strip-canvas');
        if (!finalCanvas) return;
        
        // Ensure we draw the static strip, not live animation frame
        const prevFrameIdx = liveAnimationFrameIndex;
        liveAnimationFrameIndex = -1;
        fastRebuildFinalCanvas();

        const link = document.createElement('a');
        link.download = `mobooth-strip-${Date.now()}.png`;
        link.href = finalCanvas.toDataURL('image/png');
        link.click();

        liveAnimationFrameIndex = prevFrameIdx;
        fastRebuildFinalCanvas();
    }

    // Modal and Button Event Listeners
    const btnFinalDownload = document.getElementById('btn-final-download');
    if (btnFinalDownload) {
        btnFinalDownload.addEventListener('click', () => {
            playRetroSound('select');
            const modal = document.getElementById('download-modal-overlay');
            if (modal) modal.style.display = 'flex';
        });
    }

    const btnEndGame = document.getElementById('btn-end-game');
    if (btnEndGame) {
        btnEndGame.addEventListener('click', () => {
            playRetroSound('select');
            clearTemplateSession();
            window.location.href = (window.BASEURL || '') + '/';
        });
    }

    const btnCloseDlModal = document.getElementById('btn-close-download-modal');
    if (btnCloseDlModal) {
        btnCloseDlModal.addEventListener('click', () => {
            document.getElementById('download-modal-overlay').style.display = 'none';
        });
    }

    const btnDlStrip = document.getElementById('btn-dl-strip');
    if (btnDlStrip) btnDlStrip.addEventListener('click', () => { playRetroSound('select'); downloadStrip(); });

    const btnDlRaw = document.getElementById('btn-dl-raw');
    if (btnDlRaw) btnDlRaw.addEventListener('click', () => { playRetroSound('select'); downloadRawPhotos(); });

    const btnDlGif = document.getElementById('btn-dl-gif');
    if (btnDlGif) btnDlGif.addEventListener('click', () => { playRetroSound('select'); downloadGif(); });

    const btnDlLive = document.getElementById('btn-dl-live');
    if (btnDlLive) btnDlLive.addEventListener('click', () => { 
        if (sessionStorage.getItem(SS_LIVE) !== '1') {
            alert('Live Photo tidak aktif selama sesi foto.');
            return;
        }
        playRetroSound('select'); 
        downloadLivePhoto(); 
    });

    // ---- Restore session on load ----
    const savedStep = sessionStorage.getItem(SS_STEP);
    if (savedStep === 'camera' || savedStep === 'preview') {
        const slots = sessionStorage.getItem(SS_SLOTS) || '3';
        layoutSelect.value = slots;
        if (shotTotal) shotTotal.textContent = slots;

        // Restore captured images from session
        try {
            const savedImgs = JSON.parse(sessionStorage.getItem(SS_IMAGES) || '[]');
            capturedImages = savedImgs;
        } catch(e) { capturedImages = []; }

        try {
            const savedLives = JSON.parse(sessionStorage.getItem(SS_LIVE_PHOTOS) || '[]');
            capturedLivePhotos = savedLives;
        } catch(e) { capturedLivePhotos = []; }

        if (savedStep === 'preview' && capturedImages.length > 0) {
            // Restore to preview/edit step
            templateSelectionLayout.style.display = 'none';
            playgroundLayout.style.display = 'none';
            previewEditLayout.style.display = 'flex';
            closeReplaceModal();
            if (window.lucide) window.lucide.createIcons();
            
            const tabLive = document.getElementById('tab-live');
            if (tabLive) {
                tabLive.style.display = sessionStorage.getItem(SS_LIVE) === '1' ? 'inline-block' : 'none';
            }

            buildFinalCanvas();
            if (!sessionStorage.getItem(SS_GIF)) {
                generateAutoGif();
            } else {
                if (currentFormat === 'gif') renderGifPreview();
            }
        } else {
            // Camera step (or preview with no images — fallback to camera)
            sessionStorage.setItem(SS_STEP, 'camera');
            templateSelectionLayout.style.display = 'none';
            playgroundLayout.style.display = 'flex';
            renderPreviewPlaceholders();
            if (btnStartCamera) setTimeout(() => btnStartCamera.click(), 100);
        }
    }

    // Intercept menu link click — handle Preview "Back" or clear session
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            playRetroSound('select');
            
            // If we are currently in Preview step, clicking the main Back button
            // should just take us back to the Camera step instead of quitting.
            if (sessionStorage.getItem(SS_STEP) === 'preview') {
                sessionStorage.setItem(SS_STEP, 'camera');
                previewEditLayout.style.display = 'none';
                playgroundLayout.style.display = 'flex';
                return; // Stop here, do not exit
            }

            // If we are in the Camera step, go back to Template Selection
            if (sessionStorage.getItem(SS_STEP) === 'camera') {
                sessionStorage.setItem(SS_STEP, '');
                clearTemplateSession();
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                const video = document.getElementById('webcam');
                if (video) video.srcObject = null;
                
                playgroundLayout.style.display = 'none';
                templateSelectionLayout.style.display = 'flex';
                
                // reset template selection visually
                const btnConfirm = document.getElementById('btn-confirm-template');
                if (btnConfirm) {
                    btnConfirm.disabled = true;
                    btnConfirm.style.opacity = '0.5';
                    btnConfirm.style.filter = 'grayscale(1)';
                    btnConfirm.style.cursor = 'not-allowed';
                }
                document.querySelectorAll('.template-card.active').forEach(c => c.classList.remove('active'));
                
                const cvsPaper = document.getElementById('template-canvas-paper');
                const prevEmpty = document.getElementById('template-preview-empty');
                if (cvsPaper) cvsPaper.style.display = 'none';
                if (prevEmpty) prevEmpty.style.display = 'flex';
                
                capturedImages = [];
                capturedLivePhotos = [];
                if (imgCache.photos) imgCache.photos = [];
                if (imgCache.livePhotos) imgCache.livePhotos = [];
                saveImages();
                saveLivePhotos();
                currentShot = 0;
                
                return; // Stop here, do not exit
            }

            // Otherwise, exit the app
            clearTemplateSession();
            if (stream) stream.getTracks().forEach(track => track.stop());

            const monitor = document.querySelector('.retro-monitor');
            if (monitor) {
                monitor.classList.remove('powering-on');
                monitor.classList.add('powering-off');
                setTimeout(() => { window.location.href = targetUrl; }, 350);
            } else {
                window.location.href = targetUrl;
            }
        });
    });

    // Keyboard ESC / B back routing
    document.addEventListener('keydown', (e) => {
        if (e.key === 'b' || e.key === 'B' || e.key === 'Escape') {
            const backBtn = document.querySelector('.btn-monitor-back');
            if (backBtn) backBtn.click();
        }
    });
});
</script>


<?php
// Load layouts footer
require APPROOT . '/Views/layouts/footer.php';
?>
