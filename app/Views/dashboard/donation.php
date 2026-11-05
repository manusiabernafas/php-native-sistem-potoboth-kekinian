<?php
$css = 'dashboard';
require APPROOT . '/Views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php 
    $active_menu = 'donation';
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
                <h3>Kelola Portal</h3>
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

            <!-- Setting Form Container -->
            <section class="table-card" style="margin: 0 0 24px 0; padding: 32px;">
                <div class="table-header" style="margin-bottom: 24px; border-bottom: 3px solid #1f2937; padding-bottom: 16px;">
                    <div class="table-title">
                        <i data-lucide="heart" class="icon-md" style="color: #f43f5e; width: 32px; height: 32px;"></i>
                        <div>
                            <h2 style="font-family: var(--font-primary); font-weight: 700; font-size: 1.5rem; text-transform: uppercase;">Pengaturan Link Donasi</h2>
                            <p style="color: var(--text-secondary); margin-top: 4px; font-size: 0.85rem;">Konfigurasikan tautan Saweria, Bagibagi, QRIS, dan Rekening Bank yang akan ditampilkan di Kiosk.</p>
                        </div>
                    </div>
                </div>

                <form action="<?= BASEURL; ?>/dashboard/saveDonation" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? ''); ?>">
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="text-align: left;">
                            <label for="saweria_url" style="font-weight: 700; display: block; margin-bottom: 8px;">> LINK SAWERIA</label>
                            <input type="url" id="saweria_url" name="saweria_url" class="form-control" placeholder="https://saweria.co/username" value="<?= htmlspecialchars($donation['saweria_url'] ?? ''); ?>" style="width: 100%; border: 3px solid #1f2937; padding: 12px; font-family: var(--font-primary); font-size: 0.95rem; box-shadow: 3px 3px 0 0 #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div class="form-group" style="text-align: left;">
                            <label for="bagibagi_url" style="font-weight: 700; display: block; margin-bottom: 8px;">> LINK BAGIBAGI.CO</label>
                            <input type="url" id="bagibagi_url" name="bagibagi_url" class="form-control" placeholder="https://bagibagi.co/username" value="<?= htmlspecialchars($donation['bagibagi_url'] ?? ''); ?>" style="width: 100%; border: 3px solid #1f2937; padding: 12px; font-family: var(--font-primary); font-size: 0.95rem; box-shadow: 3px 3px 0 0 #1f2937; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="text-align: left;">
                            <label for="rek_no" style="font-weight: 700; display: block; margin-bottom: 8px;">> NOMOR REKENING / GOPAY / DLL</label>
                            <input type="text" id="rek_no" name="rek_no" class="form-control" placeholder="Contoh: 123-456-7890" value="<?= htmlspecialchars($donation['rek_no'] ?? ''); ?>" style="width: 100%; border: 3px solid #1f2937; padding: 12px; font-family: var(--font-primary); font-size: 0.95rem; box-shadow: 3px 3px 0 0 #1f2937; box-sizing: border-box;">
                        </div>
                        
                        <div class="form-group" style="text-align: left;">
                            <label for="rek_bank" style="font-weight: 700; display: block; margin-bottom: 8px;">> BANK / ATAS NAMA</label>
                            <input type="text" id="rek_bank" name="rek_bank" class="form-control" placeholder="Contoh: Bank BCA a/n Budi" value="<?= htmlspecialchars($donation['rek_bank'] ?? ''); ?>" style="width: 100%; border: 3px solid #1f2937; padding: 12px; font-family: var(--font-primary); font-size: 0.95rem; box-shadow: 3px 3px 0 0 #1f2937; box-sizing: border-box;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px; border: 3px dashed #1f2937; padding: 20px; background: #fafafa; text-align: left;">
                        <label for="qris_image" style="font-weight: 700; display: block; margin-bottom: 12px;">> UPLOAD BARU / GAMBAR QRIS (PNG/JPEG)</label>
                        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                            <?php if (!empty($donation['qris_image'])): 
                                $qrisUrl = $donation['qris_image'];
                                if (strpos($qrisUrl, 'http') !== 0) {
                                    $fileName = basename($qrisUrl);
                                    $qrisUrl = BASEURL . '/assets/uploads/' . $fileName;
                                }
                            ?>
                                <div style="text-align: center; background: #fff; border: 3px solid #1f2937; padding: 10px; box-shadow: 4px 4px 0 0 #1f2937;">
                                    <img src="<?= htmlspecialchars($qrisUrl); ?>" alt="Current QRIS" style="width: 120px; height: 120px; object-fit: contain; display: block; margin: 0 auto 6px auto;">
                                    <span style="font-size: 0.75rem; color: #16a34a; font-weight: 700; display: block; margin-top: 4px;">QRIS AKTIF</span>
                                </div>
                            <?php else: ?>
                                <div style="text-align: center; border: 3px dashed #cbd5e1; width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                                    <i data-lucide="qr-code" style="width: 48px; height: 48px; color: #94a3b8;"></i>
                                </div>
                            <?php endif; ?>
                            <div style="flex: 1; min-width: 200px;">
                                <input type="file" id="qris_image" name="qris_image" accept="image/*" style="display: block; margin-bottom: 8px;">
                                <small style="color: var(--text-secondary); font-size: 0.8rem; display: block;">Mendukung format gambar JPEG/PNG/GIF/WEBP. Ukuran file maksimal 2 MB.</small>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                        <a href="<?= BASEURL; ?>/dashboard" class="btn btn-secondary" style="text-decoration: none; padding: 12px 24px; font-weight: 700; border: 3px solid #1f2937; color: #1f2937; display: flex; align-items: center;">Kembali ke Home</a>
                        <button type="submit" class="btn btn-primary" style="padding: 12px 32px; font-weight: 700; box-shadow: 4px 4px 0 0 #1f2937; display: flex; align-items: center;">Simpan Pengaturan</button>
                    </div>
                </form>
            </section>

            <!-- Transaction History Container -->
            <section class="table-card" style="margin: 0 0 24px 0; padding: 32px;">
                <div class="table-header" style="margin-bottom: 24px; border-bottom: 3px solid #1f2937; padding-bottom: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="table-title">
                        <i data-lucide="history" class="icon-md" style="color: #2563eb; width: 32px; height: 32px;"></i>
                        <div>
                            <h2 style="font-family: var(--font-primary); font-weight: 700; font-size: 1.5rem; text-transform: uppercase;">Riwayat Transaksi Masuk</h2>
                            <p style="color: var(--text-secondary); margin-top: 4px; font-size: 0.85rem;">Daftar donasi masuk yang diterima secara real-time via Webhook Saweria/Bagibagi.</p>
                        </div>
                    </div>
                    <div>
                        <a href="<?= BASEURL; ?>/dashboard/simulateDonation" class="btn btn-primary" style="background-color: #10b981; border-color: #1f2937; text-decoration: none; padding: 8px 16px; font-size: 0.85rem; font-weight: 700; box-shadow: 3px 3px 0 0 #1f2937; display: flex; align-items: center; gap: 8px; color: #fff;">
                            <i data-lucide="play" style="width: 16px; height: 16px;"></i> Simulasi Masuk
                        </a>
                    </div>
                </div>

                <!-- Webhook URL Info Box -->
                <div style="border: 3px solid #1f2937; padding: 16px; background-color: #eff6ff; box-shadow: 4px 4px 0 0 #1f2937; margin-bottom: 24px; box-sizing: border-box; text-align: left;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: #2563eb; display: block; text-transform: uppercase; margin-bottom: 4px;">> URL Webhook Live Anda:</span>
                    <code style="font-family: monospace; font-size: 0.85rem; font-weight: 700; color: #1e3a8a; word-break: break-all; display: block; background: #fff; padding: 8px; border: 2px solid #1f2937;">
                        <?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . BASEURL . "/dashboard/webhook?platform=saweria" ?>
                    </code>
                    <small style="color: var(--text-secondary); font-size: 0.75rem; display: block; margin-top: 6px;">Masukkan URL di atas pada pengaturan developer akun Saweria / Bagibagi Anda untuk integrasi otomatis.</small>
                </div>

                <div class="table-responsive" style="overflow-x: auto; border: 3px solid #1f2937; box-shadow: 4px 4px 0 0 #1f2937;">
                    <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background-color: #f8fafc; border-bottom: 3px solid #1f2937;">
                                <th style="padding: 12px; font-weight: 700; font-family: var(--font-primary); font-size: 0.85rem; border-right: 2px solid #1f2937; width: 120px;">PLATFORM</th>
                                <th style="padding: 12px; font-weight: 700; font-family: var(--font-primary); font-size: 0.85rem; border-right: 2px solid #1f2937; width: 150px;">PENGIRIM</th>
                                <th style="padding: 12px; font-weight: 700; font-family: var(--font-primary); font-size: 0.85rem; border-right: 2px solid #1f2937; width: 120px;">JUMLAH</th>
                                <th style="padding: 12px; font-weight: 700; font-family: var(--font-primary); font-size: 0.85rem; border-right: 2px solid #1f2937;">PESAN</th>
                                <th style="padding: 12px; font-weight: 700; font-family: var(--font-primary); font-size: 0.85rem; width: 150px;">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody id="donation-transactions-body">
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-secondary); font-family: var(--font-primary); font-size: 0.9rem;">
                                        Belum ada riwayat transaksi donasi masuk.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $tx): ?>
                                    <tr style="border-bottom: 2px solid #1f2937;">
                                        <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; text-align: center;">
                                            <?php if (strtolower($tx['platform']) === 'saweria'): ?>
                                                <span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border: 1px solid #d97706; font-size: 0.75rem; font-weight: 700; display: inline-block;">SAWERIA</span>
                                            <?php else: ?>
                                                <span style="background-color: #fce7f3; color: #db2777; padding: 4px 8px; border: 1px solid #db2777; font-size: 0.75rem; font-weight: 700; display: inline-block;">BAGIBAGI</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; color: #1f2937;">
                                            <?= htmlspecialchars($tx['donator_name']); ?>
                                        </td>
                                        <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; color: #16a34a;">
                                            Rp <?= number_format($tx['amount'], 0, ',', '.'); ?>
                                        </td>
                                        <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; color: #4b5563; line-height: 1.4;">
                                            <?= htmlspecialchars($tx['message'] ?? '-'); ?>
                                        </td>
                                        <td style="padding: 12px; font-family: var(--font-primary); font-size: 0.8rem; color: #6b7280;">
                                            <?= date('d M Y H:i', strtotime($tx['created_at'])); ?>
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

