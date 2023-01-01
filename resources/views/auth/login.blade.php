<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}" />

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-extended.css') }}" />
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/icons.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/pace.min.css') }}">

    <title>SISARPRAS SMP Santa Ursula Bandung</title>
</head>

<body class="bg-reset-password">
    @include('sweetalert::alert')
    <div class="wrapper">
        <main class="authentication-content">
            <div class="container-fluid">
                <div class="authentication-card">
                    <div class="card shadow rounded-5 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-flex align-items-center justify-content-center border-end">
                                <img src="{{ asset('images/logoServiam.png') }}" class="img-fluid" alt="">
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body p-4 p-sm-5">
                                    <h4 class="card-title text-center text-uppercase fw-bolder">SISARPRAS URSULA</h4>
                                    <h5 class="card-title text-center text-uppercase fw-bolder mb-4">Login Administrator</h5>
                                    @if(session()->has('errors'))
                                    <div class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-danger">{{ session('errors') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <form class="form-body" action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="username" class="form-label">Username</label>
                                                <div class="ms-auto position-relative">
                                                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                        <i class="bi bi-person-fill"></i>
                                                    </div>
                                                    <input type="text" class="form-control radius-30 ps-5" name="username" placeholder="Enter Username"
                                                        autocomplete="off" required />
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="ms-auto position-relative">
                                                    <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                        <i class="bi bi-lock-fill"></i>
                                                    </div>
                                                    <input type="password" class="form-control radius-30 ps-5" name="password" placeholder="Enter Password"
                                                        autocomplete="off" required />
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="remember" />
                                                    <label class="form-check-label" for="remember">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid gap-3">
                                                    <button type="submit" class="btn btn-primary radius-30">LOGIN</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>
    @vite(['resources/js/app.js'])
</body>
