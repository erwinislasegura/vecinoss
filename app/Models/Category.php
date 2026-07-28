<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
final class Category
{
    public static function all(): array { return Database::connection()->query('SELECT c.*, COUNT(p.id) post_count FROM categories c LEFT JOIN posts p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll(); }
    public static function findBySlug(string $slug): ?array { $s=Database::connection()->prepare('SELECT * FROM categories WHERE slug=?'); $s->execute([$slug]); return $s->fetch() ?: null; }
}

