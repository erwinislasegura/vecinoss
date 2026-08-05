<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;
final class Advertisement
{
    public static function published(int $limit=6): array { self::ensureTable(); $s=Database::connection()->prepare("SELECT * FROM advertisements WHERE status='published' AND starts_at<=CURRENT_TIMESTAMP AND (ends_at IS NULL OR ends_at>=CURRENT_TIMESTAMP) ORDER BY sort_order ASC,id DESC LIMIT ?");$s->bindValue(1,$limit,\PDO::PARAM_INT);$s->execute();return $s->fetchAll(); }
    public static function allAdmin(): array { self::ensureTable();return Database::connection()->query('SELECT * FROM advertisements ORDER BY sort_order ASC,created_at DESC')->fetchAll(); }
    public static function find(int $id): ?array { self::ensureTable();$s=Database::connection()->prepare('SELECT * FROM advertisements WHERE id=?');$s->execute([$id]);return $s->fetch()?:null; }
    public static function save(array $data,?int $id=null): void { self::ensureTable();$v=[$data['name'],$data['image'],$data['target_url'],$data['alt_text'],$data['open_new_tab'],$data['sort_order'],$data['status'],$data['starts_at'],$data['ends_at']];if($id){$v[]=$id;$sql='UPDATE advertisements SET name=?,image=?,target_url=?,alt_text=?,open_new_tab=?,sort_order=?,status=?,starts_at=?,ends_at=? WHERE id=?';}else{$sql='INSERT INTO advertisements(name,image,target_url,alt_text,open_new_tab,sort_order,status,starts_at,ends_at) VALUES(?,?,?,?,?,?,?,?,?)';}Database::connection()->prepare($sql)->execute($v); }
    public static function delete(int $id): void { self::ensureTable();Database::connection()->prepare('DELETE FROM advertisements WHERE id=?')->execute([$id]); }
    private static function ensureTable(): void { Database::connection()->exec("CREATE TABLE IF NOT EXISTS advertisements (id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,name VARCHAR(140) NOT NULL,image VARCHAR(500) NOT NULL,target_url VARCHAR(1000) NOT NULL,alt_text VARCHAR(180) NOT NULL,open_new_tab TINYINT(1) NOT NULL DEFAULT 1,sort_order INT NOT NULL DEFAULT 0,status ENUM('draft','published') NOT NULL DEFAULT 'draft',starts_at DATETIME NOT NULL,ends_at DATETIME NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,INDEX idx_advertisements_public (status,starts_at,ends_at,sort_order)) ENGINE=InnoDB"); }
}
