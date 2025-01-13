<!doctype html>
<html lang="ar" class="" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="apple-touch-icon" href="/icons/icon-512x512.png">
    <link rel="icon" type="image/png" href="/icons/icon-512x512.png"/>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="">

<div class="navbar border-b fixed top-0 w-full h-12 ">
    <div class="container flex items-center py-2">
        <div class="flex gap-x-3 items-center">

        <x-app-logo class="w-10" />
            <div class="font-bold">عبر الحدود</div>
        </div>

    </div>
</div>

<main >
    {{ $slot }}
</main>

<div class="border-t h-10 bg-indigo-800"></div>
</body>
</html>
