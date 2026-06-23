<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Vision Concept Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Google Fonts & FontAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f3ff;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #4c1d95 0%, #2e1065 100%);
            color: #ffffff;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(46, 16, 101, 0.15);
        }
        .sidebar-header {
            padding: 30px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar-item a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #ddd6fe;
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }
        .sidebar-item a i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            color: #a78bfa;
        }
        .sidebar-item a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .sidebar-item.active a {
            background-color: #ffffff;
            color: #4c1d95 !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .sidebar-item.active a i {
            color: #4c1d95 !important;
        }
        /* main content on the right */
        .main-content {
            flex-grow: 1;
            background-color: #ffffff;
            padding: 40px 50px;
            min-width: 0;
        }
        /* input box */
        .form-control {
            border: 1px solid #e2e8f0 !important;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #a78bfa !important;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.15) !important;
            background-color: #fff !important;
        }
        .brand-logo-text {
            color: #4c1d95 !important;
            font-size: 2.4rem !important;
            font-weight: 800 !important;
            letter-spacing: -0.7px;
            margin-bottom: 2px;
        }
        .brand-subtitle-text {
            color: #a78bfa !important;
            font-size: 0.9rem !important;
            letter-spacing: 2px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-login {
            background: linear-gradient(135deg, #4c1d95 0%, #2e1065 100%);
            transition: all 0.15s ease-in-out;
        }


        .btn-login:hover {
            background: linear-gradient(135deg, #5b21b6 0%, #3b0764 100%);
        }


        .btn-login:active {
            background: #1e1b4b !important;
            transform: scale(0.98);
            box-shadow: inset 0 3px 5px rgba(0,0,0,0.3) !important;
        }

        .btn-teal {
            background-color: #0d9488;
            transition: all 0.15s ease-in-out;
        }


        .btn-teal:hover {
            background-color: #0f766e;
        }

        .btn-teal:active {
            background-color: #115e59 !important;
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    @if(request()->is('login') || request()->is('/'))
        <div class="d-flex align-items-center justify-content-center min-vh-100" style="background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%); padding: 15px;">
            <div class="bg-white p-5 rounded-4 shadow-sm border" style="width: 100%; max-width: 440px;">

                <div class="text-center mb-4">
                    <img src="{{ asset('images/visionConceptLogo.png') }}" alt="Logo" class="img-fluid mb-2" style="max-height: 105px;">
                    <h1 class="brand-logo-text">Vision Concept</h1>
                    <span class="brand-subtitle-text d-block">Management System</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    @else
        <!-- sideBar: dashBoard -->
        <div class="dashboard-container">

            <div class="sidebar">
                <div class="sidebar-header d-flex align-items-center gap-3">
                    <img src="{{ asset('images/visionConceptLogo.png') }}" alt="Logo" class="img-fluid" style="max-height: 35px; filter: brightness(0) invert(1);">
                    <div>
                        <h6 class="fw-bold m-0" style="letter-spacing: 0.5px;">Vision Concept</h6>
                        <small style="color: #a78bfa; font-size: 0.7rem;" class="text-uppercase fw-semibold">Management System</small>
                    </div>
                </div>

                <ul class="sidebar-menu">
                    <!-- Dashboard activation -->
                    <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('/dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#"><i class="fa-solid fa-users"></i> Customer Details</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
                    </li>
                    <!-- Eye Condition's menu activation -->
                    <li class="sidebar-item {{ request()->is('condition/create') || request()->is('condition') ? 'active' : '' }}">
                        <a href="{{ route('condition.create') }}"><i class="fa-solid fa-eye"></i> Eye Conditions</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#"><i class="fa-solid fa-cart-plus"></i> Create Order</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#"><i class="fa-solid fa-receipt"></i> Manage Orders</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#"><i class="fa-solid fa-chart-line"></i> Reports</a>
                    </li>
                    <li class="sidebar-item mt-5">
                        <a href="#" style="color: #fca5a5;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    </li>
                </ul>
            </div>

            <div class="main-content">
                {{ $slot }}
            </div>

        </div>
    @endif

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6Rzs3gYMHg31KmShNmPv6YlOm8v1W608SB4LO60kJLN0N9hn65z53B2RKEX6437" crossorigin="anonymous"></script>
</body>
</html>
