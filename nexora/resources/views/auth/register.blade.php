@extends('layouts.auth')

@section('title', 'NEXORA — Register')

@section('content')
    <p class="login-tagline">Create your NEXORA account</p>

    <div class="login-card">
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-input" id="regName" placeholder="John Doe"/>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" class="form-input" id="regEmail" placeholder="you@example.com"/>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div class="pass-wrap">
          <input type="password" class="form-input" id="regPass" placeholder="••••••••"/>
          <button class="pass-toggle" data-target="regPass">👁</button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <div class="pass-wrap">
          <input type="password" class="form-input" id="regPassConfirm" placeholder="••••••••"/>
          <button class="pass-toggle" data-target="regPassConfirm">👁</button>
        </div>
      </div>
      <div class="form-error" id="regError"></div>
      <div class="form-success" id="regSuccess"></div>
      <button class="btn-login" id="registerBtn">Create Account</button>
      <div class="login-footer">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/register.js'])
@endpush
