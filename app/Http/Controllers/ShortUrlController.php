<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortUrlController extends Controller
{
    //
    public function create(Request $request)
    {
        abort_unless(
            $request->user()->client_id !== null,
            403,
            'No company assigned.'
        );

        return view('clients.urls_create');
    }

    public function store(Request $request)
    {
        $admin = $request->user();
        abort_unless(
            ($admin->role === 'Admin' || $admin->role === 'Member') && $admin->client_id !== null,
            403
        );

        $validated = $request->validate([
            'long_url' => ['required','url:http,https','max:2048'],
        ]);
        $shortCode = Str::random(7);
        try {
            ShortUrl::create([
                'client_id' => $admin->client_id,
                'created_by' => $admin->id,
                'short_code' => $shortCode,
                'long_url' => $validated['long_url'],
            ]);
            return redirect()
            ->route('client.dashboard')
            ->with('success', 'Short URL generated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // If a collision happens (extremely rare with 7 random characters), handle or retry
            return back()->withErrors(['long_url' => 'Collision occurred, please try again.']);
        }
    }

    public function export(Request $request): StreamedResponse
    {
        $period = $request->input('period', 'this_month');
        $admin = auth()->user();
        // dd($admin);
        $query = ShortUrl::query()->with('creator');
        if ( $admin->role === 'Member') {
            $query->where('created_by', $admin->id);
        }
        if ( $admin->role === 'Admin') {
            $query->where('client_id', $admin->client_id);
        }
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'last_week') {
            $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
        } elseif ($period === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month);
        } else {
            $query->whereMonth('created_at', now()->month);
        }
        // dd($query->toSql());

        $fileName = 'short-urls-report-' . $period . '-' . date('Y-m-d') . '.csv';
        return response()->streamDownload(function () use ($query) {
            $file = fopen('php://output', 'w');

            // Add CSV Headers
            fputcsv($file, ['Short URL', 'Long URL', 'Hits', 'Created By', 'Created On']);

            // Fetch records in chunks to save memory
            $query->chunk(500, function ($shortUrls) use ($file) {
                foreach ($shortUrls as $url) {
                    fputcsv($file, [
                        url('/s/' . $url->short_code),
                        $url->long_url,
                        $url->hits,
                        $url->creator?->name ?? 'N/A',
                        $url->created_at?->format('d M Y H:i'),
                    ]);
                }
            });
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
