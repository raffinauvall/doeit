<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'DoeIt') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
        
        }
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 1rem;
            box-shadow: 1px 0px 13px 0px rgba(0,0,0,0.54);
-webkit-box-shadow: 1px 0px 13px 0px rgba(0,0,0,0.54);
-moz-box-shadow: 1px 0px 13px 0px rgba(0,0,0,0.54);
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            transition: 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }
        .main-content {
            margin-left: 230px;
            padding: 20px;
        }
        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body class="text-white bg-dark ff-sfRegular">

    <!-- Sidebar -->
    <div class="sidebar bg-dark text-center p-3">
        <img src="{{ asset('images/logo_doeit.png') }}" class="text-center" alt="Logo" width="100">
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }} text-start bg-success rounded-4 ff-sfRegular fw-bold fs-5 p-2 ps-3">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('transactions.index') }}" class="{{ request()->is('transactions*') ? 'active' : '' }} text-start">
            <i class="bi bi-clock-history me-2"></i> Riwayat
        </a>
        <a href="{{ route('goals.index') }}" class="{{ request()->is('goals*') ? 'active' : '' }} text-start">
            <i class="bi bi-tags me-2"></i> Kategori
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-4 text-center">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
        

    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar bg-dark text-white border-0">
            <h2 class="mb-0 text-white ff-sfBold">Hello, {{ Auth::user()->name }}</h2>
            <span class="text-white">{{ now()->format('d M Y') }}</span>
        </div>

        <!-- Page Content -->
        <div class="mt-4">
            @yield('content')
        </div>
    </div>
   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
