<?php

class AuditLog
{
    // Get recent audit logs
    public static function recent(
        int $limit = 20
    ): array {

        // Keep limit safe
        $limit = max(
            1,
            min($limit, 100)
        );

        $stmt = db()->query("
            SELECT
                audit_logs.*,
                users.name
            FROM audit_logs
            LEFT JOIN users
                ON audit_logs.user_id =
                   users.id
            ORDER BY audit_logs.id DESC
            LIMIT $limit
        ");

        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );
    }
}