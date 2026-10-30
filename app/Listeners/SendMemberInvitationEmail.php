<?php

namespace App\Listeners;

use App\Events\MemberInvitationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\MemberInvitationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMemberInvitationEmail implements ShouldQueue
{
    public int $tries = 3;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MemberInvitationCreated $event): void
    {
        //
        try {
            Mail::to($event->invitation->email)->send(
                new MemberInvitationMail(
                    $event->invitation,
                    $event->invitationUrl
                )
            );
        } catch (Throwable $exception) {
            Log::warning('Member invitation email attempt failed, will retry if attempts remain.', [
                'recipient' => $event->invitation->email,
                'error' => $exception->getMessage(),
            ]);
            
            throw $exception;
        }
    }
}
