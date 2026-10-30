<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
</head>
<body>

    <h2>Login</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div>
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email','superadmin@example.com') }}"
                required
            >
        </div>

        <br>

        <div>
            <label>Password</label>
            <input
                type="password"
                name="password"
                value="SuperAdmin@123"
                required
            >
        </div>

        <br>

        <button type="submit">Login</button>
    </form>

</body>
</html>