<?php

namespace App\Core;

use PDO;
use PDOException;

class Model {
    private static ?PDO $instance = null;

    /**
     * Get (or create) the singleton PDO database connection.
     */
    protected function db(): PDO {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST, DB_NAME, DB_CHARSET
            );
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // Show a clean error rather than leaking credentials
                die('Database connection failed. Please contact administrator.');
            }
        }
        return self::$instance;
    }
}
