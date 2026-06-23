@extends('authLayouts.app')

@section('title', 'Login')

@section('content')

{{-- Hidden session / error messages --}}
@if(Session::has('message'))
<input type="hidden" name="theMessage" value="{{ Session::get('message')}}" class="theMessage" />
@endif

@if(Session::has('messageLogin'))
<input type="hidden" name="theMessageLogin" value="{{ Session::get('messageLogin')}}" class="theMessageLogin" />
@endif

@if (count($errors) > 0)
  @foreach ($errors->all() as $error)
    <input type="hidden" name="error{{ $loop->iteration }}" value="{{ $error }}" class="{{ (Session::get('data-from'))}}" />
  @endforeach
@endif

{{-- LOGIN PANEL --}}
<div class="auth-wrapper" id="loginPanel">
  <div class="auth-card">

    {{-- Back to Home --}}
    <a href="/" class="btn-back" title="Kembali ke Beranda">
      <i class="fas fa-arrow-left"></i>
      <span>Beranda</span>
    </a>

    {{-- Logo --}}
    <div class="logo-wrapper">
      <img src="{{ asset('/img/logo-sijatom.png')}}" alt="SIJATOM Logo" class="logo-image">
    </div>

    {{-- Title --}}
    <h1 class="auth-title">SIJATOM</h1>
    <p class="auth-subtitle">Sistem Penjadwalan Kuliah</p>

    {{-- Login Form --}}
    <form action="/login" method="post" class="auth-form">
      @csrf

      <div class="input-field @error('emailAtauUsername') borderError @enderror">
        <i class="fas fa-user"></i>
        <input
          type="text"
          name="emailAtauUsername"
          placeholder="Email atau Username"
          value="{{ old('emailAtauUsername') }}"
          autocomplete="username"
        />
      </div>

      <div class="input-field @error('passwordLogin') borderError @enderror">
        <i class="fas fa-lock"></i>
        <input
          type="password"
          id="passwordLogin"
          name="passwordLogin"
          placeholder="Password"
          autocomplete="current-password"
        />
        <button type="button" class="toggle-password" onclick="togglePassword('passwordLogin', this)" tabindex="-1" title="Tampilkan/sembunyikan password">
          <i class="fas fa-eye"></i>
        </button>
      </div>

      <input type="submit" value="Login" class="btn-login" />
    </form>

    {{-- Divider --}}
    <div class="auth-divider">
      <span></span>
      <p>atau</p>
      <span></span>
    </div>

    {{-- Register Button --}}
    <button class="btn-register" onclick="showRegisterPanel()">
      Register
    </button>

    {{-- Lupa Password --}}
    <p class="forgot-pass">Lupa Password? Silahkan Lapor Admin.</p>

  </div>
</div>

{{-- REGISTER PANEL --}}
<div class="auth-wrapper" id="registerPanel" style="display:none;">
  <div class="register-card">

    {{-- Back to Home --}}
    <a href="/" class="btn-back" title="Kembali ke Beranda">
      <i class="fas fa-arrow-left"></i>
      <span>Beranda</span>
    </a>

    {{-- Logo --}}
    <div class="logo-wrapper">
      <img src="{{ asset('/img/logo-sijatom.png')}}" alt="SIJATOM Logo" class="logo-image">
    </div>

    <h1 class="auth-title">Register</h1>

    <form action="/register" method="post" class="auth-form">
      @csrf

      <div class="input-field @error('nama') borderError @enderror">
        <i class="fas fa-user"></i>
        <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" />
      </div>

      <div class="input-field @error('username') borderError @enderror">
        <i class="fas fa-user-secret"></i>
        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" />
      </div>

      <div class="input-field @error('email') borderError @enderror">
        <i class="fas fa-envelope"></i>
        <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" />
      </div>

      <div class="input-field @error('password') borderError @enderror">
        <i class="fas fa-lock"></i>
        <input type="password" id="passwordRegister" name="password" placeholder="Password" />
        <button type="button" class="toggle-password" onclick="togglePassword('passwordRegister', this)" tabindex="-1" title="Tampilkan/sembunyikan password">
          <i class="fas fa-eye"></i>
        </button>
      </div>

      {{-- Default role for registration is Dosen (role_id = 2) --}}
      <input type="hidden" name="role" value="2" />

      <input type="submit" value="Register" class="btn-login" />
    </form>

    <div class="back-to-login">
      Sudah punya akun? <a href="#" onclick="showLoginPanel(); return false;">Login di sini</a>
    </div>

  </div>
</div>

<script>
  function showRegisterPanel() {
    document.getElementById('loginPanel').style.display = 'none';
    document.getElementById('registerPanel').style.display = 'flex';
  }

  function showLoginPanel() {
    document.getElementById('registerPanel').style.display = 'none';
    document.getElementById('loginPanel').style.display = 'flex';
  }

  // If there were register errors, show register panel automatically
  @if(Session::get('data-from') == 'register' || $errors->has('nama') || $errors->has('username') || $errors->has('email') || $errors->has('password'))
    showRegisterPanel();
  @endif

  // Toggle show/hide password
  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.classList.toggle('fa-eye',       !isHidden);
    icon.classList.toggle('fa-eye-slash',  isHidden);
  }
</script>

@endsection