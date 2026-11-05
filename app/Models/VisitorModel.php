<?php

namespace App\Models;

use App\Core\Model;

class VisitorModel extends Model {
    public function create($name, $email, $type) {
        $checkStmt = $this->db()->prepare('SELECT id FROM visitors WHERE email = ? AND login_type = ?');
        $checkStmt->execute([$email, $type]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            // Update last login timestamp
            $updateStmt = $this->db()->prepare('UPDATE visitors SET created_at = CURRENT_TIMESTAMP WHERE id = ?');
            return $updateStmt->execute([$existing['id']]);
        }

        $stmt = $this->db()->prepare('INSERT INTO visitors (name, email, login_type) VALUES (?, ?, ?)');
        return $stmt->execute([$name, $email, $type]);
    }

    public function getAll() {
        $stmt = $this->db()->query('SELECT * FROM visitors ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}
