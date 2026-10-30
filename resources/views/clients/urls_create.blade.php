@extends('clients.layouts.app')

@section('title', 'Generate Short URL')

@section('brand-title', 'Client Member Dashboard')

@section('header-title', 'Dashboard')

@section('content')

    <div class="container">
    <h2>Generate Short URL</h2>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('urls.store') }}">
        @csrf

        <label for="long_url">Original URL</label>

        <input
            type="url"
            id="long_url"
            name="long_url"
            placeholder="https://example.com/page"
            value="{{ old('long_url') }}"
            maxlength="2048"
            required
        >

        <button type="submit" class="btn">Generate URL</button>

        <a href="{{ route('client.dashboard') }}" class="btn">
            Back to Dashboard
        </a>
    </form>
</div>

@endsection
@push('styles')
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #a5d5f5;
            font-family: monospace;
        }

        .container {
            max-width: 750px;
            margin: 20px auto;
            padding: 25px;
            border: 2px solid #222;
            background: #e9edf1;
        }

        h2 {
            color: #0875df;
            font-weight: normal;
        }

        label {
            display: block;
            margin: 20px 0 8px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 2px solid #222;
            font-family: monospace;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 18px;
            border: 1px solid #0875df;
            background: #a5d5f5;
            color: #064f9e;
            text-decoration: none;
            cursor: pointer;
            font-family: monospace;
        }

        .error {
            margin-top: 8px;
            color: #dc3545;
        }
    </style>
@endpush


