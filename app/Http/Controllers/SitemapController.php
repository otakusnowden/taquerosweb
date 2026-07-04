<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('servicios'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('contacto'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach (Solution::active()->ordered()->get() as $solution) {
            $urls[] = [
                'loc' => route('solution', $solution),
                'priority' => $solution->is_flagship ? '0.9' : '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $solution->updated_at?->toAtomString(),
            ];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
