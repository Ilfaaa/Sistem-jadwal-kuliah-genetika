<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #3a5f96 0%, #4A70A9 100%);">
    <!-- Brand Logo -->
    <a href="{{ session('user_login') ? '/home/dashboard' : '/' }}" class="brand-link" style="margin-left: 15px;">
        <img src="{{ asset('/img/logo-sijatom.png') }}" alt="Sijatom Logo" class="brand-image img-circle ml-n1">
        <span class="brand-text font-weight-bold" style="font-size: 16px">SIJATOM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        @if(session('user_login') && isset($user_login) && $user_login)
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('/img/profile/'.$user_login->image) }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/myprofile" class="d-block">{{ ucwords($user_login->username) }}</a>
                <span class="d-block text-muted" style="font-size: 12px;">
                    @if($user_login->role_id == 1)
                        Admin
                    @elseif($user_login->role_id == 2)
                        Dosen
                    @elseif($user_login->role_id == 3)
                        Mahasiswa
                    @endif
                </span>
            </div>
        </div>
        @endif

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                @if(session('user_login'))
                <li class="nav-item mt-n2 mr-2 mb-1" style="border-bottom: rgba(255,255,255,0.15) solid 1px">
                    <a href="/home/dashboard" class="nav-link {{ (request()->segment(1) == 'home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dasbor</p>
                    </a>
                </li>
                @endif

                @if(session('user_login') && isset($user_login) && $user_login->role_id == 1)
                    <li class="nav-header">MENU ADMIN</li>

                    <li class="nav-item">
                        <a href="/manageusers" class="nav-link {{ (request()->segment(1) == 'manageusers') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Kelola Pengguna</p>
                        </a>
                    </li>
                @endif

                @if(session('user_login') && isset($user_login) && $user_login->role_id == 1)
                    <li class="nav-header">MENU PENGELOLAAN</li>

                    <li class="nav-item">
                        <a href="/managekuliah" class="nav-link {{ (request()->segment(1) == 'managekuliah' && request()->segment(2) == '') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Kelola Perkuliahan</p>
                            <i class="right fas fa-angle-left arrow-kuliah"></i>
                        </a>

                        <ul class="nav nav-treeview-container treeview-kuliah">
                            <li class="nav-item ml-2">
                                <a href="/managekuliah/manageprodi" class="nav-link {{ (request()->segment(2) == 'manageprodi') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-circle-notch"></i>
                                    <p>Kelola Program Studi</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="/managekuliah/managematkul" class="nav-link {{ (request()->segment(2) == 'managematkul') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-circle-notch"></i>
                                    <p>Kelola Mata Kuliah</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="/managekuliah/managedosen" class="nav-link {{ (request()->segment(2) == 'managedosen') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-circle-notch"></i>
                                    <p>Kelola Dosen</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="/managekuliah/managekelas" class="nav-link {{ (request()->segment(2) == 'managekelas') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-circle-notch"></i>
                                    <p>Kelola Kelas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @elseif(!session('user_login'))
                    <li class="nav-header">INFORMASI DOSEN</li>

                    <li class="nav-item">
                        <a href="/managekuliah/managedosen" class="nav-link {{ (request()->segment(2) == 'managedosen') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Daftar Dosen</p>
                        </a>
                    </li>
                @endif

                @if(session('user_login') && isset($user_login) && $user_login->role_id == 1)
                    <li class="nav-item">
                        <a href="/manageruang" class="nav-link {{ (request()->segment(1) == 'manageruang') ? 'active' : '' }}">
                            <i class="nav-icon far fa-square"></i>
                            <p>Kelola Ruang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/managehari" class="nav-link {{ (request()->segment(1) == 'managehari') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-day"></i>
                            <p>Kelola Hari</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/managetahunajaran" class="nav-link {{ (request()->segment(1) == 'managetahunajaran') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Kelola Tahun Ajaran</p>
                        </a>
                    </li>
                @endif

                @if(session('user_login') && isset($user_login) && $user_login->role_id != 3)
                    {{-- Menu Penjadwalan Kuliah: tampil untuk Admin dan Dosen, tersembunyi untuk Mahasiswa --}}
                    <li class="nav-header">MENU PENJADWALAN KULIAH</li>

                    <li class="nav-item">
                        <a href="{{ url('/blocking-jadwal') }}" class="nav-link {{ request()->segment(1) == 'blocking-jadwal' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ban"></i>
                            <p>Pemblokiran Jadwal Dosen</p>
                        </a>
                    </li>

                    <li class="nav-item {{ (request()->segment(1) == 'generatejadwal' || request()->segment(1) == 'hasiljadwal') ? 'menu-open' : '' }}">
                        <a href="/" class="nav-link {{ (request()->segment(1) == 'generatejadwal' || request()->segment(1) == 'hasiljadwal') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Penjadwalan Kuliah
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview list-jadwal">
                            @if(isset($user_login) && $user_login->role_id == 1)
                                <li class="nav-item">
                                    <a href="/generatejadwal" class="nav-link pl-4 {{ (request()->segment(1) == 'generatejadwal') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Buat Jadwal</p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="/hasiljadwal" class="nav-link pl-4 {{ (request()->segment(1) == 'hasiljadwal') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hasil Jadwal</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(!session('user_login') || (isset($user_login) && $user_login->role_id == 3))
                    {{-- ============================================== --}}
                    {{-- Informasi Dosen + Lihat Jadwal Kuliah --}}
                    {{-- ============================================== --}}
                    <li class="nav-header">MENU MAHASISWA</li>

                    <li class="nav-item">
                        <a href="/hasiljadwal" class="nav-link {{ (request()->segment(1) == 'hasiljadwal' || request()->is('/')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Hasil Jadwal</p>
                        </a>
                    </li>
                @endif



                @if(session('user_login') && isset($user_login) && $user_login)
                <li class="nav-header">MENU PROFIL</li>

                <li class="nav-item">
                    <a href="/myprofile" class="nav-link {{ (request()->segment(1) == 'myprofile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/editprofile" class="nav-link {{ (request()->segment(1) == 'editprofile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>Ubah Profil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/editpassword" class="nav-link {{ (request()->segment(1) == 'editpassword') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-lock"></i>
                        <p>Ubah Kata Sandi</p>
                    </a>
                </li>

                <li class="nav-item list-menu-sidebar ml-n2" style="border-top: rgba(255,255,255,0.15) solid 1px">
                    <a href="#" class="nav-link my-2 ml-2" data-toggle="modal" data-target="#modal-logout">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Keluar</p>
                    </a>
                </li>
                @else
                <li class="nav-item list-menu-sidebar ml-n2" style="border-top: rgba(255,255,255,0.15) solid 1px">
                    <a href="/login" class="nav-link my-2 ml-2">
                        <i class="nav-icon fas fa-sign-in-alt"></i>
                        <p>Login</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

