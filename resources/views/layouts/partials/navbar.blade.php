<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark"
     style="background-color:#4A70A9; border-bottom: 1px solid rgba(239,236,227,0.25);">

  <!-- Left navbar/brand links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link navbar-hamburger-pill" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    @if(session('user_login'))
      @if(session('user_login')->role_id == 1)
      <li class="nav-item">
        <a class="nav-link navbar-bell-pill" href="/manageusers/approvals" role="button" title="Persetujuan Akun">
          <i class="fas fa-bell"></i>
          @php
            $pendingCount = DB::table('users')->where('is_active', 2)->count();
          @endphp
          @if($pendingCount > 0)
            <span class="badge badge-warning navbar-badge">{{ $pendingCount }}</span>
          @endif
        </a>
      </li>
      @endif
    <li class="nav-item">
      <a class="nav-link navbar-user-pill" href="#" role="button" data-toggle="modal" data-target="#modal-logout">
        <i class="fas fa-user-circle mr-1"></i>
        {{ isset($user_login) ? ucwords($user_login->username) : 'User' }}
        <i class="fas fa-sign-out-alt ml-2" title="logout"></i>
      </a>
    </li>
    @else
    <li class="nav-item">
      <a class="nav-link navbar-user-pill" href="/login">
        <i class="fas fa-sign-in-alt mr-1"></i> Login
      </a>
    </li>
    @endif
  </ul>
</nav>
<!-- /.navbar -->

<style>
  /* Navbar user pill */
  .navbar-user-pill {
    color: #EFECE3 !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
    border-radius: 50px !important;
    padding: 0 16px !important;
    font-weight: 600 !important;
    font-size: 0.88rem !important;
    display: flex !important;
    align-items: center !important;
    transition: background 0.25s, border-color 0.25s !important;
    margin-right: 8px;
    height: 36px !important;
    box-sizing: border-box !important;
  }

  .navbar-user-pill:hover,
  .navbar-user-pill:focus {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.28) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
  }

  /* Navbar bell pill */
  .navbar-bell-pill {
    color: #EFECE3 !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
    border-radius: 50% !important;
    width: 36px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: background 0.25s, border-color 0.25s !important;
    margin-right: 8px;
    padding: 0 !important;
    box-sizing: border-box !important;
    position: relative;
  }

  .navbar-bell-pill:hover,
  .navbar-bell-pill:focus {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.28) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
  }

  .navbar-bell-pill .navbar-badge {
    position: absolute;
    top: -2px;
    right: -4px;
    font-size: 0.65rem;
    padding: 2px 4px;
    border-radius: 50%;
  }

  /* Navbar hamburger pill */
  .navbar-hamburger-pill {
    color: #EFECE3 !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.25) !important;
    border-radius: 50% !important;
    width: 36px !important;
    height: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: background 0.25s, border-color 0.25s !important;
    margin-left: 8px;
    padding: 0 !important;
    box-sizing: border-box !important;
  }

  .navbar-hamburger-pill:hover,
  .navbar-hamburger-pill:focus {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.28) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
  }
</style>
