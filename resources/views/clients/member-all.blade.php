@extends('clients.layouts.app')

@section('title', 'All Members')
@section('brand-title', 'Client Admin Dashboard')
@section('header-title', 'Members')

@section('content')

    <div class="dashboard">
        <div class="section-header">
            <h3 class="section-title">ALL Members</h3>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('client.dashboard') }}" class="btn">
                    ← Back to Dashboard
                </a>
            </div>
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
            // pageLength: 5,
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