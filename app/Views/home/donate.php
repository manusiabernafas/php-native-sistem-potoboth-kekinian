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
            <h1 class="monitor-title">DONATE & SUPPORT</h1>
            <span class="monitor-subtitle">SYSTEM v1.0</span>
        </div>
        
        <!-- Monitor Body (Scrollable & Rich) -->
        <div class="monitor-body">
            <h2 class="retro-h2">SUPPORT MOBOOTH</h2>
            <p class="retro-p">
                Dukungan Anda membantu kami untuk terus menjaga server tetap menyala dan mengembangkan fitur-fitur baru yang lebih keren! Silakan pilih metode donasi di bawah ini:
            </p>

            <?php 
            $hasSaweria = !empty($donation['saweria_url']);
            $hasBagibagi = !empty($donation['bagibagi_url']);
            $hasRek = !empty($donation['rek_no']);
            $hasQris = !empty($donation['qris_image']);
            $anyDonation = $hasSaweria || $hasBagibagi || $hasRek || $hasQris;
            ?>

            <?php if (!$anyDonation): ?>
                <p class="retro-p">Metode donasi belum dikonfigurasi oleh administrator.</p>
            <?php else: ?>
                <ul class="retro-list">
                    <?php if ($hasSaweria): ?>
                        <li class="retro-list-item">
                            SAWERIA: <a href="<?= htmlspecialchars($donation['saweria_url']); ?>" target="_blank" style="color: #0f380f; text-decoration: underline; font-weight: 900;">SAWER VIA SAWERIA</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($hasBagibagi): ?>
                        <li class="retro-list-item">
                            BAGIBAGI: <a href="<?= htmlspecialchars($donation['bagibagi_url']); ?>" target="_blank" style="color: #0f380f; text-decoration: underline; font-weight: 900;">DUKUNG VIA BAGIBAGI</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($hasRek): ?>
                        <li class="retro-list-item">
                            REKENING: <strong><?= htmlspecialchars($donation['rek_bank']); ?> - <?= htmlspecialchars($donation['rek_no']); ?></strong>
                        </li>
                    <?php endif; ?>

                    <?php if ($hasQris): 
                        $qrisUrl = $donation['qris_image'];
                        if (strpos($qrisUrl, 'http') !== 0) {
                            $fileName = basename($qrisUrl);
                            $qrisUrl = BASEURL . '/assets/uploads/' . $fileName;
                        }
                    ?>
                        <li class="retro-list-item">
                            QRIS: <a href="<?= htmlspecialchars($qrisUrl); ?>" target="_blank" style="color: #0f380f; text-decoration: underline; font-weight: 900;">BUKA GAMBAR QRIS</a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
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
