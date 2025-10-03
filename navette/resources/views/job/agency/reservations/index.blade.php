<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservations - Agence</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .page-container{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh}
    .card-box{background:#fff;border-radius:20px;padding:1.5rem;box-shadow:0 10px 30px rgba(0,0,0,.1)}
  </style>
</head>
<body>
  <div class="page-container py-4">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-3 text-white">
        <h3 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Mes réservations</h3>
        <a href="{{ route('agency.vehicles.index') }}" class="btn btn-outline-light btn-sm"><i class="fas fa-car me-1"></i>Mes véhicules</a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="card-box">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Client</th>
                <th>Trajet</th>
                <th>Date</th>
                <th>Passagers</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Paiement</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reservations as $reservation)
              <tr>
                <td>{{ $reservation->id }}</td>
                <td>{{ optional($reservation->user)->name ?? '—' }}</td>
                <td>{{ $reservation->navette->departure }} → {{ $reservation->navette->destination }}</td>
                <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $reservation->passenger_count }}</td>
                <td>{{ number_format($reservation->total_price, 2) }} €</td>
                <td>
                  <span class="badge bg-{{ $reservation->status==='confirmed'?'success':($reservation->status==='cancelled'?'danger':'secondary') }}">
                    {{ ucfirst($reservation->status) }}
                  </span>
                </td>
                <td>{{ ucfirst($reservation->payment_status) }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-primary" href="{{ route('agency.reservations.edit', $reservation->id) }}">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('agency.reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Supprimer cette réservation ?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center text-muted">Aucune réservation</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="mt-2">{{ $reservations->links() }}</div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
