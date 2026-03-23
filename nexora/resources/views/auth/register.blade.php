@extends('layouts.auth')

@section('title', 'NEXORA — Register')

@section('content')
    <p class="login-tagline">Create your NEXORA account</p>

    <form action="{{ route('register') }}" method="POST" class="login-card">
      @csrf
      <div class="form-group">
        <label class="form-label" for="name">Full Name</label>
        <input type="text" name="name" class="form-input" id="name" placeholder="John Doe" value="{{ old('name') }}" required autofocus/>
        @error('name') <div class="form-error">{{ $message }}</div> @enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input type="email" name="email" class="form-input" id="email" placeholder="you@example.com" value="{{ old('email') }}" required/>
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
      <div class="form-group">
        <label class="form-label" for="password_confirmation">Confirm Password</label>
        <div class="pass-wrap">
          <input type="password" name="password_confirmation" class="form-input" id="password_confirmation" placeholder="••••••••" required/>
          <button type="button" class="pass-toggle" data-target="password_confirmation">👁</button>
        </div>
      </div>
      <button type="submit" class="btn-login">Create Account</button>
      <div class="login-footer">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
    </form>
@endsection

@push('scripts')
    @vite(['resources/js/register.js'])
@endpush
