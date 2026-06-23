@extends('layouts.app')

@section('title','Edit Password | Sistem Penjadwalan Kuliah')

@section('content')

  <!-- Main content -->
  <section class="content d-flex flex-column justify-content-center" style="min-height: 80vh;">
    <div class="container-fluid">

      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          @if (session('status'))
            {!! session('status') !!}
          @endif
        </div>
      </div>

      <div class="row justify-content-center">
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
                      autocomplete="new-password"
                      style="border-right: none;"
                    >
                    <div class="input-group-append">
                      <span class="input-group-text bg-white" style="cursor: pointer; border-left: none;" onclick="togglePassword('current_password', this)">
                        <i class="fas fa-eye text-muted"></i>
                      </span>
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
                      style="border-right: none;"
                    >
                    <div class="input-group-append">
                      <span class="input-group-text bg-white" style="cursor: pointer; border-left: none;" onclick="togglePassword('new_password', this)">
                        <i class="fas fa-eye text-muted"></i>
                      </span>
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
                      style="border-right: none;"
                    >
                    <div class="input-group-append">
                      <span class="input-group-text bg-white" style="cursor: pointer; border-left: none;" onclick="togglePassword('new_password_confirmation', this)">
                        <i class="fas fa-eye text-muted"></i>
                      </span>
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
  function togglePassword(targetId, el) {
    const input = document.getElementById(targetId);
    const icon = el.querySelector('i');
    
    if (!input) return;
    
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
      icon.classList.remove('text-muted');
      icon.classList.add('text-primary');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
      icon.classList.remove('text-primary');
      icon.classList.add('text-muted');
    }
  }
</script>
@endpush