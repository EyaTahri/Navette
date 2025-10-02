<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vehicle->brand }} {{ $vehicle->model }} - Détails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .details-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .details-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .details-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
        }
        .details-content {
            padding: 2rem;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-available {
            background: #d4edda;
            color: #155724;
        }
        .status-in_use {
            background: #fff3cd;
            color: #856404;
        }
        .status-maintenance {
            background: #f8d7da;
            color: #721c24;
        }
        .status-out_of_service {
            background: #d1ecf1;
            color: #0c5460;
        }
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .image-item {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .feature-tag {
            background: #667eea;
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            margin: 5px;
            display: inline-block;
            font-size: 0.9rem;
        }
        .navette-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .navette-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.75rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #667eea;
        }
    </style>
</head>
<body>
    <div class="details-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="details-card">
                        <!-- En-tête -->
                        <div class="details-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h1 class="display-5 fw-bold mb-2">
                                        <i class="fas fa-car me-2"></i>
                                        {{ $vehicle->brand }} {{ $vehicle->model }}
                                    </h1>
                                    <p class="lead mb-0">
                                        {{ $vehicle->license_plate }} • {{ $vehicle->year }} • {{ $vehicle->capacity }} places
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="status-badge status-{{ $vehicle->status }}">
                                        {{ $vehicle->status_label }}
                                    </span>
                                    <div class="mt-2">
                                        <a href="{{ route('agency.vehicles.edit', $vehicle->id) }}" class="btn btn-light me-2">
                                            <i class="fas fa-edit me-1"></i>
                                            Modifier
                                        </a>
                                        <a href="{{ route('agency.vehicles.index') }}" class="btn btn-outline-light">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Retour
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenu principal -->
                        <div class="details-content">
                            <div class="row">
                                <!-- Informations principales -->
                                <div class="col-lg-8">
                                    <!-- Galerie d'images -->
                                    @if($vehicle->images && count($vehicle->images) > 0)
                                    <div class="image-gallery">
                                        @foreach($vehicle->images as $image)
                                        <div class="image-item">
                                            <img src="{{ Storage::url($image) }}" 
                                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal"
                                                 onclick="showImage('{{ Storage::url($image) }}')">
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="text-center py-4 mb-4" style="background: #f8f9fa; border-radius: 10px;">
                                        <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                                        <p class="text-muted">Aucune image disponible</p>
                                    </div>
                                    @endif

                                    <!-- Informations générales -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Informations générales
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Marque :</strong> {{ $vehicle->brand }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Modèle :</strong> {{ $vehicle->model }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Type :</strong> {{ $vehicle->vehicle_type }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Année :</strong> {{ $vehicle->year }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Capacité :</strong> {{ $vehicle->capacity }} places
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Plaque :</strong> {{ $vehicle->license_plate }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Couleur :</strong> {{ $vehicle->color ?? 'Non spécifiée' }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Statut :</strong> 
                                                    <span class="status-badge status-{{ $vehicle->status }}">
                                                        {{ $vehicle->status_label }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Caractéristiques techniques -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-cogs me-2"></i>
                                            Caractéristiques techniques
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Carburant :</strong> {{ $vehicle->fuel_type_label }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Transmission :</strong> {{ $vehicle->transmission_label }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Actif :</strong> 
                                                    <span class="badge bg-{{ $vehicle->is_active ? 'success' : 'danger' }}">
                                                        {{ $vehicle->is_active ? 'Oui' : 'Non' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Équipements -->
                                    @if($vehicle->features && count($vehicle->features) > 0)
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-star me-2"></i>
                                            Équipements
                                        </h4>
                                        @foreach($vehicle->features as $feature)
                                        <span class="feature-tag">
                                            <i class="fas fa-check me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $feature)) }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif

                                    <!-- Tarifs -->
                                    @if($vehicle->daily_rate || $vehicle->hourly_rate || $vehicle->km_rate)
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-euro-sign me-2"></i>
                                            Tarifs
                                        </h4>
                                        <div class="row">
                                            @if($vehicle->daily_rate)
                                            <div class="col-md-4">
                                                <p class="mb-2">
                                                    <strong>Tarif journalier :</strong><br>
                                                    <span class="h5 text-success">{{ number_format($vehicle->daily_rate, 2) }} €</span>
                                                </p>
                                            </div>
                                            @endif
                                            
                                            @if($vehicle->hourly_rate)
                                            <div class="col-md-4">
                                                <p class="mb-2">
                                                    <strong>Tarif horaire :</strong><br>
                                                    <span class="h5 text-success">{{ number_format($vehicle->hourly_rate, 2) }} €</span>
                                                </p>
                                            </div>
                                            @endif
                                            
                                            @if($vehicle->km_rate)
                                            <div class="col-md-4">
                                                <p class="mb-2">
                                                    <strong>Tarif au km :</strong><br>
                                                    <span class="h5 text-success">{{ number_format($vehicle->km_rate, 2) }} €</span>
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Dates importantes -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            Dates importantes
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Dernière maintenance :</strong><br>
                                                    {{ $vehicle->maintenance_date ? $vehicle->maintenance_date->format('d/m/Y') : 'Non spécifiée' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Expiration assurance :</strong><br>
                                                    {{ $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('d/m/Y') : 'Non spécifiée' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($vehicle->description)
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-align-left me-2"></i>
                                            Description
                                        </h4>
                                        <p class="mb-0">{{ $vehicle->description }}</p>
                                    </div>
                                    @endif

                                    <!-- Navettes utilisant ce véhicule -->
                                    @if($vehicle->navettes->count() > 0)
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-route me-2"></i>
                                            Navettes utilisant ce véhicule ({{ $vehicle->navettes->count() }})
                                        </h4>
                                        @foreach($vehicle->navettes as $navette)
                                        <div class="navette-card">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1">
                                                        {{ $navette->departure }} 
                                                        <i class="fas fa-arrow-right mx-2"></i>
                                                        {{ $navette->destination }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $navette->departure_datetime ? $navette->departure_datetime->format('d/m/Y H:i') : 'Date à définir' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <span class="badge bg-{{ $navette->accepted ? 'success' : 'warning' }}">
                                                        {{ $navette->accepted ? 'Acceptée' : 'En attente' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                <!-- Sidebar -->
                                <div class="col-lg-4">
                                    <!-- Actions rapides -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-cogs me-2"></i>
                                            Actions rapides
                                        </h4>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('agency.vehicles.edit', $vehicle->id) }}" class="btn btn-primary">
                                                <i class="fas fa-edit me-2"></i>
                                                Modifier le véhicule
                                            </a>
                                            
                                            <button class="btn btn-outline-primary" onclick="changeStatus('available')">
                                                <i class="fas fa-check me-2"></i>
                                                Marquer disponible
                                            </button>
                                            
                                            <button class="btn btn-outline-warning" onclick="changeStatus('maintenance')">
                                                <i class="fas fa-tools me-2"></i>
                                                Mettre en maintenance
                                            </button>
                                            
                                            <button class="btn btn-outline-danger" onclick="changeStatus('out_of_service')">
                                                <i class="fas fa-ban me-2"></i>
                                                Mettre hors service
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Statistiques -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Statistiques
                                        </h4>
                                        
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="border rounded p-3">
                                                    <h5 class="text-primary mb-1">{{ $vehicle->navettes->count() }}</h5>
                                                    <small class="text-muted">Navettes</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="border rounded p-3">
                                                    <h5 class="text-success mb-1">{{ $vehicle->reservations->count() }}</h5>
                                                    <small class="text-muted">Réservations</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Historique -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-history me-2"></i>
                                            Historique
                                        </h4>
                                        
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <h6 class="fw-bold mb-1">Véhicule créé</h6>
                                                <small class="text-muted">{{ $vehicle->created_at->format('d/m/Y à H:i') }}</small>
                                            </div>
                                            
                                            @if($vehicle->updated_at != $vehicle->created_at)
                                            <div class="timeline-item">
                                                <h6 class="fw-bold mb-1">Dernière modification</h6>
                                                <small class="text-muted">{{ $vehicle->updated_at->format('d/m/Y à H:i') }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les images -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Image du véhicule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Véhicule" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImage(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
        }

        function changeStatus(status) {
            if (confirm('Êtes-vous sûr de vouloir changer le statut de ce véhicule ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("agency.vehicles.updateStatus", $vehicle->id) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                
                form.appendChild(csrfToken);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>






