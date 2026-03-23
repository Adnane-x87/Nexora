@extends('layouts.auth')

@section('title', 'NEXORA — Login')

@section('content')
    <p class="login-tagline">Sign in to your account</p>

    <div class="login-card">
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-input" id="loginEmail" placeholder="you@example.com"/>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="pass-wrap">
          <input type="password" class="form-input" id="loginPass" placeholder="••••••••"/>
          <button class="pass-toggle" data-target="loginPass">👁</button>
        </div>
      </div>
      <div class="form-error" id="loginError"></div>
      <button class="btn-login" id="loginBtn">Sign In</button>
      <div class="login-footer">Don't have an account? <a href="{{ route('register') }}">Register</a></div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/login.js'])
@endpush
