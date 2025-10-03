<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes réservations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .hero{background:linear-gradient(135deg,rgba(102,126,234,.9),rgba(118,75,162,.9));padding:2rem 0;margin-bottom:1.5rem;color:#fff}
    .card-box{background:#fff;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);padding:1rem}
    .status-badge{border-radius:20px;padding:.35rem .75rem;font-weight:600}
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('search.index') }}">
        <i class="fas fa-car text-primary me-2"></i> Covoiturage Navette
      </a>
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="{{ route('profile') }}"><i class="fas fa-user me-1"></i>Profil</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
        </a>
      </div>
    </div>
  </nav>

  <div class="hero">
    <div class="container">
      <h1 class="h3 mb-0"><i class="fas fa-calendar-check me-2"></i>Mes réservations</h1>
    </div>
  </div>

  <div class="container mb-4">
    <div class="card-box">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Trajet</th>
              <th>Date</th>
              <th>Prix</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            @foreach($reservations as $reservation)
              <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ optional($reservation->navette)->departure }} → {{ optional($reservation->navette)->destination }}</td>
                <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($reservation->total_price, 2) }} €</td>
                <td>
                  <span class="status-badge bg-{{ $reservation->status==='confirmed'?'success':($reservation->status==='cancelled'?'danger':'secondary') }} text-white">
                    {{ ucfirst($reservation->status) }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
