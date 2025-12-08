<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Pemilihan Mobil MABAC')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navbar')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('layouts.footer')
</body>
</html>
