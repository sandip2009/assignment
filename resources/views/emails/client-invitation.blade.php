<!DOCTYPE html>
<html lang="en">
<body>

    <h2>Hello {{ $adminName }},</h2>

    <p>
        You have been invited to become an Admin of
        <strong>{{ $companyName }}</strong>.
    </p>

    <p>
        Click the button below to complete your registration.
    </p>

    <p>
        <a href="{{ $invitationUrl }}"
           style="display:inline-block;padding:12px 20px;
                  background:#007bff;color:white;
                  text-decoration:none;">
            Accept Invitation
        </a>
    </p>

    <p>
        This invitation expires on {{ $expiresAt }}.
    </p>

    <p>
        If you were not expecting this invitation, you can ignore this email.
    </p>

</body>
</html>