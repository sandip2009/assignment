@extends('superadmin.layouts.app')

@section('title', 'All Clients')

@section('content')
    <div class="page-title">ALL CLIENTS</div>

    <div class="dashboard">
        <div class="section-header">
            <h3 class="section-title">Complete Client List</h3>
            <a href="{{ route('clients.create') }}"class="btn">Invite</a>
            <a href="{{ route('superadmin.dashboard') }}" class="btn">
                ← Back to Dashboard
            </a>
            
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

        {{--<div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Users</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>
                                <strong>{{ $client->company_name ?? $client->name }}</strong><br>
                                <span style="font-size: 12px; color: #666;">{{ $client->email }}</span>
                            </td>
                            <td>{{ $client->users_count ?? 0 }}</td>
                            <td>{{ $client->total_generated_urls ?? 0 }}</td>
                            <td>{{ $client->total_url_hits ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; flex-wrap: wrap; gap: 10px;">
            <div class="pagination-info" style="font-size: 14px; color: #555;">
                Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of total {{ $clients->total() }}
            </div>

            <div class="pagination" style="margin: 0;">
                {{ $clients->links() }}
            </div>
        </div> --}}
    </div>
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
            // pageLength: 1, // matches your original pagination size
            // lengthMenu: [1, 5, 10, 25, 50]
        });
    });
</script>
@endpush