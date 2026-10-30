<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Complete Registration</title>
</head>
<body>

    <h2>Complete Your Registration</h2>

    <p>Company: {{ $invitation->client->company_name }}</p>

    <p>Welcome, {{ $invitation->name }}!</p>

    @if ($errors->any())
        <div style="color:red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"
          action="{{ route('invitations.register', ['token' => $token]) }}">

        @csrf

        <div>
            <label for="name">Name</label><br>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $invitation->name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Email</label><br>

            <input
                type="email"
                value="{{ $invitation->email }}"
                readonly
            >
        </div>

        <br>

        <div>
            <label for="password">Create Password</label><br>

            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <br>

        <div>
            <label for="password_confirmation">Confirm Password</label><br>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <br>

        <button type="submit">Complete Registration</button>

    </form>

</body>
</html>