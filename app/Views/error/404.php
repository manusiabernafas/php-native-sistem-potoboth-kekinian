<?php
$css = 'error';
require APPROOT . '/Views/layouts/header.php';
?>

<div class="error-container">
    <div class="error-card">
        <div class="error-code">404</div>
        <h2>Halaman Tidak Ditemukan</h2>
        <p><?= htmlspecialchars($message ?? 'Maaf, halaman yang Anda tuju tidak terdaftar atau telah dipindahkan.'); ?></p>
        
        <a href="<?= BASEURL; ?>/" class="btn btn-primary">
            <i data-lucide="home" class="btn-icon"></i>
            <span>Kembali ke Beranda</span>
        </a>
    </div>
</div>

<?php
require APPROOT . '/Views/layouts/footer.php';
?>
