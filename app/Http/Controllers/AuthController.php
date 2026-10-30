<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\ClientInvitationMail;
use App\Models\Invitation;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 


class AuthController extends Controller
{
    //
    public function showLogin()
    {
        return view('auth.login');
    }

     public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate(); // protection for fixation attacks
            $user = Auth::user();
            return match ($user->role) {
                'SuperAdmin' => redirect()->route('superadmin.dashboard'),
                'Admin'      => redirect()->route('client.dashboard'),
                // 'Member'     => redirect()->route('client.dashboard'),
                default      => $this->logoutInvalidRole($request),
            };
        }
        return back()->withErrors(['email' => 'Invalid email or password.',])->onlyInput('email');
    }

    public function showRegister(string $token)
    {
        $invitation = Invitation::query()
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->with('client')
            ->firstOrFail(); // Automatically throws a 404 if not found

        return view('auth.register',compact('invitation', 'token'));
    }

    public function register(Request $request, string $token)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required','confirmed',Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user = DB::transaction(function () use ($token,$validated) {
            $invitation = Invitation::query()
                ->where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            abort_unless(
                $invitation &&
                $invitation->accepted_at === null &&
                $invitation->expires_at->isFuture() &&
                // $invitation->role === 'Admin',
                403,
                'This invitation is invalid, expired, or already used.'
            );
            if (User::where('email', $invitation->email)->exists()) {
                abort(409,'An account with this email already exists. Contact support.');
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => $invitation->role,
                'client_id' => $invitation->client_id,
                'parent_id' => $invitation->invited_by,
                'email_verified_at' => null,
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);
            return $user;
        });
        return redirect()->route('login');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
