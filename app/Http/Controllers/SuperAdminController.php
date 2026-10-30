<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Client;
use App\Models\Invitation;
use App\Mail\ClientInvitationMail;
use App\Models\ShortUrl;
use App\Events\ClientInvitationCreated;

class SuperAdminController extends Controller
{
    //
    public function index(Request $request)
    {
        $period = $request->input('period', 'this_month');
        return view('superadmin.dashboard', compact('period'));
    }

    public function getShortUrlsData(Request $request)
    {
        $period = $request->input('period', 'this_month');
        $query = ShortUrl::query()->with('creator');

        // Period filtering logic
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

        $totalRecords = $query->count();
        $filteredRecords = $totalRecords;

        // Pagination (DataTables parameters)
        $start = $request->input('start', 0);
        $length = $request->input('length', 10); // matches your paginate(5) preference
        
        $shortUrls = $query->latest()->skip($start)->take($length)->get();

        $data = [];
        foreach ($shortUrls as $url) {
            $shortUrlLink = route('short.redirect', $url->short_code);
            
            $data[] = [
                'short_url' => '<a href="' . $shortUrlLink . '" target="_blank" rel="noopener noreferrer">' . $shortUrlLink . '</a>',
                'long_url' => '<a href="' . $shortUrlLink . '" target="_blank" rel="noopener noreferrer">' . e($url->long_url) . '</a>',
                'hits' => $url->hits,
                'created_by' => e($url->creator?->name ?? 'N/A'),
                'created_at' => $url->created_at?->format('d M Y'),
            ];
        }
        // dd($data);

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function getClientsData(Request $request)
    {
        $query = Client::query()
            ->withCount('users')
            ->withCount('shortUrls as total_generated_urls')
            ->withSum('shortUrls as total_url_hits', 'hits');

        $totalRecords = Client::count();
        $filteredRecords = $query->count();  
        // dd($query->toSql());    

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 1); // matches your paginate(1) preference
        
        $clients = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($clients as $client) {
            $data[] = [
                'client_info' => '<strong>' . e($client->company_name) . '</strong><br><small>' . e($client->email) . '</small>',
                'users_count' => $client->users_count,
                'total_generated_urls' => $client->total_generated_urls ?? 0,
                'total_url_hits' => $client->total_url_hits ?? 0,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function create()
    {
        return view('superadmin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required','email','max:255',Rule::unique('clients', 'email')],
        ]);

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);
        // [$client, $invitation]

        $data = DB::transaction(function () use ($validated,$request,$token,$expiresAt) {
            $slug = Str::slug($validated['company_name']);

            if (Client::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::lower(Str::random(6));
            }

            $client = Client::create([
                'company_name' => $validated['company_name'],
                'slug' => $slug,
                'email' => $validated['email'],
                'created_by' => $request->user()->id,
            ]);
            // dd($client->toarray());

            $invitation = Invitation::create([
                'client_id' => $client->id,
                'invited_by' => $request->user()->id,
                'name' => $validated['company_name'],
                'email' => $validated['email'],
                'role' => 'Admin',
                'token_hash' => hash('sha256', $token),
                'expires_at' => $expiresAt,
            ]);
            // dd($invitation->toarray());
            return ["client"=>$client, "invitation" => $invitation];
        });

        $invitationUrl = route('invitations.accept', [
            'token' => $token,
        ]);
        $client = $data["client"];
        $invitation = $data["invitation"];
        ClientInvitationCreated::dispatch(
            client: $client,
            invitation: $invitation,
            invitationUrl: $invitationUrl,
            expiresAt: $expiresAt->toDayDateTimeString(),
        );

        return redirect()
            ->route('superadmin.dashboard')
            ->with(
                'success',
                'Invitation sent successfully to ' . $invitation->email
            );
    }

    public function viewAllClients(Request $request)
    {   
        return view('superadmin.clients-all');
    }

    public function viewAll(Request $request)
    {
        $period = $request->input('period', 'this_month');
        $admin = auth()->user();
        return view('superadmin.short-urls-all', compact('admin', 'period'));
    }
}
