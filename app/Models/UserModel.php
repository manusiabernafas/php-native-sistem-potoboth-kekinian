<?php

namespace App\Models;

use App\Core\Model;

class UserModel extends Model {

    /**
     * Find a user by username (case-insensitive).
     *
     * @param string $username
     * @return array|null
     */
    public function getByUsername(string $username): ?array {
        $stmt = $this->db()->prepare(
            'SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Verify credentials and return safe user data (no password).
     *
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function login(string $username, string $password): array|false {
        $user = $this->getByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    /**
     * Update user's password.
     *
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(int $userId, string $newPassword): bool {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db()->prepare(
            'UPDATE users SET password = ? WHERE id = ?'
        );
        return $stmt->execute([$hashed, $userId]);
    }
}
