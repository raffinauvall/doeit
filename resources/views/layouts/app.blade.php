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
        :root {
            --sidebar-width: 280px;
        }

        body {
            display: flex;
            background-color: #f8f9fa;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fff;
            padding-top: 1rem;
            box-shadow: 1px 0 13px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
            z-index: 1050;
        }

        .sidebar a {
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #198754;
            transition: 0.2s;
            font-size: 18px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #198754;
            color: #fff;
            border-radius: 10px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease-in-out;
            padding: 20px;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border: 0;
            border-radius: 15px;

        }

        /* --- MOBILE --- */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 80%;
                max-width: 300px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar .menu-toggle {
                display: inline-block;
            }

        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #198754;
            cursor: pointer;
            display: none;
        }

        /* overlay ketika sidebar dibuka */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1049;
        }

        .overlay.show {
            display: block;
        }

        .dropdown-menu {
            padding: 0 !important;
        }

        .dropdown-divider {
            margin: 0 !important;
            padding: 0 !important;
        }

        .item-logout:hover {
            border-radius: 5px;
            background-color: #dc3545;
            color: white !important;
            font-weight: 500
        }
    </style>
</head>

<body class="ff-sfRegular text-dark">

    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar">
        <img src="{{ asset('images/logo_doeit.png') }}" class="mb-4" alt="Logo" width="100">

        <a href="{{ route('dashboard') }}"
            class="d-flex align-items-center mb-3 sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <img src="{{ asset('images/' . (request()->is('dashboard') ? 'dashboard_icon.png' : 'dashboard_green.png')) }}"
                data-default="{{ asset('images/dashboard_green.png') }}"
                data-hover="{{ asset('images/dashboard_icon.png') }}" width="30" class="me-4 sidebar-icon"
                alt="">
            <span class="fs-4">Dashboard</span>
        </a>

        <a href="{{ route('transactions.index') }}"
            class="d-flex align-items-center mb-3 sidebar-link {{ request()->is('transactions*') ? 'active' : '' }}">
            <img src="{{ asset('images/' . (request()->is('transactions*') ? 'trans_icon.png' : 'trans_green.png')) }}"
                data-default="{{ asset('images/trans_green.png') }}" data-hover="{{ asset('images/trans_icon.png') }}"
                width="30" class="me-4 sidebar-icon" alt="">
            <span class="fs-4">Transaction</span>
        </a>

        <a href="{{ route('goals.index') }}"
            class="d-flex align-items-center sidebar-link {{ request()->is('goals*') ? 'active' : '' }}">
            <img src="{{ asset('images/' . (request()->is('goals*') ? 'goal_icon.png' : 'goal_green.png')) }}"
                data-default="{{ asset('images/goal_green.png') }}" data-hover="{{ asset('images/goal_icon.png') }}"
                width="30" class="me-4 sidebar-icon" alt="">
            <span class="fs-4">Goal</span>
        </a>
    </div>


    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <div class="main-content container-fluid">
        <!-- Topbar -->
        <div class="topbar bg-white border-bottom d-flex justify-content-between align-items-center px-3 py-2">
            <!-- Kiri: Toggle sidebar (muncul hanya di mobile) -->
            <button class="btn btn-outline-success d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- Tengah: Tanggal + Jam -->
            <div class="flex-grow-1 text-center text-md-start">
                <span id="currentTime"
                    class="text-dark ff-sfSemibold fs-5">{{ now()->format('d F Y, H:i:s A') }}</span>
            </div>

            @php
                $avatar =
                    Auth::user()->gender === 'male'
                        ? asset('images/avatar_male.png')
                        : asset('images/avatar_female.png');
            @endphp
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                        id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $avatar }}" alt="User" width="42" height="42"
                            class="rounded-circle d-inline d-md-none me-2 bg-success">

                        <span class="d-none d-md-inline fs-6 ff-sfSemibold">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li>
                            <a class="dropdown-item" href="{{ route('auth.editPassword') }}">
                                <i class="bi bi-key me-2"></i> Change Password
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">@csrf
                                <button class="dropdown-item item-logout text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="mt-4">
            @yield('content')
        </div>
    </div>

    <script>
        // Toggle Sidebar (Mobile Version)
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Realtime Clock and Date
        function updateClock() {
            const now = new Date();

            // Format tanggal & waktu manual
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };

            const formatted = now.toLocaleString('en-GB', options).replace(',', '');
            document.getElementById('currentTime').textContent = formatted;
        }

        // update setiap 1 detik
        setInterval(updateClock, 1000);

        // panggil sekali pas awal load
        updateClock();
        document.querySelectorAll('.sidebar-link').forEach(link => {
            const img = link.querySelector('.sidebar-icon');

            if (!img) return;

            const defaultSrc = img.getAttribute('data-default');
            const hoverSrc = img.getAttribute('data-hover');

            link.addEventListener('mouseenter', () => {
                if (!link.classList.contains('active')) {
                    img.src = hoverSrc;
                }
            });

            link.addEventListener('mouseleave', () => {
                if (!link.classList.contains('active')) {
                    img.src = defaultSrc;
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
