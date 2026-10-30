<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Super Admin Panel')</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #fff;
            color: #111;
        }

        .panel-title {
            color: #ff7900;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .dashboard {
            background: #e9edf1;
            border: 2px solid #222;
            padding: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #222;
            border-bottom: 2px solid #ff9800;
            padding: 15px;
            margin-bottom: 25px;
        }

        .navbar a,
        .link-button {
            color: #111;
            text-decoration: none;
            margin-right: 15px;
        }

        .section-title {
            color: #0066cc;
            margin-top: 25px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 2px solid #222;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #999;
            overflow-wrap: anywhere;
        }

        th {
            font-size: 12px;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            background: #cce5ff;
            border: 1px solid #007bff;
            color: #0056b3;
            text-decoration: none;
            cursor: pointer;
        }

        .pagination {
            margin: 15px 0 30px;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination a,
        .pagination span {
            margin-right: 5px;
        }

        .alert {
            padding: 10px;
            background: #d4edda;
            margin-bottom: 15px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        select, input {
            padding: 8px;
            max-width: 100%;
        }

        @media (max-width: 700px) {
            body {
                padding: 10px;
            }

            .dashboard {
                padding: 10px;
            }

            .table-wrap {
                overflow-x: auto;
            }

            table {
                min-width: 650px;
            }

            .navbar,
            .section-header {
                gap: 10px;
                flex-wrap: wrap;
            }
        }

        /* Clean Pagination Styling Matching Dashboard Theme */
        .pagination {
            display: flex;
            align-items: center;
            margin: 15px 0 25px;
        }

        .pagination nav {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Hide the summary text (e.g., "Showing 1 to 15 of 50 results") if desired, or let it flow */
        /* .pagination nav > div:first-child { display: none; } */

        .pagination div.flex.items-center.justify-between {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination span[aria-current="page"] > span,
        .pagination a,
        .pagination span[aria-disabled="true"] {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid #007bff;
            background: #fff;
            color: #0056b3;
            text-decoration: none;
            font-size: 14px;
            border-radius: 2px;
        }

        /* Active Page Styling */
        .pagination span[aria-current="page"] > span {
            background: #cce5ff;
            font-weight: bold;
        }

        /* Hover effect for clickable links */
        .pagination a:hover {
            background: #e6f2ff;
        }

        /* Disabled previous/next buttons */
        .pagination span[aria-disabled="true"] {
            background: #f1f3f5;
            border-color: #ccc;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="panel-title">SUPER ADMIN PANEL</div>

    <div class="dashboard">

        <h3 style="color:red;">
            Super Admin Dashboard
        </h3>

        <div class="navbar">
            <div>
                <a href="{{ route('superadmin.dashboard') }}">
                    Dashboard
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn">
                    Logout →
                </button>
            </form>
        </div>

        @yield('content')

    </div>
 @stack('scripts')
</body>
</html>