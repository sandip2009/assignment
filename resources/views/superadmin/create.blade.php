@extends('superadmin.layouts.app')

@section('title', 'Invite Client')

@section('content')

    <h3 class="section-title">Invite New Client</h3>

    <p>
        Enter the company details and the Admin's email address.
        An invitation link will be sent to the Admin.
    </p>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('clients.store') }}"
        method="POST"
    >
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="company_name">Name</label><br>

            <input
                type="text"
                id="company_name"
                name="company_name"
                value="{{ old('company_name') }}"
                required
                maxlength="255"
                 placeholder="Client Name...."
            >
        </div>

        {{-- <div style="margin-bottom: 15px;">
            <label for="name">Admin Name</label><br>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="255"
                placeholder="Client Name...."
            >
        </div> --}}

        <div style="margin-bottom: 15px;">
            <label for="email">Email</label><br>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                maxlength="255"
                 placeholder="Ex. client@example.com"
            >
        </div>

        <button type="submit" class="btn">
            Send Invitation
        </button>

        <a
            href="{{ route('superadmin.dashboard') }}"
            class="btn"
        >
            Cancel
        </a>
    </form>

@endsection