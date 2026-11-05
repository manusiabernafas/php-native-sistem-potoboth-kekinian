<?php

namespace App\Models;

use App\Core\Model;

class ItemModel extends Model {

    /**
     * Get all items from MySQL database.
     * 
     * @return array
     */
    public function getAll(): array {
        return $this->db()
            ->query('SELECT * FROM items ORDER BY id ASC')
            ->fetchAll();
    }

    /**
     * Find item by ID.
     * 
     * @param int $id
     * @return array|null Item or null if not found
     */
    public function getById($id): ?array {
        $stmt = $this->db()->prepare('SELECT * FROM items WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a new item record.
     * 
     * @param array $data New item data (without ID)
     * @return bool Success status
     */
    public function create($data): bool {
        $stmt = $this->db()->prepare('
            INSERT INTO items (name, location, status, notes)
            VALUES (:name, :location, :status, :notes)
        ');
        
        return $stmt->execute([
            ':name'     => $data['name'],
            ':location' => $data['location'],
            ':status'   => $data['status'] ?? 'Active',
            ':notes'    => $data['notes'] ?? ''
        ]);
    }

    /**
     * Update an existing item by ID.
     * 
     * @param int $id
     * @param array $data Updated item data
     * @return bool Success status
     */
    public function update($id, $data): bool {
        $stmt = $this->db()->prepare('
            UPDATE items SET
                name     = :name,
                location = :location,
                status   = :status,
                notes    = :notes
            WHERE id = :id
        ');

        return $stmt->execute([
            ':name'     => $data['name'],
            ':location' => $data['location'],
            ':status'   => $data['status'],
            ':notes'    => $data['notes'] ?? '',
            ':id'       => (int) $id
        ]);
    }

    /**
     * Delete an item by ID.
     * 
     * @param int $id
     * @return bool Success status
     */
    public function delete($id): bool {
        $stmt = $this->db()->prepare('DELETE FROM items WHERE id = ?');
        return $stmt->execute([(int) $id]);
    }
}
