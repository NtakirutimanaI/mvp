<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
  <div class="container-fluid">
    <!-- Hamburger button -->
    <button id="hamburgerBtn" type="button"
            class="btn btn-outline-secondary me-3"
            aria-label="Toggle sidebar">
      &#9776;
    </button>

    <!-- App Name -->
    <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
      CITIZEN ENGAGEMENT SYSTEM
    </a>

    <!-- Right Icons -->
    <div class="d-flex align-items-center ms-auto me-4">
      <!-- Inbox -->
      <a href="{{ route('inbox') }}" class="text-dark me-3 position-relative" title="Inbox">
        <i class="fa-solid fa-inbox fa-lg"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          3
          <span class="visually-hidden">unread messages</span>
        </span>
      </a>

      <!-- Notifications -->
      <a href="{{ route('notifications') }}" class="text-dark me-3 position-relative" title="Notifications">
        <i class="fa-solid fa-bell fa-lg"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          5
          <span class="visually-hidden">new notifications</span>
        </span>
      </a>

      <!-- Profile dropdown -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
           id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}"
               onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}';"
               class="rounded-circle me-2 border" width="40" height="40" alt="Avatar" style="object-fit: cover;">
          <span class="fw-semibold">{{ Auth::user()->name ?? 'Guest' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
          <li><a class="dropdown-item" href="{{ route('settings') }}">Settings</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>

<!-- Sidebar -->
@php
  use App\Models\Menu;
  $userRole = Auth::user()->role ?? 'guest';
  $sidebarMenus = Menu::where('visible_in_sidebar', true)
                      ->where('user_role', $userRole)
                      ->orderBy('order', 'asc')
                      ->get();
@endphp

<div id="sidebar" style="
  position: fixed;
  top: 0;
  left: -270px;
  width: 270px;
  height: 100vh;
  background-color: #f8f9fa;
  border-right: 1px solid #dee2e6;
  transition: left 0.3s ease;
  z-index: 1050;
  padding: 1.2rem;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
  overflow-y: auto;
">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="text-primary fw-bold mb-0">Menu</h5>
    <button id="closeSidebarBtn" class="btn btn-sm btn-outline-secondary" title="Close Menu">
      &times;
    </button>
  </div>

  <!-- Sidebar content -->
  <nav>
    <ul class="list-group list-group-flush">
      @foreach($sidebarMenus as $menu)
        <li class="list-group-item border-0 px-0 py-2">
          <a href="{{ url($menu->link) }}" class="text-dark d-flex align-items-center text-decoration-none hover-opacity">
            <i class="fa {{ $menu->icon }} me-2 text-primary"></i>
            <span class="fw-medium">{{ $menu->menu_name }}</span>
          </a>
        </li>
      @endforeach
    </ul>
  </nav>
</div>

<!-- Sidebar JS -->
<script>
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sidebar = document.getElementById('sidebar');
  const closeSidebarBtn = document.getElementById('closeSidebarBtn');

  hamburgerBtn.addEventListener('click', () => {
    sidebar.style.left = '0';
  });

  closeSidebarBtn.addEventListener('click', () => {
    sidebar.style.left = '-270px';
  });

  window.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && e.target !== hamburgerBtn && !hamburgerBtn.contains(e.target)) {
      sidebar.style.left = '-270px';
    }
  });
</script>

<!-- Hover effect -->
<style>
  .hover-opacity:hover {
    opacity: 0.85;
    transition: opacity 0.2s;
  }
</style>
