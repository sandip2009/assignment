<div class="table-responsive">
    <table class="url-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Short URL</th>
                <th>Original URL</th>
                <th>Created By</th>
                <th>Hits</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($urls as $url)
                <tr>
                    <td>{{ $urls->firstItem() + $loop->index }}</td>

                    <td>
                        <a
                            href="{{ url('/s/' . $url->short_code) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ url('/s/' . $url->short_code) }}
                        </a>
                    </td>

                    <td class="original-url">
                        <a
                            href="{{ $url->long_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ $url->long_url }}
                        </a>
                    </td>

                    <td>{{ $url->creator?->name ?? 'Unknown' }}</td>

                    <td>{{ $url->hits }}</td>

                    <td>{{ $url->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        No generated URLs found for this period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $urls->links() }}
</div>