<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Setting
{
    private const DEFAULTS = [
        'weather_enabled' => '1',
        'weather_title' => 'El tiempo en tu comuna',
        'weather_fallback_name' => 'San Antonio',
        'weather_fallback_latitude' => '-33.5933',
        'weather_fallback_longitude' => '-71.6217',
        'horoscope_enabled' => '1',
        'horoscope_cta_eyebrow' => 'TU GUÍA DEL DÍA',
        'horoscope_cta_title' => 'Horóscopo diario',
        'horoscope_cta_text' => 'Descubre qué tienen preparado los astros para tu signo.',
        'horoscope_cta_button' => 'Ver mi horóscopo',
        'horoscope_page_title' => 'Horóscopo de hoy',
        'horoscope_page_intro' => 'Consulta las predicciones para los doce signos del zodiaco.',
    ];
    private const SOCIAL_DEFAULTS = [
        'social_facebook_enabled' => '0',
        'social_facebook_url' => '',
        'social_instagram_enabled' => '0',
        'social_instagram_url' => '',
        'social_x_enabled' => '0',
        'social_x_url' => '',
        'social_youtube_enabled' => '0',
        'social_youtube_url' => '',
        'social_whatsapp_enabled' => '0',
        'social_whatsapp_url' => '',
    ];

    public static function weather(): array
    {
        self::ensureTable();
        $rows = Database::connection()->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'weather_%'")->fetchAll();
        $values = array_column($rows, 'setting_value', 'setting_key');
        return array_merge(self::DEFAULTS, $values);
    }

    public static function saveWeather(array $values): void
    {
        self::ensureTable();
        $statement = Database::connection()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach (self::DEFAULTS as $key => $default) {
            if (!str_starts_with($key, 'weather_')) continue;
            $statement->execute(['key' => $key, 'value' => (string) ($values[$key] ?? $default)]);
        }
    }

    public static function horoscope(): array
    {
        self::ensureTable();
        $rows = Database::connection()->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'horoscope_%'")->fetchAll();
        $values = array_column($rows, 'setting_value', 'setting_key');
        return array_merge(self::DEFAULTS, $values);
    }

    public static function saveHoroscope(array $values): void
    {
        self::ensureTable();
        $statement = Database::connection()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach (self::DEFAULTS as $key => $default) {
            if (!str_starts_with($key, 'horoscope_')) continue;
            $statement->execute(['key' => $key, 'value' => (string) ($values[$key] ?? $default)]);
        }
    }

    public static function social(): array
    {
        self::ensureTable();
        $rows = Database::connection()->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'social_%'")->fetchAll();
        return array_merge(self::SOCIAL_DEFAULTS, array_column($rows, 'setting_value', 'setting_key'));
    }

    public static function saveSocial(array $values): void
    {
        self::ensureTable();
        $statement = Database::connection()->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach (self::SOCIAL_DEFAULTS as $key => $default) {
            $statement->execute(['key' => $key, 'value' => (string) ($values[$key] ?? $default)]);
        }
    }

    private static function ensureTable(): void
    {
        Database::connection()->exec('CREATE TABLE IF NOT EXISTS settings (setting_key VARCHAR(100) PRIMARY KEY, setting_value TEXT NOT NULL, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB');
    }
}
