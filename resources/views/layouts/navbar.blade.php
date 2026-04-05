{{-- resources/views/layouts/navbar.blade.php --}}
<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        <i class="bx bx-search fs-4 lh-0"></i>
        <input
          type="text"
          class="form-control border-0 shadow-none"
          placeholder="Search..."
          aria-label="Search..."
        />
      </div>
    </div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      @auth
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            {{-- Ganti dengan logika avatar pengguna jika ada, atau path default yang benar --}}
            <img src="{{ auth()->user()->avatar_url ?? asset('assets/img/avatars/1.png') }}" alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}"> {{-- Pastikan route 'profile.edit' ada (Breeze menyediakan ini) --}}
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ auth()->user()->avatar_url ?? asset('assets/img/avatars/1.png') }}" alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                  <small class="text-muted">{{ ucfirst(auth()->user()->role ?? 'Member') }}</small> {{-- Menampilkan role dengan huruf kapital awal --}}
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">Profil Saya</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#"> {{-- Ganti href dengan route settings jika ada --}}
              <i class="bx bx-cog me-2"></i>
              <span class="align-middle">Pengaturan</span>
            </a>
          </li>
          {{-- Contoh item billing, sesuaikan jika perlu --}}
          {{-- <li>
            <a class="dropdown-item" href="#">
              <span class="d-flex align-items-center align-middle">
                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                <span class="flex-grow-1 align-middle">Billing</span>
                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
              </span>
            </a>
          </li> --}}
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-nav">
                @csrf
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                    <i class="bx bx-power-off me-2"></i>
                    <span class="align-middle">Log Out</span>
                </a>
            </form>
          </li>
        </ul>
      </li>
      @else
      <li class="nav-item me-2">
        <a href="{{ route('login') }}" class="nav-link p-0">
          <button type="button" class="btn btn-primary">
            <span class="tf-icons bx bx-log-in"></span>&nbsp; Login
          </button>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('register') }}" class="nav-link p-0">
          <button type="button" class="btn btn-outline-primary">
            <span class="tf-icons bx bx-user-plus"></span>&nbsp; Register
          </button>
        </a>
      </li>
      @endauth
    </ul>
  </div>
</nav>