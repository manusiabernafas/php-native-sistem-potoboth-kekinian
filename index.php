<?php

/**
 * Front Controller
 * All requests are routed through this file.
 */

// 1. Start session for managing login states and flash messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load global configuration settings
require_once __DIR__ . '/config/config.php';

// Server-side Inactivity session timeout: 15 minutes (900 seconds)
if (isset($_SESSION['user'])) {
    $timeout = 900;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        
        session_start();
        $_SESSION['flash_error'] = 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 15 menit.';
        header('Location: ' . BASEURL . '/login');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// 3. Register PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/app/';

    // Does the class use the prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relativeClass = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // If the file exists, load it
    if (file_exists($file)) {
        require_once $file;
    }
});

// 4. Initialize Core App (routing and dispatching)
$app = new App\Core\App();
