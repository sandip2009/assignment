<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'URL Shortener')</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0 10px 15px;
            background: #a5d5f5;
            color: #111;
            font-family: monospace;
        }

        .page-title {
            margin: 0;
            padding: 0 0 5px;
            color: #ff3333;
            font-size: 22px;
            font-weight: normal;
        }

        .dashboard {
            width: 100%;
            min-height: 290px;
            border: 2px solid #222;
            background: #e9edf1;
        }

        .navbar {
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 8px 20px;
            border-bottom: 2px solid #ff9800;
        }

        .nav-left {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        .logo {
            padding: 5px 10px;
            border: 2px solid #ff9800;
            background: #fff;
            color: #ff9800;
            font-size: 12px;
            text-decoration: none;
        }

        .nav-title {
            font-size: 16px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .nav-links a {
            color: #111;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            color: #0875df;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            border: 0;
            background: transparent;
            color: #111;
            font-family: monospace;
            font-size: 15px;
            cursor: pointer;
        }

        .logout-btn:hover {
            color: #0875df;
        }

        .content {
            padding: 26px 20px 22px;
        }

        .section-title {
            margin: 0 0 13px;
            color: #0875df;
            font-size: 16px;
            font-weight: normal;
        }

        .card {
            margin-bottom: 20px;
            padding: 18px;
            border: 2px solid #222;
            background: #fff;
        }

        .btn {
            display: inline-block;
            padding: 9px 15px;
            border: 1px solid #0875df;
            background: #a5d5f5;
            color: #064f9e;
            text-decoration: none;
            cursor: pointer;
            font-family: monospace;
            font-size: 14px;
        }

        .btn:hover {
            background: #82c4f5;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 2px solid #222;
            border-radius: 0;
            background: #fff;
            font-family: monospace;
        }

        .alert {
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid;
        }

        .alert-success {
            border-color: #198754;
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-error {
            border-color: #dc3545;
            background: #f8d7da;
            color: #842029;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .common-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .common-table th,
        .common-table td {
            padding: 10px;
            border: 1px solid #d1d5db;
            text-align: left;
        }

        .common-table th {
            background: #e9edf1;
        }

        @media (max-width: 650px) {
            .navbar {
                padding: 10px;
                align-items: flex-start;
            }

            .nav-left {
                gap: 10px;
            }

            .content {
                padding: 18px 12px;
            }

            .card {
                padding: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <h1 class="page-title">@yield('brand-title', 'Client Dashboard')</h1>

    <div class="dashboard">

        {{-- Common Header --}}
        <header class="navbar">

            <div class="nav-left">
                <a href="{{ url('/') }}" class="logo">&gt;URL&lt;</a>

                <span class="nav-title">
                    @yield('header-title', 'Dashboard')
                </span>

               {{-- @auth
                    <nav class="nav-links">

                        @if (auth()->user()->role === 'Admin')
                            <a href="{{ route('client.dashboard') }}">
                                Dashboard
                            </a>
                            <a href="{{ route('members.create') }}">
                                Invite Member
                            </a>

                        @elseif (auth()->user()->role === 'Member')
                            <a href="{{ route('member.dashboard') }}">
                                Dashboard
                            </a>
                        @endif

                    </nav>
                @endauth --}}
            </div>

            @auth
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="logout-form"
                >
                    @csrf

                    <button type="submit" class="logout-btn btn">
                        Logout &rarr;
                    </button>
                </form>
            @endauth

        </header>

        {{-- Common Success / Error Messages --}}
        <main class="content">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Individual Page Content --}}
            @yield('content')

        </main>

    </div>

    @stack('scripts')

</body>
</html>
