<?php

namespace App\Models;

use App\Core\Model;

class TemplateModel extends Model {

    /**
     * Retrieve all templates ordered by newest first.
     */
    public function getAll(): array {
        return $this->db()
            ->query('SELECT * FROM templates ORDER BY created_at DESC')
            ->fetchAll();
    }

    /**
     * Get a single template by ID.
     */
    public function getById(int $id): ?array {
        $stmt = $this->db()->prepare('SELECT * FROM templates WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a new template record and return its new ID (or false on failure).
     */
    public function create(array $data): int|false {
        $stmt = $this->db()->prepare('
            INSERT INTO templates
                (name, layout_type, slots, width, height, status, notes, image_url, frame_coordinates)
            VALUES
                (:name, :layout_type, :slots, :width, :height, :status, :notes, :image_url, :frame_coordinates)
        ');

        $ok = $stmt->execute([
            ':name'              => $data['name'],
            ':layout_type'       => $data['layout_type'],
            ':slots'             => $data['slots'] ?? 0,
            ':width'             => $data['width'] ?? 200,
            ':height'            => $data['height'] ?? 600,
            ':status'            => $data['status'] ?? 'Draft',
            ':notes'             => $data['notes'] ?? '',
            ':image_url'         => $data['image_url'] ?? '',
            ':frame_coordinates' => $data['frame_coordinates'] ?? '[]',
        ]);

        return $ok ? (int) $this->db()->lastInsertId() : false;
    }

    /**
     * Update an existing template record by ID.
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db()->prepare('
            UPDATE templates SET
                name              = :name,
                layout_type       = :layout_type,
                slots             = :slots,
                width             = :width,
                height            = :height,
                status            = :status,
                notes             = :notes,
                image_url         = :image_url,
                frame_coordinates = :frame_coordinates
            WHERE id = :id
        ');

        return $stmt->execute([
            ':name'              => $data['name'],
            ':layout_type'       => $data['layout_type'],
            ':slots'             => $data['slots'] ?? 0,
            ':width'             => $data['width'] ?? 200,
            ':height'            => $data['height'] ?? 600,
            ':status'            => $data['status'] ?? 'Active',
            ':notes'             => $data['notes'] ?? '',
            ':image_url'         => $data['image_url'] ?? '',
            ':frame_coordinates' => $data['frame_coordinates'] ?? '[]',
            ':id'                => $id,
        ]);
    }

    /**
     * Delete a template record by ID.
     */
    public function delete(int $id): bool {
        $stmt = $this->db()->prepare('DELETE FROM templates WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
