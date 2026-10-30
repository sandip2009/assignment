<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\Invitation;
use App\Models\Client;
use App\Models\User;
use App\Models\ShortUrl;
use App\Events\MemberInvitationCreated;

class ClientController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();

        // Only company Admins can access this dashboard.
        abort_unless($user->role === 'Admin' || $user->role === 'Member', 403);
        // An Admin must belong to a company.
        abort_unless($user->client_id !== null, 403);

        $period = $request->query('period', 'this_month');
        return view('clients.dashboard', compact(
            'user',
            'period'
        ));
    }

    public function getMembersData(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->client_id !== null, 403);

        $query = User::where('client_id', $user->client_id)
            ->where('parent_id', $user->id)
            ->withCount('shortUrls')
            ->whereIn('role', ['Member', 'Admin']);
        // dd($query->toSql());

        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;

        // Pagination (DataTables parameters)
        $start = $request->input('start', 0);
        $length = $request->input('length', 5); // matches your original paginate(5)
        
        $members = $query->latest()->skip($start)->take($length)->get();
        // dd($members);

        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'name' => e($member->name),
                'email' => e($member->email),
                'role' => e($member->role),
                'short_urls_count' => $member->short_urls_count ?? 0,
                'created_at' => $member->created_at?->format('d M Y') ?? 'N/A',
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function viewAllMembers(Request $request)
    {
        $admin = auth()->user();
        return view('clients.member-all', compact('admin'));
    }

    public function createMember()
    {
        $admin = auth()->user();
        abort_unless($admin->role === 'Admin', 403);
        abort_unless($admin->client_id !== null, 403, 'No company assigned.');

        return view('clients.members_create');
    }

    public function storeMember(Request $request)
    {
        $admin = $request->user();
        abort_unless(
            $admin->role === 'Admin' && $admin->client_id !== null,
            403
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','email','max:255',Rule::unique('users', 'email')],
            'role' => ['required', 'string', Rule::in(['Admin', 'Member'])],
        ]);
        $token = Str::random(64);

        $invitation = Invitation::create([
            'client_id' => $admin->client_id,
            'invited_by' => $admin->id,
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => ($validated['role']) ? $validated['role'] : 'Member',
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addHours(24),
        ]);
        // dd($invitation);
        $invitationUrl = route('invitations.accept', ['token' => $token]);
        MemberInvitationCreated::dispatch($invitation, $invitationUrl);
        return redirect()->route('client.dashboard')->with('success', 'Member invitation sent successfully.');
    }

    public function getShortUrlsData(Request $request)
    {
        $user = $request->user();
        abort_unless($user && ($user->role === 'Admin' || $user->role === 'Member'), 403);

        $period = $request->input('period', 'this_month');
        $query = ShortUrl::query()->with('creator');

        // Role-based filtering
        if ($user->role === 'Member') {
            $query->where('created_by', $user->id);
        }
        // dd($user->role);
        if ($user->role === 'Admin') {
            $query->where('client_id', $user->client_id);
        }

        // Period filtering logic
        if ($period === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($period === 'last_week') {
            $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
        } elseif ($period === 'last_month') {
            $query->whereMonth('created_at', now()->subMonth()->month);
        }

        // dd($query->toSql());

        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;
        // dd($filteredRecords)

        // DataTables Pagination parameters
        $start = $request->input('start', 0);
        $length = $request->input('length', 5); // matches your paginate(5)
        // dd($start,$length);
        
        $shortUrls = $query->latest()->skip($start)->take($length)->get();
        // dd($shortUrls);

        $data = [];
        foreach ($shortUrls as $url) {
            $shortUrlLink = route('short.redirect', $url->short_code);
            $data[] = [
                'short_url' => '<a href="' . $shortUrlLink . '" target="_blank" rel="noopener noreferrer">' . $shortUrlLink . '</a>',
                'long_url' => '<a href="' . $shortUrlLink . '" target="_blank" rel="noopener noreferrer">' . e($url->long_url) . '</a>',
                'hits' => $url->hits,
                'created_by' => e($url->creator?->name ?? 'N/A'),
                'created_at' => $url->created_at?->format('d M Y') ?? 'N/A',
            ];
        }
        // dd($$data);

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function viewAll(Request $request)
    {
        $period = $request->input('period', 'this_month');
        return view('clients.short-urls-all', compact( 'period'));
    }

    public function redirect(string $short_code)
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->firstOrFail();
        // dd($shortUrl);
        $shortUrl->increment('hits');

        // Redirect to the original long URL
        return redirect()->away($shortUrl->long_url);
    }
}
