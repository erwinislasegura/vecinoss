<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Setting
{
    public const HOROSCOPE_SIGNS = [
        ['key' => 'aries', 'name' => 'Aries', 'symbol' => '♈', 'range' => '21 mar — 19 abr', 'text' => 'Tu iniciativa abre una conversación importante. Avanza con decisión, pero escucha antes de responder.'],
        ['key' => 'tauro', 'name' => 'Tauro', 'symbol' => '♉', 'range' => '20 abr — 20 may', 'text' => 'Un asunto práctico comienza a ordenarse. Prioriza lo simple y evita cargar con tareas ajenas.'],
        ['key' => 'geminis', 'name' => 'Géminis', 'symbol' => '♊', 'range' => '21 may — 20 jun', 'text' => 'Las ideas fluyen con rapidez. Anota lo esencial y elige una sola dirección para convertirla en acción.'],
        ['key' => 'cancer', 'name' => 'Cáncer', 'symbol' => '♋', 'range' => '21 jun — 22 jul', 'text' => 'Tu intuición estará especialmente activa. Reserva tiempo para tu entorno cercano y protege tus límites.'],
        ['key' => 'leo', 'name' => 'Leo', 'symbol' => '♌', 'range' => '23 jul — 22 ago', 'text' => 'Tu presencia inspira a otros. Comparte el protagonismo y una colaboración dará mejores resultados.'],
        ['key' => 'virgo', 'name' => 'Virgo', 'symbol' => '♍', 'range' => '23 ago — 22 sep', 'text' => 'Una pequeña mejora tendrá un gran efecto. Ordena pendientes y deja espacio para lo inesperado.'],
        ['key' => 'libra', 'name' => 'Libra', 'symbol' => '♎', 'range' => '23 sep — 22 oct', 'text' => 'El equilibrio llega cuando expresas lo que necesitas. Una conversación honesta aliviará tensiones.'],
        ['key' => 'escorpio', 'name' => 'Escorpio', 'symbol' => '♏', 'range' => '23 oct — 21 nov', 'text' => 'Observa antes de tomar una decisión definitiva. Hay información valiosa en los detalles que otros pasan por alto.'],
        ['key' => 'sagitario', 'name' => 'Sagitario', 'symbol' => '♐', 'range' => '22 nov — 21 dic', 'text' => 'Una oportunidad invita a ampliar tus horizontes. Revisa bien las condiciones y atrévete a explorar.'],
        ['key' => 'capricornio', 'name' => 'Capricornio', 'symbol' => '♑', 'range' => '22 dic — 19 ene', 'text' => 'La constancia empieza a mostrar resultados. Reconoce lo avanzado y ajusta la siguiente meta.'],
        ['key' => 'acuario', 'name' => 'Acuario', 'symbol' => '♒', 'range' => '20 ene — 18 feb', 'text' => 'Tu mirada diferente resuelve un problema antiguo. Explica tu idea con claridad y suma aliados.'],
        ['key' => 'piscis', 'name' => 'Piscis', 'symbol' => '♓', 'range' => '19 feb — 20 mar', 'text' => 'La sensibilidad será una fortaleza si la acompañas de límites claros. Confía en tu percepción.'],
    ];

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
        'horoscope_signs' => '',
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
        $settings = array_merge(self::DEFAULTS, $values);
        $settings['signs'] = self::decodeHoroscopeSigns($settings['horoscope_signs'] ?? '');
        return $settings;
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

    public static function encodeHoroscopeSigns(array $values): string
    {
        $signs = [];
        foreach (self::HOROSCOPE_SIGNS as $default) {
            $key = $default['key'];
            $signs[] = [
                'key' => $key,
                'name' => $default['name'],
                'symbol' => $default['symbol'],
                'range' => mb_substr(trim((string) ($values[$key]['range'] ?? $default['range'])), 0, 40),
                'text' => mb_substr(trim((string) ($values[$key]['text'] ?? $default['text'])), 0, 500),
            ];
        }

        return json_encode($signs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
    }

    private static function decodeHoroscopeSigns(string $json): array
    {
        $custom = json_decode($json, true);
        $byKey = [];
        if (is_array($custom)) {
            foreach ($custom as $item) {
                if (is_array($item) && isset($item['key'])) {
                    $byKey[(string) $item['key']] = $item;
                }
            }
        }

        $signs = [];
        foreach (self::HOROSCOPE_SIGNS as $default) {
            $item = $byKey[$default['key']] ?? [];
            $range = trim((string) ($item['range'] ?? ''));
            $text = trim((string) ($item['text'] ?? ''));
            $signs[] = [
                'key' => $default['key'],
                'name' => $default['name'],
                'symbol' => $default['symbol'],
                'range' => $range !== '' ? $range : $default['range'],
                'text' => $text !== '' ? $text : $default['text'],
            ];
        }

        return $signs;
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
