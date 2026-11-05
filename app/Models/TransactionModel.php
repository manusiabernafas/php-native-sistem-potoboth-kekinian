<?php

namespace App\Models;

use App\Core\Model;

class TransactionModel extends Model {

    public function __construct() {
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS donation_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            platform VARCHAR(50) NOT NULL,
            donator_name VARCHAR(100) NOT NULL,
            amount DECIMAL(15, 2) NOT NULL,
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->db()->exec($sql);
    }

    /**
     * Get all transaction logs.
     */
    public function getAll(): array {
        return $this->db()
            ->query('SELECT * FROM donation_transactions ORDER BY created_at DESC')
            ->fetchAll();
    }

    /**
     * Create a new transaction log.
     */
    public function create(array $data): bool {
        $stmt = $this->db()->prepare('
            INSERT INTO donation_transactions (platform, donator_name, amount, message)
            VALUES (:platform, :donator_name, :amount, :message)
        ');
        return $stmt->execute([
            ':platform' => $data['platform'],
            ':donator_name' => $data['donator_name'],
            ':amount' => $data['amount'],
            ':message' => $data['message'] ?? ''
        ]);
    }
}
