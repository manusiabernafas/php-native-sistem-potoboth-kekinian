<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TemplateModel;

class TemplateController extends Controller {
    private $templateModel;

    public function __construct() {
        // Ensure user is logged in
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = 'Akses ditolak. Silakan login terlebih dahulu.';
            $this->redirect('login');
        }
        $this->templateModel = new TemplateModel();
    }

    /**
     * Render main template management view.
     */
    public function index() {
        $templates = $this->templateModel->getAll();

        $data = [
            'title'      => 'Kelola Template - Mobooth',
            'user'       => $_SESSION['user'],
            'templates'  => $templates,
            'success'    => $_SESSION['flash_success'] ?? null,
            'error'      => $_SESSION['flash_error'] ?? null,
            'csrf_token' => $this->csrfToken()
        ];

        // Consume flash messages
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->view('template/index', $data);
    }

    /**
     * Render the template creator screen.
     */
    public function add() {
        $data = [
            'title'      => 'Buat Template Baru - Mobooth',
            'user'       => $_SESSION['user'],
            'isEdit'     => false,
            'template'   => null,
            'csrf_token' => $this->csrfToken()
        ];
        $this->view('template/editor', $data);
    }

    /**
     * Render the template editor screen.
     * 
     * @param int $id
     */
    public function edit($id = 0) {
        $id = intval($id);
        $template = $this->templateModel->getById($id);

        if (!$template) {
            $_SESSION['flash_error'] = 'Template tidak ditemukan!';
            $this->redirect('template');
        }

        $data = [
            'title'      => 'Edit Template - Mobooth',
            'user'       => $_SESSION['user'],
            'isEdit'     => true,
            'template'   => $template,
            'csrf_token' => $this->csrfToken()
        ];
        $this->view('template/editor', $data);
    }

    /**
     * Create a new template.
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf('template');
            $name = trim($_POST['name'] ?? '');
            $layout_type = trim($_POST['layout_type'] ?? '');
            $notes = trim($_POST['notes'] ?? '');

            // Basic validation
            if (empty($name) || empty($layout_type)) {
                $_SESSION['flash_error'] = 'Nama dan Kategori wajib diisi!';
                $this->redirect('template');
            }

            // Map layout size defaults
            $width = 200;
            $height = 600;
            if ($layout_type === '4R Grid') {
                $width = 400;
                $height = 600;
            } elseif ($layout_type === 'A4 Document') {
                $width = 420;
                $height = 594;
            } elseif ($layout_type === 'Kartu Pokemon') {
                $width = 250;
                $height = 350;
            }

            $newTemplate = [
                'name' => $name,
                'layout_type' => $layout_type,
                'slots' => 0,
                'width' => $width,
                'height' => $height,
                'status' => 'Draft',
                'notes' => $notes,
                'image_url' => '',
                'frame_coordinates' => '[]'
            ];

            $newId = $this->templateModel->create($newTemplate);
            if ($newId) {
                $_SESSION['flash_success'] = 'Template baru berhasil diinisialisasi!';
                $this->redirect('template/edit/' . $newId);
            } else {
                $_SESSION['flash_error'] = 'Gagal menyimpan template baru.';
                $this->redirect('template');
            }
        } else {
            $this->redirect('template');
        }
    }

    /**
     * Update an existing template.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf('template');
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $layout_type = trim($_POST['layout_type'] ?? '');
            $slots = intval($_POST['slots'] ?? 0);
            $width = intval($_POST['width'] ?? 0);
            $height = intval($_POST['height'] ?? 0);
            $status = trim($_POST['status'] ?? 'Active');
            $notes = trim($_POST['notes'] ?? '');
            $frame_coordinates = trim($_POST['frame_data'] ?? '');

            // Validation
            if ($id <= 0 || empty($name) || empty($layout_type) || $width <= 0 || $height <= 0) {
                $_SESSION['flash_error'] = 'Data template tidak valid atau kurang lengkap!';
                $this->redirect('template');
            }

            $updatedTemplate = [
                'name' => $name,
                'layout_type' => $layout_type,
                'slots' => $slots,
                'width' => $width,
                'height' => $height,
                'status' => $status,
                'notes' => $notes,
                'frame_coordinates' => $frame_coordinates
            ];

            if (isset($_FILES['bg_image']) && $_FILES['bg_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['bg_image'];
                $allowedMimes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
                $maxSize = 5 * 1024 * 1024; // 5 MB

                // Validate real MIME type (not just extension)
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($file['tmp_name']);

                if (!in_array($realMime, $allowedMimes, true)) {
                    $_SESSION['flash_error'] = 'File latar harus berupa gambar (PNG/JPEG/GIF/WEBP)!';
                    $this->redirect('template');
                }
                if ($file['size'] > $maxSize) {
                    $_SESSION['flash_error'] = 'Ukuran file maksimum 5 MB!';
                    $this->redirect('template');
                }

                $uploadDir = dirname(APPROOT) . '/assets/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                // Force safe extension from real MIME, never trust client filename
                $ext = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif', 'image/webp' => 'webp'][$realMime];
                $fileName = bin2hex(random_bytes(12)) . '.' . $ext;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $updatedTemplate['image_url'] = BASEURL . '/assets/uploads/' . $fileName;
                }
            } else {
                $existing = $this->templateModel->getById($id);
                if ($existing && isset($existing['image_url'])) {
                    $updatedTemplate['image_url'] = $existing['image_url'];
                }
            }

            if ($this->templateModel->update($id, $updatedTemplate)) {
                $_SESSION['flash_success'] = 'Template berhasil disimpan!';
            } else {
                $_SESSION['flash_error'] = 'Gagal memperbarui template.';
            }
            $this->redirect('template');
        } else {
            $this->redirect('template');
        }
    }

    /**
     * Delete a template by ID.
     * 
     * @param int $id
     */
    public function delete($id = 0) {
        $id = intval($id);
        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID template tidak valid!';
            $this->redirect('template');
        }

        if ($this->templateModel->delete($id)) {
            $_SESSION['flash_success'] = 'Template berhasil dihapus!';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus template.';
        }
        $this->redirect('template');
    }
}
