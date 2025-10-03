<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes offres</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  @include('job.partials.navbar')
  <div class="container mt-4">
    <h3 class="mb-3"><i class="fas fa-bullhorn me-2"></i>Offres spéciales</h3>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Trajet</th>
            <th>Date</th>
            <th>Offre</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($navettes as $n)
          <tr>
            <td>{{ $n->id }}</td>
            <td>{{ $n->departure }} → {{ $n->destination }}</td>
            <td>{{ optional($n->departure_datetime)?->format('d/m/Y H:i') }}</td>
            <td>
              @if($n->is_special_offer)
                <span class="badge bg-success"><i class="fas fa-star me-1"></i> -{{ $n->discount_percentage }}%</span>
              @else
                <span class="badge bg-secondary">Aucune</span>
              @endif
            </td>
            <td class="text-end">
              @if(!$n->is_special_offer)
              <form action="{{ route('agency.offers.publish', $n->id) }}" method="POST" class="d-inline">
                @csrf
                <div class="input-group input-group-sm" style="max-width: 240px; float:right;">
                  <input type="number" name="discount_percentage" class="form-control" placeholder="Remise %" min="0" max="100">
                  <button class="btn btn-primary"><i class="fas fa-bullhorn me-1"></i>Publier</button>
                </div>
              </form>
              @else
              <form action="{{ route('agency.offers.remove', $n->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-1"></i>Retirer</button>
              </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
