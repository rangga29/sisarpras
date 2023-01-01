<!DOCTYPE html>
<html lang="en" class="semi-dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" />

    <link rel="stylesheet" href="{{ asset('plugins/simplebar/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/metismenu/css/metisMenu.min.css') }}">
    @stack('css-plugins')

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-extended.css') }}" />
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/icons.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/pace.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/semi-dark.css') }}">

    <title>SISARPRAS SMP Santa Ursula Bandung</title>
</head>

<body>
    @include('sweetalert::alert')
    <div class="wrapper">
        @include('components.header')
        @include('components.sidebar')
        <div class="page-content">
            {{ $slot }}
        </div>
        <div class="overlay nav-toggle-icon"></div>
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>
    @stack('js-plugins')
    @vite(['resources/js/app.js'])
    @stack('js-scripts')
</body>

</html>
