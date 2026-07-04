<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => '¿Necesito conocimientos técnicos para usarlo?',
                'answer' => 'No. Nosotros dejamos todo listo y funcionando. Tú solo entras a un '
                    . 'panel sencillo para cambiar platillos, precios o promociones cuando quieras. '
                    . 'Si algo se te complica, te ayudamos por WhatsApp.',
                'sort' => 1,
            ],
            [
                'question' => '¿El dominio y la página son míos?',
                'answer' => 'Sí. El dominio se registra a nombre de tu negocio y la página es tuya. '
                    . 'El primer año de dominio y hosting va incluido sin costo.',
                'sort' => 2,
            ],
            [
                'question' => '¿Puedo actualizar mi menú yo mismo?',
                'answer' => 'Claro. Cambias platillos, precios, fotos y promociones desde tu panel '
                    . 'en minutos, las veces que quieras. Sin llamadas, sin esperas y sin costos extra.',
                'sort' => 3,
            ],
            [
                'question' => '¿Cuánto tarda en estar lista mi página?',
                'answer' => 'La mayoría de los menús digitales quedan listos en pocos días una vez '
                    . 'que nos compartes tu información. Te avisamos en cada paso.',
                'sort' => 4,
            ],
            [
                'question' => '¿Funciona bien en celular?',
                'answer' => 'Tu página está diseñada para verse y funcionar perfecto en celular, '
                    . 'tablet y computadora. La mayoría de tus clientes te verán desde el teléfono, '
                    . 'y para ellos está optimizada.',
                'sort' => 5,
            ],
            [
                'question' => '¿Qué pasa si después quiero más funciones?',
                'answer' => 'La plataforma crece contigo. Puedes sumar módulos como carrito, pagos en '
                    . 'línea, cupones o reportes cuando los necesites, sin rehacer tu página.',
                'sort' => 6,
            ],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(['question' => $data['question']], $data);
        }
    }
}
