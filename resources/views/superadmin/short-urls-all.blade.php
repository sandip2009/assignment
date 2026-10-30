@extends('superadmin.layouts.app')

@section('title', 'All Short URLs')
@section('brand-title', 'Client Admin Dashboard')
@section('header-title', 'All Short URLs')

@section('content')

    <div class="dashboard">
        <div class="section-header">
            <h3 class="section-title">ALL SHORT URLS</h3>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                {{-- Filter Form --}}
                <form action="{{ route('superadmin.urls.all') }}" method="GET" class="filter-form" id="filter-form">
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
    </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>

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
            // pageLength: 5,
        });
        $('#period-select').on('change', function () {
            // console.log($(this).value);
            table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $('#period-select').on('change', function () {
            let period = $(this).val();
            // console.log(period);
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