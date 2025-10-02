<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des agences</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .admin-container{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh}
    .main-content{background:#fff;border-radius:20px;margin:1rem;padding:2rem;box-shadow:0 20px 40px rgba(0,0,0,.1)}
    .badge-verify{border-radius:20px;padding:6px 12px;font-weight:600}
  </style>
</head>
<body>
  <div class="admin-container py-4">
    <div class="container">
      <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="mb-0"><i class="fas fa-building me-2"></i>Agences</h3>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Retour</a>
        </div>
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Lieu</th>
                <th>Contact</th>
                <th>Véhicules</th>
                <th>Navettes</th>
                <th>Vérifiée</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($agencies as $agency)
              <tr>
                <td>{{ $agency->id }}</td>
                <td>{{ $agency->name }}</td>
                <td>{{ $agency->email }}</td>
                <td>{{ $agency->place }}</td>
                <td>{{ $agency->contactdetails }}</td>
                <td>{{ $agency->vehicles_count }}</td>
                <td>{{ $agency->navettes_count }}</td>
                <td>
                  <span class="badge {{ $agency->email_verified_at ? 'bg-success' : 'bg-warning text-dark' }} badge-verify">
                    {{ $agency->email_verified_at ? 'Oui' : 'Non' }}
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    @if(!$agency->email_verified_at)
                    <form action="{{ route('admin.approveAgency', $agency->id) }}" method="POST">
                      @csrf
                      <button class="btn btn-sm btn-success" title="Approuver"><i class="fas fa-check"></i></button>
                    </form>
                    <form action="{{ route('admin.rejectAgency', $agency->id) }}" method="POST" onsubmit="return confirm('Rejeter et supprimer cette agence ?')">
                      @csrf
                      <button class="btn btn-sm btn-danger" title="Rejeter"><i class="fas fa-times"></i></button>
                    </form>
                    @endif
                    <!-- Edit modal trigger -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAgency{{ $agency->id }}">
                      <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.agencies.destroy', $agency->id) }}" method="POST" onsubmit="return confirm('Supprimer cette agence ?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>

                  <!-- Edit Modal -->
                  <div class="modal fade" id="editAgency{{ $agency->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form action="{{ route('admin.agencies.update', $agency->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title">Modifier agence</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <label class="form-label">Nom</label>
                              <input type="text" name="name" class="form-control" value="{{ $agency->name }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Email</label>
                              <input type="email" name="email" class="form-control" value="{{ $agency->email }}" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Lieu</label>
                              <input type="text" name="place" class="form-control" value="{{ $agency->place }}">
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Contact</label>
                              <input type="text" name="contactdetails" class="form-control" value="{{ $agency->contactdetails }}">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $agencies->links() }}
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
