<?php

namespace App\Listeners;

use App\Events\ClientInvitationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\ClientInvitationMail;
use Illuminate\Support\Facades\Mail;

class SendClientInvitationEmail implements ShouldQueue
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
    public function handle(ClientInvitationCreated $event): void
    {
        //
        try {
            Mail::to($event->invitation->email)->send(
                new ClientInvitationMail(
                    companyName: $event->client->company_name,
                    adminName: $event->invitation->name,
                    invitationUrl: $event->invitationUrl,
                    expiresAt: $event->expiresAt,
                )
            );
        } catch (Throwable $exception) {
            // Log the failure for this specific attempt
            Log::warning('SMTP attempt failed, will retry if attempts remain.', [
                'recipient' => $event->invitation->email,
                'error' => $exception->getMessage(),
            ]);

            // Rethrow so Laravel knows the job failed and triggers a retry/failure flow
            throw $exception;
        }
    }
    
}
