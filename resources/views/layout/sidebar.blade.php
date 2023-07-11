<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('img/logo.png') }}" alt="User Image" style="margin: 24px; width: 9.0rem;">
      </div>
      {{-- <div class="info">
        <a href="#" class="d-block">APLIKASI PENJADWALAN SEKOLAH</a>
      </div> --}}
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('data-kelas.index') }}" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                  Data Kelas
              </p>
            </a>
          </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Data Mapel
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('mapel-umum.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mapel Umum</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('mapel-agama.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Mapel Agama</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-item">
          <a href="{{ route('data-guru.index') }}" class="nav-link">
            <i class="far fa-user nav-icon"></i>
            <p>
              Data Guru
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('data-ruangan.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Data Ruangan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('data-waktu.index') }}" class="nav-link">
            <i class="nav-icon fas fa-file"></i>
            <p>Waktu Tidak Tersedia</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('generate-jadwal.index') }}" class="nav-link">
            <i class="nav-icon fas fa-calendar nav-icon"></i>
            <p>Generate Jadwal</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('general-setting.index') }}" class="nav-link">
            <i class="nav-icon fas fa-gear nav-icon"></i>
            <p>General Setting</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('logout') }}"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();" class="nav-link">
            <i class="fas fa-right-from-bracket nav-icon"></i>
            <p>Logout</p>
          </a>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>