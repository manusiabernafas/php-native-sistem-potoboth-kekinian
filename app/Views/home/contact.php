<?php
// Load layouts header
require APPROOT . '/Views/layouts/header.php';
?>

<style>
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .contact-card {
        background: #9bbc0f;
        border: 3px solid #0f380f;
        border-radius: 0;
        padding: 16px;
        box-shadow: 4px 4px 0 #0f380f;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .contact-card-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 0.55rem;
        color: #0f380f;
        border-bottom: 2px dashed #0f380f;
        padding-bottom: 8px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .contact-card-title svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }
    .contact-link {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Press Start 2P', monospace;
        font-size: 0.5rem;
        color: #0f380f;
        text-decoration: none;
        background: #8bac0f;
        border: 2px solid #0f380f;
        padding: 8px 10px;
        box-shadow: 2px 2px 0 #0f380f;
        transition: transform 0.1s, box-shadow 0.1s;
        word-break: break-all;
    }
    .contact-link:hover {
        transform: translate(2px, 2px);
        box-shadow: 0 0 0 #0f380f;
        background: #306230;
        color: #9bbc0f;
    }
    .contact-link svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }
    .collab-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-family: 'Press Start 2P', monospace;
        font-size: 0.48rem;
        color: #0f380f;
        line-height: 1.7;
        margin-bottom: 10px;
    }
    .collab-item-icon {
        flex-shrink: 0;
        font-size: 0.7rem;
        margin-top: 2px;
    }
    .collab-badge {
        display: inline-block;
        background: #306230;
        color: #9bbc0f;
        font-family: 'Press Start 2P', monospace;
        font-size: 0.4rem;
        padding: 4px 8px;
        border: 2px solid #0f380f;
        box-shadow: 2px 2px 0 #0f380f;
        margin: 3px 3px 3px 0;
    }
    .req-step {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .req-step-num {
        flex-shrink: 0;
        background: #306230;
        color: #9bbc0f;
        font-family: 'Press Start 2P', monospace;
        font-size: 0.55rem;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #0f380f;
        box-shadow: 2px 2px 0 #0f380f;
    }
    .req-step-text {
        font-family: 'Press Start 2P', monospace;
        font-size: 0.48rem;
        color: #0f380f;
        line-height: 1.8;
    }
    .req-step-text strong {
        color: #306230;
    }
    @media (max-width: 600px), (orientation: portrait) {
        .contact-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Cozy Diamond Pattern Background -->
<div class="bg-grid-overlay"></div>

<!-- CRT Scanline shader -->
<div class="scanline"></div>

<div class="retro-monitor-container">
    <div class="retro-monitor powering-on">
        <!-- Monitor Header -->
        <div class="monitor-header">
            <h1 class="monitor-title">CONTACT US</h1>
            <span class="monitor-subtitle">GET IN TOUCH</span>
        </div>
        
        <!-- Monitor Body -->
        <div class="monitor-body">

            <!-- Intro -->
            <h2 class="retro-h2">HALO! 👋</h2>
            <p class="retro-p">
                Mau ngobrol soal <strong>kerja sama</strong>, <strong>request template</strong>, atau sekadar say hi?
                Hubungi aku lewat sosial media di bawah — aku aktif dan fast-respon!
            </p>

            <!-- Sosial Media -->
            <h2 class="retro-h2">📱 FIND ME ON</h2>
            <div class="contact-grid">

                <!-- WhatsApp -->
                <div class="contact-card">
                    <div class="contact-card-title">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.025.507 3.934 1.397 5.61L.055 23.454a.5.5 0 0 0 .491.546l5.987-1.342A11.94 11.94 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.956 9.956 0 0 1-5.359-1.559l-.38-.23-3.878.869.884-3.761-.249-.389A9.957 9.957 0 0 1 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                        WHATSAPP
                    </div>
                    <a href="https://wa.me/628997813111" target="_blank" class="contact-link">
                        <!-- Ganti nomormu di href: wa.me/628XXXXXXXXXX -->
                        <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M2 20l1.5-5.5A9 9 0 1 1 7.5 18.5z"/></svg>
                        +62 899-7813-111
                    </a>
                </div>

                <!-- Instagram -->
                <div class="contact-card">
                    <div class="contact-card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        INSTAGRAM
                    </div>
                    <a href="https://instagram.com/heymobooth" target="_blank" class="contact-link">
                        <!-- Ganti username instagrammu -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><rect x="2" y="2" width="20" height="20" rx="5"/></svg>
                        @heymobooth
                    </a>
                </div>

                <!-- TikTok -->
                <div class="contact-card">
                    <div class="contact-card-title">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.58a8.18 8.18 0 0 0 4.77 1.52V6.65a4.85 4.85 0 0 1-1-.04z"/></svg>
                        TIKTOK
                    </div>
                    <a href="https://tiktok.com/@mobooth.id" target="_blank" class="contact-link">
                        <!-- Ganti username tiktokmu -->
                        <svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/></svg>
                        @mobooth.id
                    </a>
                </div>

                <!-- Email -->
                <div class="contact-card">
                    <div class="contact-card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                        EMAIL
                    </div>
                    <a href="mailto:heymobooth@gmail.com" class="contact-link">
                        <!-- Ganti emailmu -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="m2 7 10 7 10-7"/></svg>
                        heymobooth@gmail.com
                    </a>
                </div>
            </div>

            <!-- Kerjasama -->
            <h2 class="retro-h2">KERJASAMA & KOLABORASI</h2>
            <p class="retro-p">Terbuka untuk berbagai bentuk kerja sama! Berikut yang bisa kita lakukan bareng:</p>
            <div class="collab-item">
                <div class="req-step-num">1</div>
                <div>
                    <strong>Photobooth Event</strong><br>
                    Integrasikan Mobooth ke event-mu — pesta, wedding, corporate event, atau gathering komunitas. Tersedia kustomisasi template sesuai tema.
                </div>
            </div>
            <div class="collab-item">
                <div class="req-step-num">2</div>
                <div>
                    <strong>Desain Template Custom</strong><br>
                    Butuh template dengan branding spesifik? Aku bisa bikin frame photobooth custom sesuai identitas brand-mu.
                </div>
            </div>
            <div class="collab-item">
                <div class="req-step-num">3</div>
                <div>
                    <strong>Endorsement & Review</strong><br>
                    Punya produk/layanan yang cocok buat kreator atau komunitas fotografi? Reach out, kita ngobrol!
                </div>
            </div>
            <div class="collab-item">
                <div class="req-step-num">4</div>
                <div>
                    <strong>Integrasi & Partnership</strong><br>
                    Mau embed Mobooth ke platform-mu atau eksplorasi partnership teknis lainnya? DM aja langsung.
                </div>
            </div>
            <p class="retro-p">
                Tipe kerja sama yang tersedia:
                <br>
                <span class="collab-badge">PAID COLLAB</span>
                <span class="collab-badge">BARTER</span>
                <span class="collab-badge">KOMUNITAS</span>
                <span class="collab-badge">OPEN SOURCE</span>
                <span class="collab-badge">REVENUE SHARE</span>
            </p>

            <!-- Request Template -->
            <h2 class="retro-h2">REQUEST TEMPLATE</h2>
            <p class="retro-p">
                Pengen punya template photobooth dengan desain tertentu? Berikut cara request-nya:
            </p>
            <div class="req-step">
                <div class="req-step-num">1</div>
                <div class="req-step-text">
                    <strong>Hubungi via WA / IG DM</strong><br>
                    Ceritain konsep template yang kamu mau — tema, warna, jumlah slot foto, dan referensi desain kalau ada.
                </div>
            </div>
            <div class="req-step">
                <div class="req-step-num">2</div>
                <div class="req-step-text">
                    <strong>Diskusi & Estimasi</strong><br>
                    Aku kasih estimasi waktu pengerjaan dan besaran biaya (kalau custom paid). Template free/komunitas akan masuk antrian rilis publik.
                </div>
            </div>
            <div class="req-step">
                <div class="req-step-num">3</div>
                <div class="req-step-text">
                    <strong>Proses & Revisi</strong><br>
                    Template dikerjain, kamu bisa kasih feedback. Maksimal 2x revisi untuk request custom.
                </div>
            </div>
            <div class="req-step">
                <div class="req-step-num">4</div>
                <div class="req-step-text">
                    <strong>Live di Mobooth</strong><br>
                    Template-mu langsung bisa dipakai di Mobooth Playground. Nama/brand kamu bisa dicantumkan sebagai kredit!
                </div>
            </div>

            <!-- Jam Respons -->
            <h2 class="retro-h2">⏰ ESTIMASI RESPONS</h2>
            <ul class="retro-list">
                <li class="retro-list-item">WA / IG DM: 1–3 jam (hari aktif)</li>
                <li class="retro-list-item">Email: 1–2 hari kerja</li>
                <li class="retro-list-item">Hari aktif: Senin – Sabtu</li>
                <li class="retro-list-item">Zona waktu: WIB (UTC+7)</li>
            </ul>

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
