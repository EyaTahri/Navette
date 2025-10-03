<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('home') }}">
      <i class="fas fa-car text-primary me-2"></i> Covoiturage Navette
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <div class="navbar-nav ms-auto">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
          <i class="fas fa-home me-1"></i> Accueil
        </a>
        <a class="nav-link {{ request()->routeIs('navettes.reservations') ? 'active' : '' }}" href="{{ route('navettes.reservations') }}">
          <i class="fas fa-calendar-check me-1"></i> Réservation
        </a>
        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
          <i class="fas fa-user me-1"></i> Profil
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
        </a>
      </div>
    </div>
  </div>
</nav>
