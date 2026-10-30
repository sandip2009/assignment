@extends('clients.layouts.app')

@section('title', 'Admin Dashboard')
@section('brand-title', 'Client Admin Dashboard')
@section('header-title', 'Dashboard')

@section('content')

    <div class="page-title">CLIENT ADMIN DASHBOARD</div>

    <div class="dashboard">

        <h3 class="company-name">
            {{ $user->client->company_name }}
            — Admin Dashboard
        </h3>

        {{-- Generated Short URLs --}}
        <div class="section-header">
            <h3 class="section-title">Generated Short URLs</h3>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('urls.create') }}" class="btn">+ Generate URL</a>
                <a href="{{ route('client.urls.all') }}" class="btn">View All</a>
            </div>

            <form action="{{ route('client.dashboard') }}" method="GET" class="filter-form">
                <select name="period" id="period-select">
                    <option value="this_month" @selected($period === 'this_month')>
                        This Month
                    </option>
                    <option value="last_month" @selected($period === 'last_month')>
                        Last Month
                    </option>
                    <option value="last_week" @selected($period === 'last_week')>
                        Last Week
                    </option>
                    <option value="today" @selected($period === 'today')>
                        Today
                    </option>
                </select>
            </form>

            <a href="{{ route('client.short-urls.export', ['period' => $period]) }}" id="download-btn" class="btn">
                Download
            </a>
        </div>

        {{-- Wrapped container for AJAX replacement --}}
        {{-- This wrapper will be replaced dynamically via AJAX --}}
        @include('clients.short-urls-section', ['period' => $period])

        {{-- Team Members Section --}}
        @if(auth()->user()->role == "Admin")
        <div class="section-header">
            <h3 class="section-title">Team Members</h3>
            <a href="{{ route('members.all') }}" class="btn">View All</a>
            <a href="{{ route('members.create') }}" class="btn">
                Invite
            </a>
        </div>

        <div class="table-wrap">
            <table id="membersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Total Generated URLs</th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables populates this automatically via AJAX --}}
                </tbody>
            </table>
        </div>
        @endif

    </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#membersTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,    // Hides search box
            lengthChange: false, // Hides entries dropdown
            ordering: false,     // Disables column sorting
            ajax: "{{ route('client.members.json') }}",
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'role' },
                { data: 'short_urls_count' },
                { data: 'created_at' }
            ],
            pageLength: 2,
        });
    });
    $(document).ready(function () {
        let shortUrlsTable = $('#shortUrlsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,    // Hides search box
            lengthChange: false, // Hides entries dropdown
            ordering: false,     // Disables sorting
            ajax: {
                url: "{{ route('client.short-urls.json') }}",
                data: function (d) {
                    d.period = $('#period-select').val(); // Passes active period parameter
                }
            },
            columns: [
                { data: 'short_url' },
                { data: 'long_url' },
                { data: 'hits' },
                { data: 'created_by' },
                { data: 'created_at' }
            ],
            pageLength: 2,
        });

        // Reload table data when period filter changes
        $('#period-select').on('change', function () {
            shortUrlsTable.ajax.reload();
        });
    });
    $(document).ready(function () {
        $('#period-select').on('change', function () {
            let period = $(this).val();
            // cosole.log(period);
            // Update download button route parameter dynamically
            let exportUrl = "{{ route('client.short-urls.export') }}?period=" + period;
            $('#download-btn').attr('href', exportUrl);
        });
    }); 
</script>
@endpush

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
    </style>
@endpush