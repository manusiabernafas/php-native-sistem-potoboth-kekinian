<?php

// Paths configuration
define('APPROOT', dirname(__DIR__) . '/app');
define('DATAPATH', dirname(__DIR__) . '/data');

// Base URL (dynamically detected relative to root domain, makes asset loading robust)
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = dirname($scriptName);
$detectedBaseUrl = ($scriptDir === '/' || $scriptDir === '\\') ? '' : str_replace('\\', '/', $scriptDir);
define('BASEURL', $detectedBaseUrl);
define('APP_NAME', 'Mobooth Admin');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mobooth_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
