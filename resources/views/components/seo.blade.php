@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
    'schemas' => [], // array of associative arrays rendered as JSON-LD
])

@php
    $siteName = config('taquerosweb.name');
    $fullTitle = $title ? $title . ' · ' . $siteName : $siteName . ' — ' . config('taquerosweb.tagline');
    $desc = $description ?? config('taquerosweb.description');
    $canonical = $canonical ?? url()->current();
    $ogImage = url($image ?? config('taquerosweb.og_image'));
    $locale = config('taquerosweb.locale', 'es_MX');
@endphp

<title>{{ $fullTitle }}</title>
<meta name="description" content="{{ $desc }}">
<link rel="canonical" href="{{ $canonical }}">
@if ($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow, max-image-preview:large">
@endif

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:title" content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $desc }}">
<meta name="twitter:image" content="{{ $ogImage }}">

{{-- Global JSON-LD: Organization + WebSite --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteName,
    'url' => url('/'),
    'logo' => url('/images/logo-a-color.jpeg'),
    'description' => config('taquerosweb.description'),
    'sameAs' => array_values(\App\Support\Site::socials()),
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'contactType' => 'sales',
        'email' => config('taquerosweb.email'),
        'areaServed' => config('taquerosweb.country'),
        'availableLanguage' => ['es'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- Page-specific JSON-LD --}}
@foreach ($schemas as $schema)
    <script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endforeach
