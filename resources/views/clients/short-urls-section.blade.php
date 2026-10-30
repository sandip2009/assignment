<div id="short-urls-section">
    <div class="table-wrap">
        <table id="shortUrlsTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>
                    <th>Created By</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
                {{-- Populated automatically via AJAX --}}
            </tbody>
        </table>
    </div>
</div>
{{--<div id="short-urls-section">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Long URL</th>
                    <th>Hits</th>
                    <th>Created By</th>
                    <th>Created On</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($shortUrls as $url)
                    <tr>
                        <td>
                            <a href="{{ route('short.redirect', $url->short_code) }}" target="_blank" rel="noopener noreferrer">
                                {{ route('short.redirect', $url->short_code) }}
                            </a>
                        </td>

                        <td>
                            <a href="{{ route('short.redirect', $url->short_code) }}" target="_blank" rel="noopener noreferrer">
                                {{ $url->long_url }}
                            </a>
                        </td>
                        <td>{{ $url->hits }}</td>
                        <td>
                            {{ $url->creator?->name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $url->created_at?->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            No short URLs found for this period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $shortUrls->appends(['period' => $period])->links() }}
    </div>
</div>
--}}