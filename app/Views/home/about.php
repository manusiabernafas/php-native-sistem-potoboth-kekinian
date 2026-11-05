<?php
// Load layouts header
require APPROOT . '/Views/layouts/header.php';
?>

<!-- Cozy Diamond Pattern Background -->
<div class="bg-grid-overlay"></div>

<!-- CRT Scanline shader -->
<div class="scanline"></div>

<div class="retro-monitor-container">
    <div class="retro-monitor powering-on">
        <!-- Monitor Header -->
        <div class="monitor-header">
            <h1 class="monitor-title">ABOUT MOBOOTH</h1>
            <span class="monitor-subtitle">SYSTEM v1.0</span>
        </div>
        
        <!-- Monitor Body (Scrollable & Rich) -->
        <div class="monitor-body">
            <h2 class="retro-h2">INTERACTIVE PHOTO BOOTH</h2>
            <p class="retro-p">
                Mobooth adalah platform photobooth interaktif modern yang dirancang untuk menghadirkan pengalaman berfoto yang cepat, menyenangkan, dan berkesan secara gratis untuk semua orang, di mana saja.
            </p>
            <p class="retro-p">
                Platform ini didesain agar sangat mudah digunakan, responsif, dan fleksibel untuk dijalankan di perangkat apa pun—mulai dari smartphone, tablet, laptop, hingga perangkat keras kiosk kustom. Ini adalah ruang bagi imajinasi terliar Anda!
            </p>
            
            <h2 class="retro-h2">DEDIKASI & UCAPAN TERIMA KASIH</h2>
            <p class="retro-p" style="border: 2px dashed #0f380f; padding: 12px; background: rgba(15, 56, 15, 0.08); line-height: 1.6; margin-bottom: 20px;">
                ★ <strong>TERIMA KASIH UNTUK KESAYANGANKU</strong> ★<br>
                Dedikasi spesial untuk kesayanganku tercinta yang selalu memberikan inspirasi tanpa henti, dukungan penuh kasih sayang, dan cinta tanpa batas. Terima kasih telah menemani setiap baris kode dan proses kreatif platform ini. Platform ini ada karena dirimu. 💚
            </p>

            <h2 class="retro-h2">KONTRIBUTOR SISTEM</h2>
            <div class="contributor-cards">
                <!-- Cococip Card -->
                <div class="contributor-card">
                    <div class="contributor-avatar-container">
                        <img src="<?= BASEURL; ?>/assets/uploads/cococip_avatar.png" alt="Cococip" class="contributor-avatar">
                    </div>
                    <div class="contributor-info">
                        <h3 class="contributor-name">Cococip</h3>
                        <p class="contributor-role">Lead Creator & Dev</p>
                        <div class="contributor-social-list">
                            <!-- GitHub -->
                            <div class="social-item">
                                <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0;">
                                    <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0 1 12 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                                </svg>
                                <a href="https://github.com/Cococip" target="_blank" class="social-link">Cococip</a>
                            </div>
                            <!-- TikTok -->
                            <div class="social-item">
                                <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0;">
                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.58a8.18 8.18 0 0 0 4.78 1.52V6.65a4.85 4.85 0 0 1-1-.04z"/>
                                </svg>
                                <a href="https://www.tiktok.com/@jogjapancing?_r=1&_t=ZS-97GFt7sYQfF" target="_blank" class="social-link">jopay</a>
                            </div>
                            <!-- YouTube -->
                            <div class="social-item">
                                <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0;">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                <a href="https://www.youtube.com/@jogjapancing" target="_blank" class="social-link">jopay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monitor Footer -->
        <div class="monitor-footer">
            <a href="<?= BASEURL; ?>/" class="btn-monitor-back page-link" data-sound="select">
                ◀ BACK TO MENU
            </a>
            <span class="monitor-footer-text">ESC / B KEY</span>
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
    // Re-initialize Lucide icons in case footer ran before DOM was ready
    if (typeof lucide !== 'undefined') lucide.createIcons();
    // Intercept menu link click to run turn-off animation before navigating
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href');
            playRetroSound('select');
            
            const monitor = document.querySelector('.retro-monitor');
            if (monitor) {
                monitor.classList.remove('powering-on');
                monitor.classList.add('powering-off');
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 350);
            } else {
                window.location.href = targetUrl;
            }
        });
    });

    // Support keyboard "B" or "Esc" key to go back
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
