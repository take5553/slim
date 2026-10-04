<?php
declare(strict_types=1);

namespace App\Auth;

use PDO;

final class AuthUserRepository
{
    public function __construct(
        private PDO $pdo,
    ) {}

    public function findOrCreateBySocial(string $provider, string $providerUserId, ?string $name, ?string $email): int
    {
        $stmt = $this->pdo->prepare(<<<SQL
            SELECT
                user_id
            FROM
                social_accounts
            WHERE
                provider = ?
                AND provider_user_id = ?
        SQL);
        $stmt->execute([$provider, $providerUserId]);
        $userId = $stmt->fetchColumn();
        if ($userId !== false) {
            return (int) $userId;
        }

        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare(<<<SQL
                INSERT INTO users (name, email) VALUES (?, ?)
            SQL)->execute([$name, $email]);
            $userId = (int) $this->pdo->lastInsertId();

            $this->pdo->prepare(<<<SQL
                INSERT INTO social_accounts (user_id, provider, provider_user_id) VALUES (?, ?, ?)
            SQL)->execute([$userId, $provider, $providerUserId]);

            $this->pdo->commit();
            return $userId;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            throw $e;
        }
    }
}
