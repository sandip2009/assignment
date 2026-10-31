<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Complete Registration</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
            padding: 25px 15px;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .register-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .register-header h2 {
            color: #222;
            font-size: 26px;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #777;
            font-size: 14px;
            line-height: 1.5;
        }

        .company-info {
            background: #f5f5ff;
            border: 1px solid #e0e0ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .company-info .label {
            color: #777;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .company-info .company-name {
            color: #4f46e5;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            overflow-wrap: anywhere;
        }

        .company-info .welcome-text {
            color: #333;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdfe5;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            border-color: #4f46e5;
        }

        .form-group input[readonly] {
            background: #f8f9fb;
            color: #666;
            cursor: not-allowed;
        }

        .error-message {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 7px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .error-message p + p {
            margin-top: 6px;
        }

        .register-button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 7px;
            background: #4f46e5;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .register-button:hover {
            background: #4338ca;
        }

        .register-footer {
            text-align: center;
            margin-top: 25px;
            color: #888;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 25px 20px;
            }

            .register-header h2 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <div class="register-container">

        <div class="register-header">
            <h2>Complete Registration</h2>
            <p>Create your account to get started.</p>
        </div>

        <div class="company-info">
            <div class="label">COMPANY</div>

            <div class="company-name">
                {{ $invitation->client->company_name }}
            </div>

            <div class="welcome-text">
                Welcome, {{ $invitation->name }}!
            </div>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST"
              action="{{ route('invitations.register', ['token' => $token]) }}">

            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $invitation->name) }}"
                    placeholder="Enter your full name"
                    autocomplete="name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    value="{{ $invitation->email }}"
                    readonly
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">Create Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password (minimum 8 characters)"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Re-enter your password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >
            </div>

            <button type="submit" class="register-button">
                Complete Registration
            </button>

        </form>

        <div class="register-footer">
            &copy; {{ date('Y') }} All rights reserved.
        </div>

    </div>

</body>
</html>