<!-- Bottom Navigation for Mobile (Gojek Style) -->
<nav class="mobile-bottom-nav">
    <a href="<?= BASEURL; ?>/dashboard" class="mobile-nav-item">
        <i data-lucide="home"></i>
        <span>Home</span>
    </a>
    <a href="<?= BASEURL; ?>/template" class="mobile-nav-item">
        <i data-lucide="layout"></i>
        <span>Template</span>
    </a>
    <a href="<?= BASEURL; ?>/dashboard/donation" class="mobile-nav-item active">
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

    // Poll for new transactions every 5 seconds
    let lastTransactionCount = <?= count($transactions); ?>;
    
    function fetchTransactions() {
        fetch('<?= BASEURL; ?>/dashboard/getTransactionsJson')
            .then(res => res.json())
            .then(data => {
                if (data && Array.isArray(data)) {
                    if (data.length !== lastTransactionCount) {
                        lastTransactionCount = data.length;
                        renderTransactions(data);
                    }
                }
            })
            .catch(err => console.error('Error polling transactions:', err));
    }

    function renderTransactions(transactions) {
        const tbody = document.getElementById('donation-transactions-body');
        if (!tbody) return;
        
        if (transactions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-secondary); font-family: var(--font-primary); font-size: 0.9rem;">
                        Belum ada riwayat transaksi donasi masuk.
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        transactions.forEach(tx => {
            const platformBadge = tx.platform.toLowerCase() === 'saweria' 
                ? `<span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border: 1px solid #d97706; font-size: 0.75rem; font-weight: 700; display: inline-block;">SAWERIA</span>`
                : `<span style="background-color: #fce7f3; color: #db2777; padding: 4px 8px; border: 1px solid #db2777; font-size: 0.75rem; font-weight: 700; display: inline-block;">BAGIBAGI</span>`;

            // Format Currency
            const amountFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(tx.amount).replace('IDR', 'Rp');

            // Format Date (e.g. 16 Jun 2026 20:30)
            const date = new Date(tx.created_at);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = String(date.getDate()).padStart(2, '0');
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const formattedDate = `${day} ${month} ${year} ${hours}:${minutes}`;

            html += `
                <tr style="border-bottom: 2px solid #1f2937;">
                    <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; text-align: center;">
                        ${platformBadge}
                    </td>
                    <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; color: #1f2937;">
                        ${escapeHTML(tx.donator_name)}
                    </td>
                    <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; font-weight: 700; color: #16a34a;">
                        ${amountFormatted}
                    </td>
                    <td style="padding: 12px; border-right: 2px solid #1f2937; font-family: var(--font-primary); font-size: 0.85rem; color: #4b5563; line-height: 1.4;">
                        ${escapeHTML(tx.message || '-')}
                    </td>
                    <td style="padding: 12px; font-family: var(--font-primary); font-size: 0.8rem; color: #6b7280;">
                        ${formattedDate}
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function escapeHTML(str) {
        if (!str) return '';
        return str.replace(/[&<>'"]/g, 
            tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
        );
    }

    // Start polling every 5 seconds
    setInterval(fetchTransactions, 5000);
});
</script>

<?php require APPROOT . '/Views/layouts/footer.php'; ?>
