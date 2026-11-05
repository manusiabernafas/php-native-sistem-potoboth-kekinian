<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ItemModel;

class DashboardController extends Controller {
    private $itemModel;

    public function __construct() {
        // Enforce session check: if user session is not active, redirect to login
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Akses ditolak. Silakan login terlebih dahulu.';
            $this->redirect('login');
        }
        $this->itemModel = new ItemModel();
    }

    /**
     * Display the main Dashboard page.
     */
    public function index() {
        $items = $this->itemModel->getAll();

        $data = [
            'title' => 'Dashboard Admin - Mobooth',
            'user' => $_SESSION['user'],
            'items' => $items,
            'success' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null
        ];

        // Consume flash messages
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/index', $data);
    }

    /**
     * Display the Visitors Data page.
     */
    public function visitors() {
        require_once APPROOT . '/Models/VisitorModel.php';
        $visitorModel = new \App\Models\VisitorModel();
        $visitors = $visitorModel->getAll();

        $data = [
            'title' => 'Data Pengunjung - Mobooth',
            'user' => $_SESSION['user'],
            'visitors' => $visitors,
            'active_menu' => 'visitors'
        ];

        $this->view('dashboard/visitors', $data);
    }

    /**
     * Handle creating a new item.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $status = trim($_POST['status'] ?? 'Active');
            $notes = trim($_POST['notes'] ?? '');

            // Form validation
            if (empty($name) || empty($location)) {
                $_SESSION['flash_error'] = 'Nama Booth dan Lokasi wajib diisi!';
                $this->redirect('dashboard');
            }

            $newItem = [
                'name' => $name,
                'location' => $location,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->itemModel->create($newItem)) {
                $_SESSION['flash_success'] = 'Booth baru berhasil ditambahkan!';
            } else {
                $_SESSION['flash_error'] = 'Gagal menyimpan booth baru.';
            }
            $this->redirect('dashboard');
        } else {
            $this->redirect('dashboard');
        }
    }

    /**
     * Handle updating an existing item.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $status = trim($_POST['status'] ?? 'Active');
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if ($id <= 0 || empty($name) || empty($location)) {
                $_SESSION['flash_error'] = 'Data booth tidak valid atau kurang lengkap!';
                $this->redirect('dashboard');
            }

            $updatedItem = [
                'name' => $name,
                'location' => $location,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->itemModel->update($id, $updatedItem)) {
                $_SESSION['flash_success'] = 'Data booth berhasil diperbarui!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui data booth.';
            }
            $this->redirect('dashboard');
        } else {
            $this->redirect('dashboard');
        }
    }

    /**
     * Handle deleting an item.
     * 
     * @param int $id
     */
    public function delete($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID booth tidak valid!';
            $this->redirect('dashboard');
        }

        if ($this->itemModel->delete($id)) {
            $_SESSION['flash_success'] = 'Booth berhasil dihapus!';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus booth.';
        }
        $this->redirect('dashboard');
    }

    /**
     * Display the dedicated Donation Settings page.
     */
    public function donation() {
        $transactionModel = new \App\Models\TransactionModel();
        $transactions = $transactionModel->getAll();

        $data = [
            'title' => 'Pengaturan Donasi - Mobooth',
            'user' => $_SESSION['user'],
            'donation' => self::getDonationSettings(),
            'transactions' => $transactions,
            'csrf_token' => $this->csrfToken(),
            'success' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null
        ];

        // Consume flash messages
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('dashboard/donation', $data);
    }

    /**
     * Fetch all transactions as JSON.
     */
    public function getTransactionsJson() {
        $transactionModel = new \App\Models\TransactionModel();
        $transactions = $transactionModel->getAll();
        $this->json($transactions);
    }

    /**
     * Save donation configuration settings from admin form submission.
     */
    public function saveDonation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf('dashboard/donation');

            $saweria = trim($_POST['saweria_url'] ?? '');
            $bagibagi = trim($_POST['bagibagi_url'] ?? '');
            $rek_no = trim($_POST['rek_no'] ?? '');
            $rek_bank = trim($_POST['rek_bank'] ?? '');
            
            $existing = self::getDonationSettings();
            $qris_image = $existing['qris_image'] ?? '';

            // Handle QRIS image upload if present
            if (isset($_FILES['qris_image']) && $_FILES['qris_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['qris_image'];
                $allowedMimes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024; // 2 MB

                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($file['tmp_name']);

                if (!in_array($realMime, $allowedMimes, true)) {
                    $_SESSION['flash_error'] = 'File QRIS harus berupa gambar (PNG/JPEG/GIF/WEBP)!';
                    $this->redirect('dashboard/donation');
                }
                if ($file['size'] > $maxSize) {
                    $_SESSION['flash_error'] = 'Ukuran file QRIS maksimum 2 MB!';
                    $this->redirect('dashboard/donation');
                }

                $uploadDir = dirname(APPROOT) . '/assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $ext = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif', 'image/webp' => 'webp'][$realMime];
                $fileName = 'qris_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    // Delete old QRIS image if exists and is not empty
                    if (!empty($qris_image)) {
                        $oldFile = dirname(APPROOT) . str_replace(BASEURL, '', $qris_image);
                        if (file_exists($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                    $qris_image = BASEURL . '/assets/uploads/' . $fileName;
                }
            }

            $settings = [
                'saweria_url' => $saweria,
                'bagibagi_url' => $bagibagi,
                'rek_no' => $rek_no,
                'rek_bank' => $rek_bank,
                'qris_image' => $qris_image
            ];

            if (self::saveDonationSettings($settings)) {
                $_SESSION['flash_success'] = 'Konfigurasi donasi berhasil diperbarui!';
            } else {
                $_SESSION['flash_error'] = 'Gagal menyimpan konfigurasi donasi.';
            }

            $this->redirect('dashboard/donation');
        } else {
            $this->redirect('dashboard/donation');
        }
    }

    /**
     * Handle password update request from profile modal/page.
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Use fallback redirect in verifyCsrf
            $redirectTo = $_POST['redirect_to'] ?? 'dashboard';
            if (strpos($redirectTo, 'http') === 0 || strpos($redirectTo, '//') === 0) {
                $redirectTo = 'dashboard';
            }

            $this->verifyCsrf($redirectTo);

            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($newPassword)) {
                $_SESSION['flash_error'] = 'Password baru tidak boleh kosong!';
                $this->redirect($redirectTo);
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['flash_error'] = 'Konfirmasi password baru tidak cocok!';
                $this->redirect($redirectTo);
            }

            $userModel = new \App\Models\UserModel();
            $userId = $_SESSION['user']['id'];

            if ($userModel->updatePassword($userId, $newPassword)) {
                $_SESSION['flash_success'] = 'Password berhasil diubah!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui password.';
            }

            $this->redirect($redirectTo);
        } else {
            $this->redirect('dashboard');
        }
    }

    /**
     * Simulate a random donation from Saweria or Bagibagi.co.
     */
    public function simulateDonation() {
        $platforms = ['Saweria', 'Bagibagi.co'];
        $names = ['RetroGamer', 'PikachuFan', 'Nostalgia90', 'BudiSlayer', 'AestheticUser', 'WibuGanteng'];
        $messages = ['Semangat terus kembangin Mobooth!', 'Gemas banget templatenya!', 'Lanjutkan bang!', 'Insert coin support!', 'Sawer dikit buat jajan kopi', 'Ini pokemon love meter lucu bgt'];
        $amounts = [10000, 15000, 20000, 25000, 50000, 100000];

        $data = [
            'platform' => $platforms[array_rand($platforms)],
            'donator_name' => $names[array_rand($names)],
            'amount' => $amounts[array_rand($amounts)],
            'message' => $messages[array_rand($messages)]
        ];

        $transactionModel = new \App\Models\TransactionModel();
        if ($transactionModel->create($data)) {
            $_SESSION['flash_success'] = 'Donasi simulasi berhasil ditambahkan!';
        } else {
            $_SESSION['flash_error'] = 'Gagal menambahkan donasi simulasi.';
        }

        $this->redirect('dashboard/donation');
    }

    /**
     * Webhook endpoint to receive live donation alerts from Saweria or Bagibagi.co.
     */
    public function webhook() {
        // Retrieve raw post data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if ($data) {
            $name = trim($data['donator_name'] ?? $data['sender'] ?? 'Anonim');
            $amount = floatval($data['amount'] ?? $data['amount_raw'] ?? 0);
            $message = trim($data['message'] ?? $data['notes'] ?? '');
            
            $platform = $_GET['platform'] ?? 'Webhook';
            if (isset($data['platform'])) {
                $platform = $data['platform'];
            }

            if ($amount > 0) {
                $transactionModel = new \App\Models\TransactionModel();
                $transactionModel->create([
                    'platform' => ucfirst($platform),
                    'donator_name' => $name,
                    'amount' => $amount,
                    'message' => $message
                ]);

                $this->json(['status' => 'success', 'message' => 'Donation logged successfully']);
            }
        }
        
        $this->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
    }
}
