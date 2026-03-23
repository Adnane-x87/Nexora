@extends('layouts.auth')

@section('title', 'NEXORA — Login')

@section('content')
    <p class="login-tagline">Sign in to your account</p>

    <form action="{{ route('login') }}" method="POST" class="login-card">
      @csrf
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" name="email" class="form-input" id="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus/>
        @error('email') <div class="form-error">{{ $message }}</div> @enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="pass-wrap">
          <input type="password" name="password" class="form-input" id="password" placeholder="••••••••" required/>
          <button type="button" class="pass-toggle" data-target="password">👁</button>
        </div>
        @error('password') <div class="form-error">{{ $message }}</div> @enderror
      </div>
      <button type="submit" class="btn-login">Sign In</button>
      <div class="login-footer">Don't have an account? <a href="{{ route('register') }}">Register</a></div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/login.js'])
@endpush
