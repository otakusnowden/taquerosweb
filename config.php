<?php
/**
 * TaquerosWeb — Configuración global del sitio
 * ─────────────────────────────────────────────
 * Centraliza precios, metadatos y ajustes generales.
 * NUNCA subir datos sensibles a repositorios públicos.
 */

return [

    // ── Identidad del sitio ──────────────────────────────────────────────────
    'site_url'    => 'https://taquerosweb.com',
    'site_name'   => 'TaquerosWeb',
    'site_domain' => 'taquerosweb.com',
    'site_tagline'=> 'Sitios Web que Sí Venden',
    'site_desc'   => 'Sitios web profesionales para taquerías y negocios mexicanos. Desde $2,199 MXN, listos en días, con SEO incluido y WhatsApp integrado.',
    'site_image'  => 'images/taquerosweb_banner.jpg',   // Ruta relativa (también se usa en OG)
    'whatsapp'    => '5215662866353',
    'email'       => 'hola@taquerosweb.com',

    // ── Paquetes y precios ───────────────────────────────────────────────────
    // Cada entrada puede tratarse como un producto individual (Google Shopping).
    'paquetes' => [

        'starter' => [
            'id'          => 'starter',
            'nombre'      => 'Starter',
            'subtitulo'   => 'Landing Express',
            'precio'      => 2199,
            'emoji'       => '🌮',
            'demo'        => 'demo/starter.html',
            'demo_biz'    => 'Tacos Don Beto',
            'entrega'     => '3 días hábiles',
            'disponible'  => true,
            'destacado'   => false,
            'tipo_badge'  => null,
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'sku'         => 'TW-STARTER-01',
        ],

        'basico' => [
            'id'          => 'basico',
            'nombre'      => 'Básico',
            'subtitulo'   => 'Sitio de Contacto',
            'precio'      => 3999,
            'emoji'       => '🌯',
            'demo'        => 'demo/basico.html',
            'demo_biz'    => 'Estética Bella Lux',
            'entrega'     => '5 días hábiles',
            'disponible'  => true,
            'destacado'   => false,
            'tipo_badge'  => null,
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'sku'         => 'TW-BASICO-01',
        ],

        'catalogo' => [
            'id'          => 'catalogo',
            'nombre'      => 'Catálogo',
            'subtitulo'   => 'Productos y servicios',
            'precio'      => 6499,
            'emoji'       => '🫔',
            'demo'        => 'demo/catalogo.html',
            'demo_biz'    => 'Café Molcajete',
            'entrega'     => '5–7 días hábiles',
            'disponible'  => true,
            'destacado'   => true,          // "featured" card
            'tipo_badge'  => 'hot',         // 🔥 Más vendido
            'btn_clase'   => 'btn-card-primary',
            'btn_texto'   => 'Comprar 🛒',
            'sku'         => 'TW-CATALOGO-01',
        ],

        'tienda' => [
            'id'          => 'tienda',
            'nombre'      => 'Tienda Lite',
            'subtitulo'   => 'E-commerce básico',
            'precio'      => 9999,
            'emoji'       => '🛒',
            'demo'        => 'demo/tienda.html',
            'demo_biz'    => 'Jabones Raíz',
            'entrega'     => '7–10 días hábiles',
            'disponible'  => true,
            'destacado'   => false,
            'tipo_badge'  => null,
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Comprar 🛒',
            'sku'         => 'TW-TIENDA-01',
        ],

        'pro' => [
            'id'          => 'pro',
            'nombre'      => 'Negocio Pro',
            'subtitulo'   => 'Sitio completo y profesional',
            'precio'      => 12999,
            'emoji'       => '💼',
            'demo'        => 'demo/pro.html',
            'demo_biz'    => 'Dental Sonríe',
            'entrega'     => '10–14 días hábiles',
            'disponible'  => true,
            'destacado'   => false,
            'tipo_badge'  => 'best',        // ⭐ Mejor opción
            'btn_clase'   => 'btn-card-gold',
            'btn_texto'   => 'Comprar ⭐',
            'sku'         => 'TW-PRO-01',
        ],

        'premium' => [
            'id'          => 'premium',
            'nombre'      => 'Premium',
            'subtitulo'   => 'Desarrollo a medida',
            'precio'      => 19999,
            'precio_desde'=> true,          // Mostrar "Desde $X"
            'emoji'       => '🏆',
            'demo'        => 'demo/pro.html',
            'demo_biz'    => 'Ejemplo Premium',
            'entrega'     => 'según proyecto',
            'disponible'  => true,
            'destacado'   => false,
            'tipo_badge'  => null,
            'btn_clase'   => 'btn-card-outline',
            'btn_texto'   => 'Solicitar cotización 📩',
            'sku'         => 'TW-PREMIUM-01',
        ],

    ],

];
