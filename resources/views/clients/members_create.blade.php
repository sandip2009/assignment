@extends('clients.layouts.app')

@section('title', 'Invite New Client')
@section('brand-title', 'Client Admin Dashboard')
@section('header-title', 'Invite New Members')

@section('content')

    <form method="POST"action="{{ route('members.store') }}">
        @csrf
        
        <div>
            <label for="name">Name</label><br>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="email">Email</label><br>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        {{-- Role --}}
        <div class="form-group">
            <label for="role">Role</label>

            <select id="role" name="role" >
                <option value="" >Select Role</option>
                <option value="Member" >Member</option>
                <option value="Admin" >Admin</option>
            </select>
        </div>

        <button class="btn" type="submit">Send Invitation</button>

        <a href="{{ route('client.dashboard') }}" class="btn">
            Cancel
        </a>

    </form>

@endsection


@push('styles')
    <style>
        /* Your existing styles remain untouched */
        * { box-sizing: border-box; }
        body { margin: 0; padding: 25px; background: #fff; color: #222; font-family: Arial, sans-serif; }
        .page-title { color: #ff7900; font-size: 22px; margin-bottom: 15px; }
        .dashboard { background: #e9edf1; border: 2px solid #222; padding: 20px; }
        .company-name { color: #ff4b4b; }
        .section-title { color: #0066cc; margin-top: 25px; }
        .section-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
        .btn { display: inline-block; padding: 8px 14px; border: 1px solid #007bff; background: #cce5ff; color: #0056b3; text-decoration: none; cursor: pointer; }
        .table-wrap { width: 100%; overflow-x: auto; }
        table { width: 100%; min-width: 650px; border-collapse: collapse; background: #fff; border: 2px solid #333; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #aaa; overflow-wrap: anywhere; }
        th { color: #555; font-size: 12px; }
        .pagination { margin: 15px 0 25px; }
        .pagination nav > div:first-child { display: none; }
        .pagination a, .pagination span { margin-right: 5px; }
        .filter-form { display: flex; gap: 8px; flex-wrap: wrap; }
        select { padding: 8px; }

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
@endpush

