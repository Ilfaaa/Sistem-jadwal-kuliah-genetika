@extends('layouts.app')

@section('title','Edit Profile | Sistem Penjadwalan Kuliah')

@section('content')
  
  <!-- Main content -->
  <section class="content d-flex flex-column justify-content-center" style="min-height: 80vh;">
    <div class="container-fluid">

      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-12 col-md-8">

          <div class="card text-choTheme">
            <div class="card-header bg-greenTheme">
              <h3 class="card-title text-whiteTheme">Form Ubah Profile</h3>
            </div>

            <form action="/editprofile" method="post" enctype="multipart/form-data">
              @method('patch')
              @csrf

              <div class="card-body">

                <div class="form-group">
                  <label for="email">Email address</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="{{ $user_login->email }}"
                    readonly
                  >
                </div>

                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $user_login->name) }}"
                  >
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="file">Picture</label>

                  <div class="row align-items-center">
                    <div class="col-sm-3 mb-2 mb-sm-0">
                      <img
                        src="{{ asset('/img/profile/' . $user_login->image) }}"
                        class="img-thumbnail rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;"
                        alt="Profile image"
                      >
                    </div>

                    <div class="col-sm-9">
                      <div class="input-group">
                        <div class="custom-file">
                          <input
                            type="file"
                            class="custom-file-input @error('file') is-invalid @enderror"
                            id="file"
                            name="file"
                            accept="image/*"
                          >
                          <label class="custom-file-label" for="file">
                            {{ $user_login->image }}
                          </label>
                        </div>
                      </div>

                      @error('file')
                        <div class="text-danger mt-1" style="font-size: 12px; font-style: italic;">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                  </div>

                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <a href="/myprofile" class="btn btn-outline-greenTheme">Kembali</a>
                <button type="submit" class="btn btn-profile float-right">Edit Profile</button>
              </div>

            </form>
          </div>

        </div>
      </div>

    </div>
  </section>
@endsection


@push('scripts')
<style>
    /* Make the file input and label show pointer cursor */
    .custom-file-input, .custom-file-label, .custom-file-label::after {
        cursor: pointer !important;
        transition: all 0.3s ease-in-out;
    }
    
    /* Hover effect for the "Browse" pseudo-element */
    .custom-file-input:hover ~ .custom-file-label::after,
    .custom-file-label:hover::after {
        background-color: #0056b3 !important; /* A nice interactive blue */
        color: #ffffff !important;
    }
</style>

@push('scripts')
<script>
    // Update label with file name
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
        
        // Preview image
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('.img-thumbnail').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush

