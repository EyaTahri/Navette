<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier ma réservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('search.index') }}">
        <i class="fas fa-car text-primary me-2"></i> Covoiturage Navette
      </a>
      <div class="navbar-nav ms-auto">
        <a class="nav-link" href="{{ route('navettes.reservations') }}">Mes réservations</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <h3 class="mb-3"><i class="fas fa-edit me-2"></i>Modifier réservation #{{ $reservation->id }}</h3>
    <p class="text-muted">{{ $reservation->navette->departure }} → {{ $reservation->navette->destination }}</p>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('reservation.user.update', $reservation->id) }}">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Passagers</label>
          <input type="number" min="1" max="20" name="passenger_count" class="form-control" value="{{ old('passenger_count', $reservation->passenger_count ?? 1) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Téléphone</label>
          <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $reservation->contact_phone) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Paiement</label>
          <select name="payment_method" class="form-select" required>
            <option value="cash" {{ $reservation->payment_method==='cash'?'selected':'' }}>Espèces</option>
            <option value="card" {{ $reservation->payment_method==='card'?'selected':'' }}>Carte</option>
            <option value="paypal" {{ $reservation->payment_method==='paypal'?'selected':'' }}>PayPal</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Demandes spéciales</label>
          <textarea name="special_requests" rows="3" class="form-control" placeholder="Optionnel">{{ old('special_requests', $reservation->special_requests) }}</textarea>
        </div>
      </div>
      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('navettes.reservations') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>

    <form method="POST" class="mt-3" action="{{ route('reservation.user.destroy', $reservation->id) }}" onsubmit="return confirm('Supprimer cette réservation ?')">
      @csrf
      @method('DELETE')
      <button class="btn btn-danger">Supprimer la réservation</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
