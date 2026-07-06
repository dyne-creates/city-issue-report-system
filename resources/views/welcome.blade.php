<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KailianFix</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    @fonts

    <!-- Bootstrap 5 -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6d28d9;
            --primary-dark: #5b21b6;
            --primary-light: #ede9fe;

            --accent: #c4b5fd;

            --text-light: rgba(255, 255, 255, .88);

            --shadow: 0 25px 60px rgba(15, 23, 42, .25);
        }

        body {
            font-family: "Instrument Sans", sans-serif;
            background: #f8fafc;
            color: #1f2937;
        }

        /* HERO */

        .hero {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0 4rem;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: url("{{ asset('baguio-cover.jpg') }}") center/cover no-repeat;
            z-index: -2;
        }

        .hero-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background:linear-gradient(110deg,
                    rgba(15, 23, 42, .90) 0%,
                    rgba(67, 56, 202, .65) 45%,
                    rgba(124, 58, 237, .35) 100%);
        }

        /* NAVBAR */

        .glass-navbar {
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 99px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .15);
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .nav-link {
            color: rgba(255, 255, 255, .92) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        /* HERO TEXT */

        .hero-title {
            font-size: clamp(3rem, 6vw, 5rem);
            line-height: 1.05;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1.5rem;
        }

        .text-accent {
            color: var(--accent);
        }

        .hero-text {
            color: var(--text-light);
            font-size: 1.08rem;
            line-height: 1.8;
            max-width: 560px;
        }

        /* BUTTONS */

        .btn-primary {
            border: none;
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            box-shadow: 0 10px 25px rgba(109, 40, 217, .25);
        }

        .btn-primary:hover {

            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        .btn-light {

            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        }

        /* PHONE PLACEHOLDER */

        .phone-frame {
            width: 320px;
            height: 640px;
            background: #101827;
            border-radius: 42px;
            padding: 12px;
            position: relative;
            margin: auto;
            box-shadow: 0 35px 80px rgba(0, 0, 0, .35), inset 0 0 0 2px rgba(255, 255, 255, .06);
        }

        .phone-frame::before {
            content: "";
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 24px;
            background: #000;
            border-radius: 20px;
            z-index: 100;
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 32px;
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        /* APP UI */

        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid #edf2f7;
        }

        .app-header h6 {
            font-weight: 700;
            margin-bottom: 2px;
        }

        .report-image {
            height: 210px;
            overflow: hidden;
        }

        .report-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .app-content {
            padding: 20px;
            flex: 1;
        }

        .info-chip {
            display: flex;
            align-items: center;
            gap: .6rem;
            background: #f4f4f5;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 12px;
            font-size: .9rem;
            color: #475569;
        }
        .info-chip i {
            color: var(--primary);
        }

        .app-content .btn {
            margin-top: 1rem;
        }

        /* RESPONSIVE */

        @media (max-width:991.98px) {

            .hero {
                min-height: auto;
                text-align: center;
                padding: 2rem 0 3rem;
            }

            .hero-text {
                margin: auto;
            }

            .hero .d-flex {
                justify-content: center;
            }

            .phone-frame {
                margin-top: 2.5rem;
            }
        }

        @media (max-width:767.98px) {

            .hero-title {
                font-size: 2.6rem;
            }

            .btn-lg {
                width: 100%;
            }

            .stat-card {
                text-align: center;
            }

            .phone-frame {
                width: 290px;
                height: 590px;
            }

            .navbar-collapse {
                margin-top: 1rem;
            }

            .navbar-nav {
                gap: .75rem;
            }
        }
    </style>
</head>

<body>

    <!--  HERO  -->
    <section class="hero py-4 py-lg-5">

        <div class="hero-bg"></div>

        <div class="container">

            <!--  NAVBAR  -->

            @if (Route::has('login'))
                <nav class="navbar navbar-expand-lg glass-navbar mb-5 px-5 py-3">

                    <a class="navbar-brand d-flex align-items-center fw-bold text-white" href="{{ url('/') }}">
                        <img src="{{ asset('logo.png') }}" alt="Logo" width="42" class="me-2">
                        KailianFix
                    </a>

                    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#mainNav">

                        <span class="navbar-toggler-icon"></span>

                    </button>

                    <div class="collapse navbar-collapse" id="mainNav">

                        <ul class="navbar-nav ms-auto align-items-lg-center">

                            @auth

                                <li class="nav-item">
                                    <a href="{{ url('/dashboard') }}" class="btn btn-primary rounded-pill px-4">
                                        Dashboard
                                    </a>
                                </li>

                            @else

                                <li class="nav-item me-lg-2">
                                    <a href="{{ route('login') }}" class="nav-link text-white fw-semibold">
                                        Log in
                                    </a>
                                </li>

                                @if(Route::has('register'))

                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">
                                            Register
                                        </a>
                                    </li>

                                @endif

                            @endauth

                        </ul>

                    </div>

                </nav>
            @endif

            <!-- ================= HERO ================= -->

            <div class="row align-items-center gy-5">

                <!-- LEFT -->

                <div class="col-lg-6">

                    <h1 class="hero-title text-white">

                        Empowering Baguio, <span class="text-accent">one report</span> at a time.

                    </h1>

                    <p class="hero-text mt-4">

                        KailianFix is a modern civic reporting platform rooted in the
                        Cordilleran spirit of <strong>binnadang</strong>. Report
                        infrastructure, environmental, and neighborhood concerns directly
                        to the proper city department.

                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4">

                        @auth

                            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg rounded-pill px-4">
                                Open Dashboard
                            </a>

                        @else

                            <a href="{{ route('register') }}" class="btn btn-light btn-md rounded-pill px-3">
                                Create Account
                            </a>

                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-md rounded-pill px-3">
                                Track Reports
                            </a>

                        @endauth

                    </div>


                </div>

                <!-- RIGHT -->

                <div class="col-lg-6 d-flex justify-content-center">

                    <div class="phone-frame">

                        <div class="phone-notch"></div>

                        <div class="phone-screen">

                            <!-- App Header -->
                            <div class="app-header mt-3">

                                <div>
                                    <h6 class="fw-bold mb-0">New Report</h6>
                                    <small class="text-muted">Citizen Portal</small>
                                </div>

                                <span class="badge bg-warning text-dark rounded-pill">
                                    Draft
                                </span>

                            </div>

                            <!-- Photo -->
                            <div class="report-image">
                                <img src="{{ asset('baguio-street.jpg') }}" class="img-fluid">
                            </div>

                            <!-- Report Info -->

                            <div class="p-3">

                                <h6 class="fw-bold">
                                    Broken Streetlamp
                                </h6>

                                <small class="text-muted d-block mb-2">
                                    Barangay Irisan
                                </small>

                                <div class="info-chip">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Exact Location
                                </div>

                                <div class="info-chip">
                                    <i class="bi bi-tools"></i>
                                    Infrastructure
                                </div>

                                <div class="info-chip">
                                    <i class="bi bi-camera-fill"></i>
                                    Photo Attached
                                </div>

                                <button class="btn btn-primary w-100 rounded-pill mt-4">
                                    Submit Report
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
</body>

</html>