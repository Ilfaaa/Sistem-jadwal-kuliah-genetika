@extends('authLayouts.app')

@section('title', 'Login')

@section('content')

<div class="container">

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
  
  <div class="forms-container" >
    <div class="signin-signup">
      <form action="/login" method="post" class="sign-in-form">
        @csrf
        <img src="{{ asset('/img/logo-unsam.png')}}" alt="Unsam Logo" class="logo-image img-circle">
        <h2 class="title">Login Form</h2>
        <div class="input-field @error('emailAtauUsername')) borderError @enderror">
          <i class="fas fa-user"></i>
          <input type="text" name="emailAtauUsername" placeholder="Email Atau Username" value="{{ old('emailAtauUsername') }}" />
        </div>
        <div class="input-field @error('passwordLogin') borderError @enderror">
          <i class="fas fa-lock"></i>
          <input type="password" name="passwordLogin" placeholder="Password" />
        </div>
        <input type="submit" value="Login" class="btn solid" />
        <p class="forgot-pass">Lupa Password? Silahkan Lapor Admin.</p>
      </form>
      
      <form action="/register" method="post" class="sign-up-form">
        @csrf
        <img src="{{ asset('/img/logo-unsam.png')}}" alt="Unsam Logo" class="logo-image img-circle">
        <h2 class="title">Register Form</h2>
        
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
            <input type="password" name="password" placeholder="Password" />
        </div>
        
        <!-- Role Selection -->
        <div class="input-field @error('role') borderError @enderror">
            <i class="fas fa-user-tag"></i>
            <div class="role-selection" style="margin-top: 12px;">
                <label style="margin-right: 100px;">
                    <input type="radio" name="role" value="2" {{ old('role') == 2 ? 'checked' : '' }}>
                    Dosen
                </label>
                <label>
                    <input type="radio" name="role" value="3" {{ old('role') == 3 ? 'checked' : '' }}>
                    Mahasiswa
                </label>
            </div>
        </div>

        
        <input type="submit" class="btn" value="Register" />
</form>

    </div>
  </div>

  <div class="panels-container">
    <div class="panel left-panel">
      <div class="content">
        <h3>Belum terdaftar?</h3>
        <p>
          Silahkan melakukan registrasi dibawah ini dan tunggu persetujuan admin.
        </p>
        <button class="btn transparent" id="sign-up-btn">
          Register
        </button>
      </div>
      <img src="{{ asset('/img/log.svg')}}" class="image" alt="" />
    </div>
    <div class="panel right-panel">
      <div class="content">
        <h3>Sudah terdaftar ?</h3>
        <p>
          Silahkan masuk menggunakan username yang telah terdaftar dihalaman login dibawah ini.
        </p>
        <button class="btn transparent" id="sign-in-btn">
          Login
        </button>
      </div>
      <img src="{{ asset('/img/register.svg')}}" class="image" alt="" />
    </div>
  </div>
</div>
@endsection