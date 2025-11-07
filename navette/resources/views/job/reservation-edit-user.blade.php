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
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier la réservation</h3>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <p class="mb-2">
              <strong><i class="fas fa-route text-primary me-2"></i>Trajet :</strong>
              {{ $reservation->navette->departure }} → {{ $reservation->navette->destination }}
            </p>
          </div>
          <div class="col-md-6">
            <p class="mb-2">
              <strong><i class="fas fa-hashtag text-primary me-2"></i>Réservation #{{ $reservation->id }}</strong>
            </p>
          </div>
        </div>
      </div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-4"><i class="fas fa-edit text-primary me-2"></i>Détails de la réservation</h4>
        
        <form method="POST" action="{{ route('reservation.user.update', $reservation->id) }}">
          @csrf
          @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">
                <i class="fas fa-map-marker-alt text-primary me-1"></i>Départ
              </label>
              <input type="text" name="departure" class="form-control" placeholder="Ville de départ" value="{{ old('departure', $reservation->navette->departure ?? '') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">
                <i class="fas fa-map-marker-alt text-danger me-1"></i>Destination
              </label>
              <input type="text" name="destination" class="form-control" placeholder="Ville d'arrivée" value="{{ old('destination', $reservation->navette->destination ?? '') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">
                <i class="fas fa-calendar-alt text-success me-1"></i>Date
              </label>
              <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date', $reservation->navette->departure_datetime ? $reservation->navette->departure_datetime->format('Y-m-d') : '') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">
                <i class="fas fa-clock text-warning me-1"></i>Heure
              </label>
              <input type="time" name="departure_time" class="form-control" value="{{ old('departure_time', $reservation->navette->departure_datetime ? $reservation->navette->departure_datetime->format('H:i') : '') }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">
                <i class="fas fa-users text-primary me-1"></i>Passagers
              </label>
              <input type="number" min="1" max="20" name="passenger_count" class="form-control" value="{{ old('passenger_count', $reservation->passenger_count ?? 1) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">
                <i class="fas fa-phone text-info me-1"></i>Téléphone
              </label>
              <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $reservation->contact_phone) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-bold">
                <i class="fas fa-credit-card text-warning me-1"></i>Paiement
              </label>
              <select name="payment_method" class="form-select" required>
                <option value="cash" {{ $reservation->payment_method==='cash'?'selected':'' }}>Espèces</option>
                <option value="card" {{ $reservation->payment_method==='card'?'selected':'' }}>Carte</option>
                <option value="paypal" {{ $reservation->payment_method==='paypal'?'selected':'' }}>PayPal</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-bold">
                <i class="fas fa-comment-alt text-secondary me-1"></i>Demandes spéciales
              </label>
              <textarea name="special_requests" rows="3" class="form-control" placeholder="Optionnel">{{ old('special_requests', $reservation->special_requests) }}</textarea>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('navettes.reservations') }}" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left me-2"></i>Annuler
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-2"></i>Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>

    <form method="POST" class="mt-3" action="{{ route('reservation.user.destroy', $reservation->id) }}" onsubmit="return confirm('Supprimer cette réservation ?')">
      @csrf
      @method('DELETE')
      <button class="btn btn-danger">Supprimer la réservation</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
