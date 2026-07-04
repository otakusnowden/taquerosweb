<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Menú Digital Profesional',
                'copy' => 'Dominio y hosting gratis el primer año, QR personalizado y tu '
                    . 'restaurante 100% responsive. Estrena presencia digital sin complicarte.',
                'image' => '/images/banner1.jpg',
                'cta_label' => 'Quiero mi menú digital',
                'cta_action' => 'contratar',
                'sort' => 1,
            ],
            [
                'title' => 'Crece tu restaurante',
                'copy' => 'Menú administrable, ventas por WhatsApp, reservaciones y dashboard de '
                    . 'ventas en una sola plataforma. Lleva tu restaurante al siguiente nivel.',
                'image' => '/images/banner2.jpg',
                'cta_label' => 'Hablar con un asesor',
                'cta_action' => 'whatsapp',
                'sort' => 2,
            ],
        ];

        foreach ($promotions as $data) {
            Promotion::updateOrCreate(['title' => $data['title']], $data);
        }
    }
}
