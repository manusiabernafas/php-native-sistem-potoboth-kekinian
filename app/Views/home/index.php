<?php
// Load layouts header
require APPROOT . '/Views/layouts/header.php';
?>

<!-- Cozy Diamond Pattern Background -->
<div class="bg-grid-overlay"></div>

<!-- CRT Scanline shader -->
<div class="scanline"></div>

<!-- Video Game Theme Floating Background Shapes -->
<div class="bg-floating-shapes">
    <i data-lucide="gamepad-2" class="shape shape-1"></i>
    <i data-lucide="ghost" class="shape shape-2"></i>
    <i data-lucide="heart" class="shape shape-3"></i>
    <i data-lucide="sword" class="shape shape-4"></i>
    <i data-lucide="zap" class="shape shape-5"></i>
    <i data-lucide="star" class="shape shape-6"></i>
    <i data-lucide="cpu" class="shape shape-7"></i>
</div>

<div class="portal-container">
    <!-- Game Boy Handheld Console -->
    <div class="gameboy">
        <!-- Bezel Header -->
        <div class="gb-header">
            <span class="gb-power-led"></span>
            <span class="gb-model">MOBOOTH COLOR</span>
        </div>
        
        <!-- LCD Screen -->
        <div class="gb-screen-wrapper">
            <div class="gb-screen powering-on">
                <div class="mascot-avatar">
                    <i data-lucide="smile" class="mascot-icon"></i>
                </div>
                <h1 class="portal-title">Mobooth</h1>
                <p class="portal-tagline">Photo Booth</p>
                <div class="screen-status-badge">INSERT COIN</div>
            </div>
        </div>
        
        <!-- Brand label -->
        <div class="gb-brand">
            <span class="brand-text">retro color</span>
        </div>
        
        <!-- Game Boy Controls -->
        <div class="gb-controls">
            <!-- D-pad (directional cross) -->
            <div class="gb-dpad">
                <div class="dpad-btn dpad-up" data-sound="hover"></div>
                <div class="dpad-btn dpad-right" data-sound="hover"></div>
                <div class="dpad-btn dpad-down" data-sound="hover"></div>
                <div class="dpad-btn dpad-left" data-sound="hover"></div>
                <div class="dpad-center"></div>
            </div>
            
            <!-- Action buttons container (tilted, pill-shaped) -->
            <div class="gb-action-buttons">
                <button type="button" class="gb-btn-mulai btn-explore-trigger" data-sound="select">
                    MULAI
                </button>
            </div>
        </div>
        
        <!-- Select / Start buttons (Menu controls) -->
        <div class="gb-menu-buttons">
            <a href="<?= BASEURL; ?>/home/about" class="gb-menu-btn page-link" data-sound="click">
                <span class="menu-btn-label">ABOUT</span>
            </a>
            <a href="<?= BASEURL; ?>/home/contact" class="gb-menu-btn page-link" data-sound="click">
                <span class="menu-btn-label">CONTACT</span>
            </a>
            <a href="<?= BASEURL; ?>/home/donate" class="gb-menu-btn page-link" data-sound="click">
                <span class="menu-btn-label">DONATE</span>
            </a>
        </div>
        
        <!-- Speaker line detail -->
        <div class="gb-speaker">
            <div class="speaker-line"></div>
            <div class="speaker-line"></div>
            <div class="speaker-line"></div>
            <div class="speaker-line"></div>
            <div class="speaker-line"></div>
        </div>
    </div>
</div>

