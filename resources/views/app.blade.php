<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ (Request::segment(1) != 'admin') ? 'class="dark"' : ''}}>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="shortcut icon" href="/images/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/images/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png">
        <!-- Scripts -->
        @routes
        @if(Request::segment(1) == 'admin')
            @vite(['resources/js/admin.js','resources/styles/app.scss'])
        @else
            @vite(['resources/js/app.js','resources/styles/app.scss'])
        @endif
        @inertiaHead
        {{-- @if(Request::segment(1) == 'checkout') --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-fLkyEMk66va4fTt0"></script>
        {{-- @endif --}}
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
