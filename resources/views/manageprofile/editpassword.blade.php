@extends('layouts.app')

@section('title','Edit Password | Sistem Penjadwalan Kuliah')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Password</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
              <a href="/home/dashboard"><i class="fas fa-igloo mr-2"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
              <a href="/myprofile">My Profile</a>
            </li>
            <li class="breadcrumb-item active">Edit Password</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-6">
          @if (session('status'))
            {!! session('status') !!}
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-8">

          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Edit Password</h3>
            </div>

            <form action="/editpassword" method="post">
              @method('patch')
              @csrf

              <div class="card-body">

                {{-- Password Saat Ini --}}
                <div class="form-group">
                  <label for="current_password">Password Saat Ini</label>

                  <div class="input-group">
                    <input
                      type="password"
                      class="form-control @error('current_password') is-invalid @enderror"
                      id="current_password"
                      name="current_password"
                      autocomplete="current-password"
                    >

                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-outline-secondary js-toggle-password"
                        data-target="current_password"
                        aria-label="Tampilkan/Sembunyikan password saat ini"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>

                  @error('current_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Password Baru --}}
                <div class="form-group">
                  <label for="new_password">Password Baru</label>

                  <div class="input-group">
                    <input
                      type="password"
                      class="form-control @error('new_password') is-invalid @enderror"
                      id="new_password"
                      name="new_password"
                      autocomplete="new-password"
                    >

                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-outline-secondary js-toggle-password"
                        data-target="new_password"
                        aria-label="Tampilkan/Sembunyikan password baru"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>

                  @error('new_password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="form-group">
                  <label for="new_password_confirmation">Konfirmasi Password Baru</label>

                  <div class="input-group">
                    <input
                      type="password"
                      class="form-control @error('new_password_confirmation') is-invalid @enderror"
                      id="new_password_confirmation"
                      name="new_password_confirmation"
                      autocomplete="new-password"
                    >

                    <div class="input-group-append">
                      <button
                        type="button"
                        class="btn btn-outline-secondary js-toggle-password"
                        data-target="new_password_confirmation"
                        aria-label="Tampilkan/Sembunyikan konfirmasi password baru"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>

                  @error('new_password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>

              </div>

              <div class="card-footer">
                <a href="/myprofile" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-profile float-right">Ubah Password</button>
              </div>
            </form>

          </div>

        </div>
      </div>

    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.js-toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (!input) return;

      const isHidden = (input.type === 'password');
      input.type = isHidden ? 'text' : 'password';

      // Toggle icon (eye <-> eye-slash)
      if (icon) {
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
      }
    });
  });
</script>
@endpush