<?php
// Specify the page-specific stylesheet name
$css = 'auth';
require APPROOT . '/Views/layouts/header.php';
?>

<div class="auth-container">
    <!-- Retro HUD Bar -->
    <div class="pixel-hud">
        <div class="hud-item">
            <i data-lucide="heart" class="hud-icon heart-icon"></i>
            <span>HP x3</span>
        </div>
        <div class="hud-item">
            <i data-lucide="coins" class="hud-icon coins-icon"></i>
            <span>999</span>
        </div>
        <div class="hud-item">
            <i data-lucide="swords" class="hud-icon weapon-icon"></i>
            <span>LV. 99</span>
        </div>
    </div>

    <!-- Retro Game Login Card -->
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <i data-lucide="gamepad-2" class="logo-icon"></i>
                <span>MOBOOTH_</span>
            </div>
            <h2>ADMIN PORTAL</h2>
            <p class="blink-text">PRESS START TO LOGIN</p>
        </div>

        <!-- Notification Alerts -->
        <?php if (!empty($error)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        title: 'LOGIN GAGAL!',
                        text: <?= json_encode($error); ?>,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        <?php endif; ?>

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

        <!-- Login Form -->
        <form action="<?= BASEURL; ?>/login/submit" method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">
            <div class="form-group">
                <label for="username">> CHOOSE CHARACTER (USERNAME)</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="ENTER USERNAME" required autofocus autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <label for="password">> ENTER PASSCODE (PASSWORD)</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="ENTER PASSWORD" required>
                </div>
            </div>

            <button type="submit" class="btn btn-pixel btn-block">
                <span>PRESS TO LOGIN</span>
            </button>
        </form>
    </div>
    
    <!-- Retro Footer Copyright -->
    <div class="pixel-footer">
        <p>© 2026 MOBOOTH PIXEL LTD. INSERT COIN TO CONTINUE.</p>
    </div>
</div>

<?php
require APPROOT . '/Views/layouts/footer.php';
?>
