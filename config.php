<?php
declare(strict_types=1);

/**
 * config.php — Configuración central de TaquerosWeb
 *
 * Este archivo es la única fuente de verdad para:
 *  - Datos del sitio (URL, nombre, SEO)
 *  - Definición de paquetes (sincronizados con la tabla `paquetes` en DB)
 *
 * IMPORTANTE: los `db_id` deben coincidir exactamente con los IDs
 * insertados por database/seeds.sql (1–6 en orden).
 */

return [

    // ── Identidad del sitio ────────────────────────────────────────────
    'site_name'    => 'TaquerosWeb',
    'site_url'     => 'https://taquerosweb.com',
    'site_tagline' => 'Sitios Web que Sí Venden',
    'site_desc'    => 'Sitios web profesionales al precio de unos tacos. Listos en días, sin complicaciones. El sabor digital que tu negocio necesita.',
    'site_image'   => 'images/taquerosweb_banner.jpg',

    // ── Contacto ──────────────────────────────────────────────────────
    'whatsapp' => '5215662866353',   // formato internacional sin +
    'email'    => 'hola@taquerosweb.com',

    // ── Paquetes ──────────────────────────────────────────────────────
    // Cada clave debe coincidir con el orden de seeds.sql.
    // `db_id` = ID real en la tabla `paquetes` (usado en /api/register).
    'paquetes' => [
        'menu_digital' => [
            'db_id'       => 8,
            'sku'         => 'TW-MENU',
            'emoji'       => '📱',
            'nombre'      => 'Menú Digital',
            'subtitulo'   => 'Tu menú siempre actualizado',
            'precio'      => 2499,
            'precio_desde'=> false,
            'disponible'  => true,
            'entrega'     => '3 días hábiles',
            'tipo_badge'  => 'best', // '' | 'hot' | 'best'
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'demo'        => 'demo/wings',
            'demo_biz'    => 'El Sabor Yucateco',
        ],
        'starter' => [
            'db_id'       => 1,
            'sku'         => 'TW-STARTER',
            'emoji'       => '🌮',
            'nombre'      => 'Starter',
            'subtitulo'   => 'Tu primer sitio completo',
            'precio'      => 3499,
            'precio_desde'=> false,
            'disponible'  => true,
            'entrega'     => '5 días hábiles',
            'tipo_badge'  => '',           // '' | 'hot' | 'best'
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'demo'        => 'demo/starter.html',
            'demo_biz'    => 'Tacos Don Beto',
        ],

        'catalogo' => [
            'db_id'       => 3,
            'sku'         => 'TW-CATALOGO',
            'emoji'       => '🫔',
            'nombre'      => 'Catálogo',
            'subtitulo'   => 'Productos y servicios',
            'precio'      => 6499,
            'precio_desde'=> false,
            'disponible'  => true,
            'entrega'     => '5–7 días hábiles',
            'tipo_badge'  => 'hot',
            'btn_clase'   => 'btn-card-primary',
            'btn_texto'   => 'Comprar 🛒',
            'demo'        => 'demo/negocio_pro/',
            'demo_biz'    => 'Café Molcajete',
        ],

        'tienda' => [
            'db_id'       => 4,
            'sku'         => 'TW-TIENDA',
            'emoji'       => '🛒',
            'nombre'      => 'Tienda Lite',
            'subtitulo'   => 'E-commerce básico',
            'precio'      => 9499,
            'precio_desde'=> false,
            'disponible'  => true,
            'entrega'     => '7–10 días hábiles',
            'tipo_badge'  => '',
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'demo'        => 'demo/tienda.html',
            'demo_biz'    => 'Jabones Raíz',
        ],
        /*
        'pro' => [
            'db_id'       => 5,
            'sku'         => 'TW-PRO',
            'emoji'       => '💼',
            'nombre'      => 'Negocio Pro',
            'subtitulo'   => 'Sitio completo + dominio + hosting',
            'precio'      => 12999,
            'precio_desde'=> false,
            'disponible'  => true,
            'entrega'     => '10–14 días hábiles',
            'tipo_badge'  => 'best',
            'btn_clase'   => 'btn-card-gold',
            'btn_texto'   => 'Comprar ⭐',
            'demo'        => 'demo/pro.html',
            'demo_biz'    => 'Dental Sonríe',
        ],
        
        'premium' => [
            'db_id'       => 6,
            'sku'         => 'TW-PREMIUM',
            'emoji'       => '🏆',
            'nombre'      => 'Premium',
            'subtitulo'   => 'Desarrollo a medida',
            'precio'      => 19999,
            'precio_desde'=> true,
            'disponible'  => true,
            'entrega'     => 'Según proyecto',
            'tipo_badge'  => '',
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Solicitar cotización 📩',
            'demo'        => 'demo/pro.html',
            'demo_biz'    => 'Ejemplo Premium',
        ],
        */
        
    ],
];
