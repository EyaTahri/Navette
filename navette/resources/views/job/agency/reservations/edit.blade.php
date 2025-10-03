<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier réservation</title>
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
      <div class="text-white mb-3">
        <a href="{{ route('agency.reservations.index') }}" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Retour</a>
      </div>

      <div class="card-box">
        <h4 class="mb-3"><i class="fas fa-edit me-2"></i>Modifier réservation #{{ $reservation->id }}</h4>
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

        <form method="POST" action="{{ route('agency.reservations.update', $reservation->id) }}">
          @csrf
          @method('PUT')
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre de passagers</label>
              <input type="number" min="1" max="20" name="passenger_count" class="form-control" value="{{ old('passenger_count', $reservation->passenger_count) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Téléphone de contact</label>
              <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $reservation->contact_phone) }}" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Demandes spéciales</label>
              <textarea name="special_requests" rows="3" class="form-control" placeholder="Notes...">{{ old('special_requests', $reservation->special_requests) }}</textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select name="status" class="form-select" required>
                <option value="pending" {{ $reservation->status==='pending'?'selected':'' }}>En attente</option>
                <option value="confirmed" {{ $reservation->status==='confirmed'?'selected':'' }}>Confirmée</option>
                <option value="cancelled" {{ $reservation->status==='cancelled'?'selected':'' }}>Annulée</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Statut paiement</label>
              <select name="payment_status" class="form-select" required>
                <option value="pending" {{ $reservation->payment_status==='pending'?'selected':'' }}>En attente</option>
                <option value="paid" {{ $reservation->payment_status==='paid'?'selected':'' }}>Payé</option>
                <option value="refunded" {{ $reservation->payment_status==='refunded'?'selected':'' }}>Remboursé</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Mode paiement</label>
              <select name="payment_method" class="form-select" required>
                <option value="cash" {{ $reservation->payment_method==='cash'?'selected':'' }}>Espèces</option>
                <option value="card" {{ $reservation->payment_method==='card'?'selected':'' }}>Carte</option>
                <option value="paypal" {{ $reservation->payment_method==='paypal'?'selected':'' }}>PayPal</option>
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('agency.reservations.index') }}" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
