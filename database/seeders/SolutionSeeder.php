<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $solutions = [
            [
                'slug' => 'menu-digital',
                'name' => 'Menú Digital',
                'tagline' => 'Tu restaurante profesional en internet, listo para vender.',
                'summary' => 'Una página completa para tu restaurante con menú administrable, '
                    . 'reservaciones, WhatsApp y código QR. Todo lo que necesitas para que te '
                    . 'encuentren y te pidan, sin complicaciones técnicas.',
                'description' => 'El Menú Digital no es solo un PDF con un QR. Es la presencia '
                    . 'digital completa de tu restaurante: una página rápida, bonita y fácil de '
                    . 'actualizar, pensada para convertir a quien te busca en un cliente que reserva '
                    . 'o pide. Tú controlas tu menú, tus precios y tus promociones desde un panel '
                    . 'simple; nosotros nos encargamos de que todo se vea profesional y cargue al instante.',
                'icon' => 'utensils',
                'badge' => 'Producto estrella',
                'status' => 'active',
                'is_flagship' => true,
                'sort' => 1,
                'includes' => [
                    'Página profesional para tu restaurante',
                    'Menú 100% administrable por ti',
                    'Sistema de reservaciones',
                    'Ubicación con Google Maps',
                    'Botón e integración con WhatsApp',
                    'Código QR personalizado y su diseño',
                    'Manual de identidad de tu marca',
                    'Dominio gratis el primer año',
                    'Hosting gratis el primer año',
                    'Promociones administrables',
                    'Diseño responsive (se ve bien en todo)',
                    'Panel de administración incluido',
                ],
                'premium' => [
                    ['icon' => 'cart', 'title' => 'Carrito de compras', 'text' => 'Convierte tu menú en una tienda y recibe pedidos directos.'],
                    ['icon' => 'credit-card', 'title' => 'Pagos con Mercado Pago', 'text' => 'Cobra en línea de forma segura, sin fricción.'],
                    ['icon' => 'tag', 'title' => 'Cupones y descuentos', 'text' => 'Lanza ofertas y mide cuáles te traen más clientes.'],
                    ['icon' => 'chart', 'title' => 'Dashboard de KPIs', 'text' => 'Visitas, pedidos y reservas en un panel claro.'],
                    ['icon' => 'sparkles', 'title' => 'Reportes', 'text' => 'Entiende qué platillos venden y cuándo vende más tu negocio.'],
                    ['icon' => 'rocket', 'title' => 'Pedidos en línea', 'text' => 'Recibe y organiza pedidos sin saturar tu WhatsApp.'],
                ],
                'features' => [
                    ['icon' => 'utensils', 'title' => 'Menú que actualizas en minutos', 'text' => 'Cambia platillos, precios y fotos cuando quieras. Sin llamar a nadie, sin esperar.'],
                    ['icon' => 'calendar', 'title' => 'Reservaciones sin fricción', 'text' => 'Tus clientes apartan mesa desde su teléfono y tú recibes el aviso al instante.'],
                    ['icon' => 'qr-code', 'title' => 'Código QR a tu medida', 'text' => 'Un QR con el diseño de tu marca para tus mesas, ventanas y redes.'],
                    ['icon' => 'map-pin', 'title' => 'Te encuentran fácil', 'text' => 'Ubicación con Google Maps integrada para que lleguen sin perderse.'],
                    ['icon' => 'globe', 'title' => 'Dominio y hosting incluidos', 'text' => 'El primer año va por nuestra cuenta. Tu marca, con dominio propio.'],
                    ['icon' => 'palette', 'title' => 'Imagen profesional', 'text' => 'Diseño cuidado y un manual de identidad para que te vean como lo que eres: serio.'],
                ],
            ],
            [
                'slug' => 'pagina-web-restaurantes',
                'name' => 'Páginas Web a tu medida',
                'tagline' => 'Un sitio profesional diseñado específicamente para tu marca.',
                'summary' => 'Una página web completa y personalizada con secciones, galería, '
                    . 'historias, testimonios y mucho más. La presencia digital que tu restaurante merece.',
                'icon' => 'globe',
                'badge' => null,
                'status' => 'active',
                'is_flagship' => false,
                'sort' => 2,
                'includes' => [
                    'Diseño único y personalizado para tu marca',
                    'Galería de fotos de tu restaurante',
                    'Secciones editables',
                    'Menú integrado',
                    'Testimonios de clientes',
                    'Formulario de contacto',
                    'Integración con WhatsApp',
                    'SEO optimizado para Google',
                    'Responsive (se ve bien en todo)',
                    'Velocidad optimizada',
                ],
                'premium' => null,
                'features' => null,
            ],
            [
                'slug' => 'chatbot-inteligente',
                'name' => 'Chatbot inteligente',
                'tagline' => 'Atiende clientes 24/7 automáticamente, sin que pierdas una venta.',
                'summary' => 'Un asistente inteligente que responde preguntas, toma reservaciones '
                    . 'y recibe pedidos en WhatsApp. Tus clientes abiertos a cualquier hora.',
                'icon' => 'sparkles',
                'status' => 'active',
                'sort' => 3,
                'includes' => [
                    'Respuestas automáticas 24/7',
                    'Toma de reservaciones',
                    'Responde preguntas frecuentes',
                    'Recibe pedidos',
                    'Integrado con WhatsApp',
                    'Fácil de configurar',
                    'Aumenta tus ventas sin esfuerzo',
                    'Análisis de conversaciones',
                ],
                'premium' => null,
                'features' => null,
            ],
            [
                'slug' => 'pedidos-en-linea',
                'name' => 'Pedidos en Línea',
                'tagline' => 'Recibe pedidos directos y deja de pagar comisiones altas.',
                'summary' => 'Tu propio canal de pedidos, sin intermediarios que se queden con '
                    . 'tu ganancia. El cliente es tuyo, los datos también.',
                'icon' => 'cart',
                'status' => 'active',
                'sort' => 4,
                'includes' => [
                    'Sistema de pedidos integrado',
                    'Sin comisiones de plataforma',
                    'Notificaciones en tiempo real',
                    'Gestión de entregas',
                    'Seguimiento de pedidos',
                    'Historial de clientes',
                    'Integración con cocina',
                    'Reportes de ventas',
                ],
                'premium' => null,
                'features' => null,
            ],
            [
                'slug' => 'crm-restaurantes',
                'name' => 'CRM para Restaurantes',
                'tagline' => 'Convierte clientes de una vez en clientes de siempre.',
                'summary' => 'Conoce a tus comensales, recuérdales que existes y haz que '
                    . 'regresen. La fidelización empieza por los datos.',
                'icon' => 'users',
                'status' => 'active',
                'sort' => 5,
                'includes' => [
                    'Base de datos de clientes',
                    'Historial de compras',
                    'Segmentación de clientes',
                    'Campañas personalizadas',
                    'Recordatorios automáticos',
                    'Programa de lealtad',
                    'Análisis de comportamiento',
                    'Integración con WhatsApp',
                ],
                'premium' => null,
                'features' => null,
            ],
            [
                'slug' => 'marketing-promociones',
                'name' => 'Marketing y Diseño de promociones',
                'tagline' => 'Campañas que traen clientes nuevos y repiten los antiguos.',
                'summary' => 'Diseñamos y ejecutamos promociones que venden. Desde descuentos inteligentes '
                    . 'hasta estrategias de fidelización que funcionan.',
                'icon' => 'rocket',
                'status' => 'active',
                'sort' => 6,
                'includes' => [
                    'Diseño de campañas promocionales',
                    'Email marketing automatizado',
                    'Gestión de redes sociales',
                    'Publicidad digital',
                    'Análisis de resultados',
                    'Estrategia de fidelización',
                    'Cupones y descuentos',
                    'Reportes de rendimiento',
                ],
                'premium' => null,
                'features' => null,
            ],
        ];

        foreach ($solutions as $data) {
            Solution::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
