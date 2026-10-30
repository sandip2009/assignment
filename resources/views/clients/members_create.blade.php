<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invite New Client</title>
</head>
<body>

    <h2>Super Admin Dashboard</h2>

    <h3>Invite New Client</h3>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST"action="{{ route('members.store') }}">
        @csrf
        
        <div>
            <label for="name">Name</label><br>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="email">Email</label><br>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        {{-- Role --}}
        <div class="form-group">
            <label for="role">Role</label>

            <select id="role" name="role" >
                <option value="" >Select Role</option>
                <option value="Member" >Member</option>
                <option value="Admin" >Admin</option>
            </select>
        </div>

        <button type="submit">Send Invitation</button>

        <a href="{{ route('client.dashboard') }}">
            Cancel
        </a>

    </form>

</body>
</html>