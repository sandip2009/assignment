<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Member Invitation</title>
</head>
<body>
    <h2>Hello {{ $invitation->name }},</h2>

    <p>
        You have been invited to join
        <strong>{{ $companyName }}</strong>
        as a Member.
    </p>

    <p>Click the button below to create your account.</p>

    <p>
        <a href="{{ $invitationUrl }}"
           style="display:inline-block;padding:12px 20px;
                  background:#2563eb;color:#fff;
                  text-decoration:none;border-radius:5px;">
            Accept Invitation
        </a>
    </p>

    <p>This invitation expires in 24 hours.</p>

    <p>If you did not expect this invitation, you can ignore this email.</p>
</body>
</html>