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
  @include('job.partials.navbar')

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
                  @if($reservation->status === 'pending')
                    <small class="ms-2">
                      <a href="{{ route('reservation.user.edit', $reservation->id) }}">modifier</a>
                      |
                      <form action="{{ route('reservation.user.destroy', $reservation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette réservation ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 align-baseline">supprimer</button>
                      </form>
                    </small>
                  @endif
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
