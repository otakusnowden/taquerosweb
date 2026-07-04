@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
    'schemas' => [],
    'heroDark' => false, // transparent navbar over a dark hero (home only)
])

<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1d4ed8">

    <x-seo
        :title="$title"
        :description="$description"
        :canonical="$canonical"
        :image="$image"
        :type="$type"
        :noindex="$noindex"
        :schemas="$schemas"
    />

    {{-- Favicons & manifest --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" href="/images/logo-minimal.jpeg">
    <link rel="apple-touch-icon" href="/images/logo-minimal.jpeg">
    <link rel="manifest" href="/site.webmanifest">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ensure scroll-reveal content is visible without JavaScript --}}
    <noscript>
        <style>.reveal{opacity:1 !important;transform:none !important;}</style>
    </noscript>

    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '2174449436682991');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=2174449436682991&ev=PageView&noscript=1"
    /></noscript>
</head>
<body class="min-h-screen bg-white text-slate-600 antialiased">
    {{-- Skip link for keyboard users --}}
    <a href="#main"
       class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:left-4 focus:top-4 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-white">
        Saltar al contenido
    </a>

    <x-navbar :transparent="$heroDark" />

    <main id="main">
        {{ $slot }}
    </main>

    <x-footer />

    {{-- Global conversion surfaces --}}
    <x-whatsapp-float />
    <x-modal-contratar />
</body>
</html>
