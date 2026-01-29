<?php

namespace App;

class Message
{
    public static function create(int $userId, string $subject, string $body): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO messages (user_id, subject, body) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $subject, $body]);

        $messageId = (int) $db->lastInsertId();
        AuditLog::log($userId, 'MESSAGE_CREATE', "Created message #$messageId: $subject");

        return $messageId;
    }

    public static function getAll(int $limit = 50, int $offset = 0): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT m.*, u.name as author_name, u.email as author_email, u.avatar as author_avatar
            FROM messages m
            JOIN users u ON m.user_id = u.id
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public static function getByUser(int $userId, int $limit = 50): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT m.*, u.name as author_name, u.email as author_email, u.avatar as author_avatar
            FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.user_id = ?
            ORDER BY m.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT m.*, u.name as author_name, u.email as author_email, u.avatar as author_avatar
            FROM messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function delete(int $id, int $userId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM messages WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);

        if ($stmt->rowCount() > 0) {
            AuditLog::log($userId, 'MESSAGE_DELETE', "Deleted message #$id");
            return true;
        }
        return false;
    }
}
