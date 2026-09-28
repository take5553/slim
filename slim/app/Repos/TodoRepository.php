<?php

declare(strict_types=1);

namespace App\Repos;

use PDO;

final class TodoRepository
{
    private PDO $pdo;

    public function __construct(string $path)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }

        $this->pdo = new PDO('sqlite:' . $path, options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $this->pdo->exec(<<<SQL
            CREATE TABLE IF NOT EXISTS todos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                done INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        SQL);
    }

    /** @return list<array{id:int, title:string, done:bool}> */
    public function all(): array
    {
        $rows = $this->pdo->query('SELECT id, title, done FROM todos ORDER BY id')->fetchAll();
        return array_map($this->hydrate(...), $rows);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, title, done FROM todos WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    public function add(string $title): array
    {
        $this->pdo->prepare('INSERT INTO todos (title) VALUES (?)')
            ->execute([$title]);
        return $this->find((int) $this->pdo->lastInsertId());
    }

    public function toggle(int $id): ?array
    {
        $this->pdo->prepare('UPDATE todos SET done = 1 - done WHERE id = ?')
            ->execute([$id]);
        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM todos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function remaining(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM todos WHERE done = 0')->fetchColumn();
    }

    private function hydrate(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'title' => (string) $row['title'],
            'done' => (bool) $row['done']
        ];
    }
}
