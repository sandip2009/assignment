@extends('superadmin.layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
    @if (session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="section-header">
        <h3 class="section-title">Clients</h3>
        <a href="{{ route('clients.create') }}"class="btn">Invite</a>
        <a href="{{ route('clients.all') }}" class="btn">View All</a> 
    </div>
    <div class="table-wrap">
        <table id="clientsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Users</th>
                    <th>Total Generated URLs</th>
                    <th>Total URL Hits</th>
                </tr>
            </thead>
            <tbody>
                {{-- DataTables will automatically populate this via AJAX --}}
            </tbody>
        </table>
    </div>

    <div class="section-header">
        <h3 class="section-title">Generated Short URLs</h3>
        <a href="{{ route('superadmin.urls.all') }}" class="btn">View All</a>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            {{-- Filter Form --}}
            <form action="{{ route('superadmin.dashboard') }}" method="GET" class="filter-form" id="filter-form">
                <select name="period" id="period-select">
                    <option value="this_month" @selected($period === 'this_month')>This Month</option>
                    <option value="last_month" @selected($period === 'last_month')>Last Month</option>
                    <option value="last_week" @selected($period === 'last_week')>Last Week</option>
                    <option value="today" @selected($period === 'today')>Today</option>
                </select>
            </form>
            <a href="{{ route('client.short-urls.export', ['period' => $period]) }}" id="download-btn" class="btn">
                Download
            </a>
            <a href="{{ route('superadmin.dashboard', ['period' => $period]) }}" class="btn">
                ← Back to Dashboard
            </a>
        </div>
    </div>
    {{-- Dynamic Section Wrapper --}}
    @include('superadmin.short-urls-section')
    

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#clientsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            ordering: false,
            ajax: "{{ route('superadmin.clients.json') }}",
            columns: [
                { data: 'client_info' },
                { data: 'users_count' },
                { data: 'total_generated_urls' },
                { data: 'total_url_hits' }
            ],
            pageLength: 2,
            // lengthMenu: [1, 5, 10, 25, 50]
        });
    });
    $(document).ready(function () {
        let table = $('#shortUrlsTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            ordering: false, // Disabled sorting
            ajax: {
                url: "{{ route('superadmin.short-urls.json') }}",
                data: function (d) {
                    d.period = $('#period-select').val(); // Send active period filter
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

        // Re-fetch data when period dropdown changes
        $('#period-select').on('change', function () {
            table.ajax.reload();
        });
    });
</script>
<script>
    // $(document).ready(function () {
    //     // 1. Handle Filter Dropdown Change via AJAX
    //     $('#period-select').on('change', function () {
    //         let period = $(this).val();
    //         let url = $('#filter-form').attr('action');

    //         fetchUrls(url, { period: period });
    //     });

    //     // 2. Handle Pagination Clicks via Event Delegation
    //     $(document).on('click', '#short-urls-section .pagination a', function (e) {
    //         e.preventDefault();
    //         let url = $(this).attr('href');

    //         fetchUrls(url);
    //     });

    //     // Helper AJAX function
    //     function fetchUrls(url, data = {}) {
    //         $.ajax({
    //             url: url,
    //             type: 'GET',
    //             data: data,
    //             beforeSend: function () {
    //                 $('#short-urls-section').css('opacity', '0.5');
    //             },
    //             success: function (response) {
    //                 console.log(response);
    //                 $('#short-urls-section').replaceWith(response.html);
    //             },
    //             error: function () {
    //                 console.error('Failed to load data.');
    //             },
    //             complete: function () {
    //                 $('#short-urls-section').css('opacity', '1');
    //             }
    //         });
    //     }
    // });
    $(document).ready(function () {
        $('#period-select').on('change', function () {
            let period = $(this).val();

            // Update download button route parameter dynamically
            let exportUrl = "{{ route('client.short-urls.export') }}?period=" + period;
            $('#download-btn').attr('href', exportUrl);
        });
    }); 
</script>
@endpush