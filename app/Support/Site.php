<?php

namespace App\Support;

use Illuminate\Support\Facades\Config;

/**
 * Small façade over config/taquerosweb.php so views and schema builders
 * share one source of truth (DRY). No business logic — just convenience.
 */
class Site
{
    /**
     * Build a wa.me deep link with an optional pre-filled message.
     */
    public static function whatsappUrl(?string $message = null): string
    {
        $number = preg_replace('/\D+/', '', (string) Config::get('taquerosweb.whatsapp.number'));
        $text = $message ?? Config::get('taquerosweb.whatsapp.message');

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($text);
    }

    /**
     * Active social profiles as [network => url], skipping empty ones.
     * WhatsApp is injected from the configured number.
     *
     * @return array<string, string>
     */
    public static function socials(): array
    {
        $socials = (array) Config::get('taquerosweb.social', []);
        $socials['whatsapp'] = self::whatsappUrl();

        return array_filter($socials, static fn ($url) => filled($url));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Config::get('taquerosweb.' . $key, $default);
    }
}
