<div class="modal fade" id="modal-logout">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Logout?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p>Klik "Logout" dibawah untuk mengakhiri sesi.</p>
          </div>
          <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <a class="btn" style="background-color:#4A70A9; color:#EFECE3;" href="/logout">
                Logout
              </a>
          </div>
      </div>
  </div>
</div>

<footer class="main-footer" style="background-color:#4A70A9; color:#EFECE3;">
    <strong>Copyright &copy; {{ date('Y') }} SIJATOM.</strong>
    All rights reserved.
</footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('/AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('/AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="{{ asset('/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="{{ asset('/AdminLTE/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('/AdminLTE/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- overlayScrollbars -->
<script src="{{ asset('/AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('/AdminLTE/dist/js/adminlte.min.js') }}"></script>

<!-- bs-custom-file-input -->
<script src="{{ asset('/AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script>
  $(function () {
    if (typeof bsCustomFileInput !== 'undefined') {
      bsCustomFileInput.init();
    }
  });
</script>

<!-- Custom ajax csrf -->
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    }
  })
</script>

{{-- PENTING: taruh stack scripts DI SINI (setelah semua plugin siap) --}}
@stack('scripts')

<!-- Custom javascript file (global) -->
<script src="{{ asset('/js/script.js') }}"></script>

</body>
</html>