<!-- Modal: Fake CAPTCHA -->
<div class="portal-modal-overlay" id="modal-captcha" style="z-index: 1000;">
    <div class="portal-modal-card" style="max-width: 320px; border-radius: 3px; border: 1px solid #d3d3d3; padding: 18px 14px; background: #f9f9f9; display: flex; align-items: center; justify-content: space-between; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); width: 100%;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div id="captcha-box" style="width: 26px; height: 26px; border: 2px solid #c1c1c1; border-radius: 2px; background: #fff; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: all 0.2s; flex-shrink: 0;">
                <!-- Icon injected via JS -->
            </div>
            <span style="font-family: 'Outfit', sans-serif; font-size: 0.95rem; font-weight: 400; color: #222;">I'm not a robot</span>
        </div>
        <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
            <i data-lucide="refresh-cw" style="width: 22px; height: 22px; color: #1a73e8; margin-bottom: 2px;"></i>
            <span style="font-family: 'Outfit', sans-serif; font-size: 0.55rem; color: #555;">reCAPTCHA</span>
            <div style="font-family: 'Outfit', sans-serif; font-size: 0.45rem; color: #9ca3af; margin-top: 2px;">Privacy - Terms</div>
        </div>
    </div>
</div>

<!-- Modal: Player Login -->
<div class="portal-modal-overlay" id="modal-login">
    <div class="portal-modal-card" style="max-width: 400px; text-align: center;">
        <div class="portal-modal-header">
            <h3 class="portal-modal-title">PLAYER LOGIN</h3>
            <button class="portal-modal-close btn-close-portal-modal"><i data-lucide="x"></i></button>
        </div>
        <div class="portal-modal-body">
            <p class="about-text" style="font-size: 0.85rem; margin-bottom: 24px; font-weight: 700;">
                Select your player account to continue:
            </p>
            
            <button type="button" class="support-link-btn btn-guest-login" data-sound="select" style="width: 100%; margin-bottom: 12px; background-color: #f1f5f9; color: #334155; border-color: #94a3b8; cursor: pointer;">
                <i data-lucide="user" style="width: 20px; height: 20px;"></i>
                <span>PLAY AS GUEST</span>
            </button>
            
            <button type="button" class="support-link-btn btn-google-login" data-sound="select" style="width: 100%; background-color: #ffffff; color: #1f2937; border-color: #e5e7eb; cursor: pointer;">
                <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google" style="width: 20px; height: 20px;">
                <span>SIGN IN WITH GOOGLE</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Start To Explore (Playground / Stations Mode Selection) -->
<div class="portal-modal-overlay" id="modal-explore">
    <div class="portal-modal-card" style="max-width: 450px;">
        <div class="portal-modal-header">
            <h3 class="portal-modal-title">CHOOSE MODE</h3>
            <button class="portal-modal-close btn-close-portal-modal"><i data-lucide="x"></i></button>
        </div>
        <div class="portal-modal-body" style="text-align: center;">
            <p class="about-text" id="greeting-text" style="font-size: 0.85rem; margin-bottom: 20px; font-weight: 700;">
                Mobooth gratis digunakan untuk siapa saja! Pilih pengalaman yang ingin Anda coba:
            </p>
            
            <a href="<?= BASEURL; ?>/home/playground" class="support-link-btn page-link" data-sound="select" style="text-decoration: none; background-color: #dcfce7; color: #16a34a; margin-bottom: 16px;">
                <i data-lucide="camera" style="width: 20px; height: 20px;"></i>
                <span>PLAY WEB PHOTOBOOTH</span>
            </a>
            
            <button type="button" class="support-link-btn btn-stations-trigger" data-sound="click" style="background-color: #f3f4f6; color: #1f2937; border-color: #1f2937; width: 100%; cursor: pointer;">
                <i data-lucide="map-pin" style="width: 20px; height: 20px;"></i>
                <span>ONLINE KIOSK STATIONS</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal: Booth Stations -->
