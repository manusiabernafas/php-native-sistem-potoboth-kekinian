<?php

namespace App\Core;

class Controller {
    /**
     * Render a view file and pass data to it.
     */
    public function view(string $view, array $data = []): void {
        extract($data);
        $viewFile = APPROOT . '/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Error: View '$view' not found.");
        }
    }

    /**
     * Simple redirect helper.
     */
    public function redirect(string $url): void {
        header('Location: ' . BASEURL . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Send a JSON response.
     */
    public function json(mixed $data, int $statusCode = 200): void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // ─── CSRF helpers ──────────────────────────────────────────────

    /**
     * Generate (or retrieve) the session CSRF token.
     */
    protected function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate the CSRF token from POST.
     * Redirects back to $fallback on failure.
     */
    protected function verifyCsrf(string $fallback = ''): void {
        $token = $_POST['csrf_token'] ?? '';
        if (
            empty($token) ||
            !hash_equals($_SESSION['csrf_token'] ?? '', $token)
        ) {
            $_SESSION['flash_error'] = 'Sesi tidak valid. Silakan coba lagi.';
            $this->redirect($fallback);
        }
        // Rotate token after use
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Get donation configuration settings from JSON data store.
     */
    public static function getDonationSettings(): array {
        $path = DATAPATH . '/donation.json';
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) {
                return array_merge([
                    'saweria_url' => '',
                    'bagibagi_url' => '',
                    'rek_no' => '',
                    'rek_bank' => '',
                    'qris_image' => ''
                ], $data);
            }
        }
        return [
            'saweria_url' => '',
            'bagibagi_url' => '',
            'rek_no' => '',
            'rek_bank' => '',
            'qris_image' => ''
        ];
    }

    /**
     * Save donation configuration settings to JSON data store.
     */
    public static function saveDonationSettings(array $data): bool {
        $path = DATAPATH . '/donation.json';
        return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }
}
