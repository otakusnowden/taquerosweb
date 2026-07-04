<?php

/*
|--------------------------------------------------------------------------
| TaquerosWeb — Site Configuration
|--------------------------------------------------------------------------
|
| Single source of truth for brand, contact, WhatsApp and social data.
| Reused across navbar, footer, modal, floating button and JSON-LD so
| nothing is duplicated. Values are overridable via .env.
|
*/

return [
    'name' => env('APP_NAME', 'TaquerosWeb'),
    'legal_name' => 'TaquerosWeb',
    'domain' => 'taquerosweb.com',
    'tagline' => 'Soluciones digitales para restaurantes',
    'description' => 'Llevamos tu restaurante al mundo digital: menú digital profesional, '
        . 'reservaciones, WhatsApp, código QR y todo lo que necesitas para vender más.',

    // Contact / NAP (used in footer + LocalBusiness schema)
    'email' => env('TW_EMAIL', 'hola@taquerosweb.com'),
    // Where the contact form delivers (falls back to the public email)
    'contact_to' => env('TW_CONTACT_TO', env('TW_EMAIL', 'hola@taquerosweb.com')),
    'phone' => env('TW_PHONE', '+52 5662866353'),
    'city' => 'México',
    'country' => 'MX',
    'locale' => 'es_MX',

    // WhatsApp — used by floating button, modal and CTAs
    'whatsapp' => [
        // International format without "+" or spaces, e.g. 5215512345678
        'number' => env('TW_WHATSAPP', '5215662866353'),
        'message' => 'Hola, me interesa el Menú Digital de TaquerosWeb. ¿Me dan más información?',
    ],

    // Social profiles (only render the ones with a URL)
    'social' => [
        'facebook' => env('TW_FACEBOOK', 'https://facebook.com/taquerosweb'),
        'instagram' => env('TW_INSTAGRAM', 'https://instagram.com/taquerosweb'),
        'tiktok' => env('TW_TIKTOK', 'https://tiktok.com/@taquerosweb'),
        'linkedin' => env('TW_LINKEDIN', 'https://linkedin.com/company/taquerosweb'),
        'whatsapp' => null, // built dynamically from whatsapp.number
    ],

    // Default Open Graph image (relative to /public)
    'og_image' => '/images/banner2.jpg',
];
