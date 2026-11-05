<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class LoginController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    /**
     * Show login form.
     * Accessible via: url/login or url/
     */
    public function index() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user'])) {
            $this->redirect('dashboard');
        }

        $data = [
            'title'      => 'Login Admin - Mobooth',
            'error'      => $_SESSION['flash_error'] ?? null,
            'success'    => $_SESSION['flash_success'] ?? null,
            'csrf_token' => $this->csrfToken()
        ];

        // Clear flash messages once consumed
        unset($_SESSION['flash_error'], $_SESSION['flash_success']);

        $this->view('auth/login', $data);
    }

    /**
     * Handle login submission.
     * Accessible via: url/login/submit
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf('login');

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $_SESSION['flash_error'] = 'Username dan Password wajib diisi!';
                $this->redirect('login');
            }

            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Regenerate session ID on login to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['user'] = $user;
                $_SESSION['flash_success'] = 'Selamat datang kembali, ' . htmlspecialchars($user['name']) . '!';
                $this->redirect('dashboard');
            } else {
                $_SESSION['flash_error'] = 'Username atau Password salah!';
                $this->redirect('login');
            }
        } else {
            $this->redirect('login');
        }
    }

    /**
     * Handle user logout.
     * Accessible via: url/login/logout
     */
    public function logout() {
        // Clear session data
        unset($_SESSION['user']);
        session_destroy();
        
        // Redirect to public home page
        header('Location: ' . BASEURL);
        exit;
    }
}