<div class="portal-modal-overlay" id="modal-stations">
    <div class="portal-modal-card" style="max-width: 450px;">
        <div class="portal-modal-header">
            <h3 class="portal-modal-title">Booth Stations</h3>
            <button class="portal-modal-close btn-close-portal-modal"><i data-lucide="x"></i></button>
        </div>
        <div class="portal-modal-body">
            <?php if (empty($activeBooths)): ?>
                <div style="border: 2px dashed #cbd5e1; padding: 24px; text-align: center; color: #94a3b8;">
                    <i data-lucide="alert-circle" style="width: 36px; height: 36px; margin-bottom: 8px;"></i>
                    <p style="font-size: 0.85rem; font-weight: 700;">No active stations online.</p>
                </div>
            <?php else: ?>
                <div class="booth-list">
                    <?php foreach ($activeBooths as $booth): ?>
                        <div class="booth-item">
                            <div class="booth-item-header">
                                <span class="booth-item-name"><?= htmlspecialchars($booth['name']); ?></span>
                                <span class="booth-item-badge">ONLINE</span>
                            </div>
                            <div style="font-size: 0.8rem; color: #6b7280; display: flex; align-items: center; gap: 4px; margin-bottom: 8px; font-weight: 600;">
                                <i data-lucide="map-pin" style="width: 12px; height: 12px;"></i>
                                <span><?= htmlspecialchars($booth['location']); ?></span>
                            </div>
                            <?php if (!empty($booth['notes'])): ?>
                                <p style="font-size: 0.8rem; color: #4b5563; background: #ffffff; padding: 8px; border: 1.5px dashed #cbd5e1;"><?= htmlspecialchars($booth['notes']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Web Audio API Retro 8-bit sound synthesizer
function playRetroSound(type) {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        
        if (type === 'click') {
            osc.type = 'sine';
            osc.frequency.setValueAtTime(600, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1000, ctx.currentTime + 0.08);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
            osc.start();
            osc.stop(ctx.currentTime + 0.1);
        } else if (type === 'select') {
            osc.type = 'square';
            osc.frequency.setValueAtTime(300, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(700, ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.1, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.16);
            osc.start();
            osc.stop(ctx.currentTime + 0.16);
        } else if (type === 'hover') {
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(200, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(350, ctx.currentTime + 0.04);
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.05);
            osc.start();
            osc.stop(ctx.currentTime + 0.05);
        }
    } catch (e) {}
}

document.addEventListener('DOMContentLoaded', () => {
    // Intercept Game Boy Button hover/click sound effects
    document.querySelectorAll('[data-sound]').forEach(el => {
        const soundType = el.getAttribute('data-sound');
        el.addEventListener('mouseenter', () => {
            if (soundType === 'hover') playRetroSound('hover');
        });
        el.addEventListener('click', () => {
            playRetroSound(soundType);
        });
    });

    // Intercept menu link click to run turn-off animation before navigating
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            
            const screen = document.querySelector('.gb-screen');
            if (screen) {
                screen.classList.remove('powering-on');
                screen.classList.add('powering-off');
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 350);
            } else {
                window.location.href = targetUrl;
            }
        });
    });

    // CAPTCHA and Mode Selection Modal Logic
    const modalCaptcha = document.getElementById('modal-captcha');
    const modalLogin = document.getElementById('modal-login');
    const modalExplore = document.getElementById('modal-explore');
    const captchaBox = document.getElementById('captcha-box');
    
    document.querySelectorAll('.btn-explore-trigger').forEach(btn => {
        btn.addEventListener('click', () => {
            if (modalCaptcha) {
                // Reset CAPTCHA state
                captchaBox.innerHTML = '';
                captchaBox.style.borderColor = '#c1c1c1';
                modalCaptcha.classList.add('open');
            } else if (modalLogin) {
                modalLogin.classList.add('open');
            }
        });
    });

    if (captchaBox) {
        captchaBox.addEventListener('click', function() {
            if (this.classList.contains('verified') || this.classList.contains('loading')) return;
            
            this.classList.add('loading');
            this.innerHTML = '<i data-lucide="loader-2" style="width: 16px; height: 16px; color: #1a73e8;" class="animate-spin"></i>';
            if (typeof lucide !== 'undefined') lucide.createIcons({ nameAttr: 'data-lucide' });
            
            setTimeout(() => {
                this.classList.remove('loading');
                this.classList.add('verified');
                this.style.borderColor = '#16a34a';
                this.innerHTML = '<i data-lucide="check" style="width: 20px; height: 20px; color: #16a34a; stroke-width: 3;"></i>';
                if (typeof lucide !== 'undefined') lucide.createIcons({ nameAttr: 'data-lucide' });
                
                // Wait a bit, then close CAPTCHA and open Login modal
                setTimeout(() => {
                    modalCaptcha.classList.remove('open');
                    if (modalLogin) {
                        modalLogin.classList.add('open');
                    }
                    // Clean up for next open
                    setTimeout(() => { this.classList.remove('verified'); }, 300);
                }, 600);
            }, 1200); // 1.2s "checking" simulation
        });
    }

    // Helper function to handle login success
    function proceedToExplore(greetingText) {
        document.getElementById('greeting-text').innerText = greetingText;
        if (modalLogin) modalLogin.classList.remove('open');
        if (modalExplore) modalExplore.classList.add('open');
    }

    // Helper function to directly jump to playground
    function jumpToPlayground() {
        if (modalLogin) modalLogin.classList.remove('open');
        const targetUrl = '<?= BASEURL; ?>/home/playground';
        const screen = document.querySelector('.gb-screen');
        if (screen) {
            screen.classList.remove('powering-on');
            screen.classList.add('powering-off');
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 350);
        } else {
            window.location.href = targetUrl;
        }
    }

    // Handle Guest Login Button
    const btnGuestLogin = document.querySelector('.btn-guest-login');
    if (btnGuestLogin) {
        btnGuestLogin.addEventListener('click', () => {
            fetch('<?= BASEURL; ?>/home/player_login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: 'Guest', type: 'Guest' })
            }).then(() => {
                jumpToPlayground();
            }).catch(() => {
                jumpToPlayground(); // fallback
            });
        });
    }

    // Load Google Identity Services Script dynamically if not exists
    if (!document.querySelector('script[src="https://accounts.google.com/gsi/client"]')) {
        const script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Handle Google SSO Login Button (Real OAuth Integration)
    const btnGoogleLogin = document.querySelector('.btn-google-login');
    if (btnGoogleLogin) {
        btnGoogleLogin.addEventListener('click', () => {
            if (typeof google === 'undefined' || !google.accounts) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Script',
                    text: 'Pastikan koneksi internet Anda stabil untuk memuat Google OAuth Services.'
                });
                return;
            }

            const clientId = '675372273842-lkc0n2ts6g0uudep3d2lo1qb9oc40j7o.apps.googleusercontent.com';

            const tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: clientId,
                scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
                callback: (tokenResponse) => {
                    if (tokenResponse && tokenResponse.access_token) {
                        Swal.fire({
                            title: 'Authenticating...',
                            text: 'Memverifikasi akun Google Anda...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        // Fetch user info from Google using the access token
                        fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
                            headers: { Authorization: `Bearer ${tokenResponse.access_token}` }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.name && data.email) {
                                // Save to our database
                                fetch('<?= BASEURL; ?>/home/player_login', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ name: data.name, type: 'Google', email: data.email })
                                }).then(() => {
                                    Swal.close();
                                    jumpToPlayground();
                                }).catch(() => {
                                    Swal.close();
                                    jumpToPlayground();
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Gagal mengambil data profil Google.', 'error');
                        });
                    }
                },
            });

            // Trigger the Google popup
            tokenClient.requestAccessToken();
        });
    }

    // Handle nested stations modal trigger
    const modalStations = document.getElementById('modal-stations');
    const btnStations = document.querySelector('.btn-stations-trigger');
    if (btnStations && modalStations && modalExplore) {
        btnStations.addEventListener('click', () => {
            modalExplore.classList.remove('open');
            modalStations.classList.add('open');
        });
    }

    // Close buttons logic
    document.querySelectorAll('.btn-close-portal-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.portal-modal-overlay');
            if (modal) {
                modal.classList.remove('open');
            }
        });
    });

    // Close on overlay background click
    document.querySelectorAll('.portal-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
            }
        });
    });
});
</script>

<?php
// Load layouts footer
require APPROOT . '/Views/layouts/footer.php';
?>
