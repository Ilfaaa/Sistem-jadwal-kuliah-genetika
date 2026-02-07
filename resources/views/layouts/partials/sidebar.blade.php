<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="overflow: hidden; background-color: #016a75;">
    <!-- Brand Logo -->
    <a href="/home/dashboard" class="brand-link" style="margin-left: 15px;">
      <img src="{{ asset('/img/logo-unsam.png')}}" alt="Unsam Logo" class="brand-image img-circle ml-n1">
      <span class="brand-text font-weight-bold" style="font-size: 16px">SISPEK FKOM UNIKU</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('/img/profile/'.$user_login->image)}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="/myprofile" class="d-block">{{ ucwords($user_login->username) }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item mt-n2 mr-2 mb-1" style="border-bottom :#4F5962 solid 1px">
            <a href="/home/dashboard" class="nav-link {{ (request()->segment(1) == 'home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @if($user_login->role_id == 1)
          <li class="nav-header">MENU ADMIN</li>
          <li class="nav-item">
            <a href="/manageusers" class="nav-link {{ (request()->segment(1) == 'manageusers') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Manage Users
              </p>
            </a>
          </li>
         <!--  <li class="nav-item">
            <a href="/allrequests" class="nav-link {{ (request()->segment(1) == 'allrequests') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                All Requests
                @if($countRequest > 0)
                <span class="right badge badge-danger">{{ $countRequest }}</span>
                @endif
              </p>
            </a>
          </li> -->
         
          <li class="nav-header">MENU MANAGEMENT</li>
          <li class="nav-item">
            <a href="/managekuliah" class="nav-link {{ (request()->segment(1) == 'managekuliah') ? 'active' : '' }}">
              <i class="nav-icon fas fa-school"></i>
              <p>
                Manage Kuliah
              </p>
              <i class="right fas fa-angle-left arrow-kuliah"></i>
            </a>
            <ul class="nav nav-treeview-container treeview-kuliah">
               <li class="nav-item ml-2">
                <a href="/managekuliah/manageprodi" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'manageprodi') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'manageprodi') ? 'text-greenTheme' : '' }}">
                    Manage Program Studi
                  </p>
                </a>
              </li>
              <li class="nav-item ml-2">
                <a href="/managekuliah/managematkul" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'managematkul') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'managematkul') ? 'text-greenTheme' : '' }}">
                    Manage Mata Kuliah
                  </p>
                </a>
              </li>
              <li class="nav-item ml-2">
                <a href="/managekuliah/managedosen" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'managedosen') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'managedosen') ? 'text-greenTheme' : '' }}">
                    Manage Dosen
                  </p>
                </a>
              </li>
              <li class="nav-item ml-2">
                <a href="/managekuliah/managekelas" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'managekelas') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'managekelas') ? 'text-greenTheme' : '' }}">
                    Manage Kelas
                  </p>
                </a>
              </li>
             
            </ul>
          </li>
          <li class="nav-item">
            <a href="/manageruang" class="nav-link {{ (request()->segment(1) == 'manageruang') ? 'active' : '' }}">
              <i class="nav-icon far fa-square"></i>
              <p>
                Manage Ruang
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/managewaktu" class="nav-link {{ (request()->segment(1) == 'managewaktu') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clock"></i>
              <p>
                Manage Waktu
                <i class="right fas fa-angle-left arrow-waktu"></i>
              </p>
            </a>
            <ul class="nav nav-treeview-container treeview-waktu">
              <li class="nav-item ml-2">
                <a href="/managewaktu/managehari" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'managehari') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'managehari') ? 'text-greenTheme' : '' }}">
                    Manage Hari
                  </p>
                </a>
              </li>
              <li class="nav-item ml-2">
                <a href="/managewaktu/managejam" class="nav-link">
                  <i class="nav-icon fas fa-circle-notch {{ (request()->segment(2) == 'managejam') ? 'text-greenTheme rotate-90d' : '' }}"></i>
                  <p class="{{ (request()->segment(2) == 'managejam') ? 'text-greenTheme' : '' }}">
                    Manage Jam
                  </p>
                </a>
              </li>
            </ul>
          </li>
           @endif
          <li class="nav-header">MENU PENJADWALAN KULIAH</li>
          <li class="nav-item {{ (request()->segment(1) == 'generatejadwal' || request()->segment(1) == 'hasiljadwal') ? 'menu-open' : '' }}">
            <a href="/" class="nav-link {{ (request()->segment(1) == 'generatejadwal' || request()->segment(1) == 'hasiljadwal') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>
                Penjadwalan Kuliah
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview list-jadwal">
              @if($user_login->role_id == 1)
              <li class="nav-item">
                <a href="/generatejadwal" class="nav-link pl-4">
                  <i class="far fa-circle nav-icon {{ (request()->segment(1) == 'generatejadwal') ? 'text-greenTheme' : '' }}"></i>
                  <p class="{{ (request()->segment(1) == 'generatejadwal') ? 'text-greenTheme' : '' }}">Generate Jadwal</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="/hasiljadwal" class="nav-link pl-4">
                  <i class="far fa-circle nav-icon {{ (request()->segment(1) == 'hasiljadwal') ? 'text-greenTheme' : '' }}"></i>
                  <p class="{{ (request()->segment(1) == 'hasiljadwal') ? 'text-greenTheme' : '' }}">Hasil Jadwal</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">MENU PROFILE</li>
          <li class="nav-item">
            <a href="/myprofile" class="nav-link {{ (request()->segment(1) == 'myprofile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My Profile
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/editprofile" class="nav-link {{ (request()->segment(1) == 'editprofile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-edit"></i>
              <p>
                Edit Profile
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/editpassword" class="nav-link {{ (request()->segment(1) == 'editpassword') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-lock"></i>
              <p>
                Edit Password
              </p>
            </a>
          </li>
          <li class="nav-item list-menu-sidebar ml-n2" style="border-top :#4F5962 solid 1px">
            <a href="#" class="nav-link my-2 ml-2" data-toggle="modal" data-target="#modal-logout">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </a>
        </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

 