<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        // Placeholder structure — replace quotes/names with real clients when available.
        $testimonials = [
            [
                'name' => 'Mariana Robles',
                'role' => 'Dueña',
                'business' => 'Antojitos La Esquina',
                'quote' => 'En dos semanas teníamos página, menú y QR en las mesas. '
                    . 'Lo mejor: yo misma actualizo los precios sin depender de nadie.',
                'rating' => 5,
                'sort' => 1,
            ],
            [
                'name' => 'Carlos Méndez',
                'role' => 'Encargado',
                'business' => 'Taquería El Fogón',
                'quote' => 'Las reservas por WhatsApp dejaron de ser un caos. '
                    . 'Ahora todo llega ordenado y no perdemos clientes los fines de semana.',
                'rating' => 5,
                'sort' => 2,
            ],
            [
                'name' => 'Gabriela Sánchez',
                'role' => 'Propietaria',
                'business' => 'Café de Olla',
                'quote' => 'Por fin aparecemos en Google y la página se ve igual de bien '
                    . 'en el celular que en la compu. Se nota profesional.',
                'rating' => 5,
                'sort' => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::updateOrCreate(
                ['name' => $data['name'], 'business' => $data['business']],
                $data
            );
        }
    }
}
