<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Véhicules - {{ Auth::user()->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .vehicles-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .vehicles-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .vehicles-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
        }
        .vehicles-content {
            padding: 2rem;
        }
        .vehicle-card {
            border: 1px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .vehicle-image {
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
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
        .stats-card {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
        }
        .action-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .vehicle-card:hover .action-buttons {
            opacity: 1;
        }
        .btn-floating {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="vehicles-container">
        <div class="container">
            <div class="vehicles-card">
                <!-- En-tête -->
                <div class="vehicles-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 fw-bold mb-2">
                                <i class="fas fa-car me-2"></i>
                                Gestion des Véhicules
                            </h1>
                            <p class="lead mb-0">Gérez votre flotte de véhicules</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('agency.vehicles.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Ajouter un véhicule
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="vehicles-content">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <i class="fas fa-car fa-2x mb-2"></i>
                                <h4>{{ $vehicles->total() }}</h4>
                                <small>Total véhicules</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h4>{{ $vehicles->where('status', 'available')->count() }}</h4>
                                <small>Disponibles</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                <i class="fas fa-tools fa-2x mb-2"></i>
                                <h4>{{ $vehicles->where('status', 'maintenance')->count() }}</h4>
                                <small>En maintenance</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card" style="background: linear-gradient(135deg, #dc3545, #e83e8c);">
                                <i class="fas fa-ban fa-2x mb-2"></i>
                                <h4>{{ $vehicles->where('status', 'out_of_service')->count() }}</h4>
                                <small>Hors service</small>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Filtrer par statut</label>
                                            <select class="form-select" id="statusFilter">
                                                <option value="">Tous les statuts</option>
                                                <option value="available">Disponibles</option>
                                                <option value="in_use">En cours d'utilisation</option>
                                                <option value="maintenance">En maintenance</option>
                                                <option value="out_of_service">Hors service</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Filtrer par type</label>
                                            <select class="form-select" id="typeFilter">
                                                <option value="">Tous les types</option>
                                                <option value="Voiture">Voiture</option>
                                                <option value="Minibus">Minibus</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Van">Van</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Rechercher</label>
                                            <input type="text" class="form-control" id="searchInput" placeholder="Marque, modèle, plaque...">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($vehicles->count() > 0)
                        <!-- Liste des véhicules -->
                        <div class="row" id="vehiclesList">
                            @foreach($vehicles as $vehicle)
                            <div class="col-lg-4 col-md-6 mb-4 vehicle-item" 
                                 data-status="{{ $vehicle->status }}" 
                                 data-type="{{ $vehicle->vehicle_type }}"
                                 data-search="{{ strtolower($vehicle->brand . ' ' . $vehicle->model . ' ' . $vehicle->license_plate) }}">
                                <div class="vehicle-card h-100 position-relative">
                                    <!-- Boutons d'action -->
                                    <div class="action-buttons">
                                        <a href="{{ route('agency.vehicles.show', $vehicle->id) }}" 
                                           class="btn btn-info btn-floating" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('agency.vehicles.edit', $vehicle->id) }}" 
                                           class="btn btn-warning btn-floating" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-floating" 
                                                onclick="deleteVehicle({{ $vehicle->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Image du véhicule -->
                                    <div class="vehicle-image">
                                        @if($vehicle->main_image)
                                            <img src="{{ Storage::url($vehicle->main_image) }}" 
                                                 alt="{{ $vehicle->brand }} {{ $vehicle->model }}" 
                                                 class="img-fluid" style="max-height: 200px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-car fa-3x"></i>
                                        @endif
                                    </div>

                                    <!-- Informations du véhicule -->
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">
                                                {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </h5>
                                            <span class="status-badge status-{{ $vehicle->status }}">
                                                {{ $vehicle->status_label }}
                                            </span>
                                        </div>

                                        <p class="text-muted mb-2">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $vehicle->year }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-users me-1"></i>
                                            {{ $vehicle->capacity }} places
                                        </p>

                                        <p class="text-muted mb-2">
                                            <i class="fas fa-id-card me-1"></i>
                                            {{ $vehicle->license_plate }}
                                        </p>

                                        <p class="text-muted mb-3">
                                            <i class="fas fa-gas-pump me-1"></i>
                                            {{ $vehicle->fuel_type_label }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-cog me-1"></i>
                                            {{ $vehicle->transmission_label }}
                                        </p>

                                        @if($vehicle->description)
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($vehicle->description, 80) }}
                                        </p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-route me-1"></i>
                                                {{ $vehicle->navettes_count }} navette(s)
                                            </small>
                                            
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="changeStatus({{ $vehicle->id }}, 'available')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="changeStatus({{ $vehicle->id }}, 'maintenance')">
                                                    <i class="fas fa-tools"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="changeStatus({{ $vehicle->id }}, 'out_of_service')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $vehicles->links() }}
                        </div>
                    @else
                        <!-- Aucun véhicule -->
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-4x text-muted mb-3"></i>
                            <h3>Aucun véhicule trouvé</h3>
                            <p class="text-muted mb-4">Commencez par ajouter votre premier véhicule à votre flotte.</p>
                            <a href="{{ route('agency.vehicles.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Ajouter un véhicule
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce véhicule ?</p>
                    <p class="text-muted small">Cette action est irréversible et supprimera également toutes les images associées.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteVehicle(vehicleId) {
            const form = document.getElementById('deleteForm');
            form.action = `/agency/vehicles/${vehicleId}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        function changeStatus(vehicleId, status) {
            if (confirm('Êtes-vous sûr de vouloir changer le statut de ce véhicule ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/agency/vehicles/${vehicleId}/status`;
                
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

        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            
            const vehicles = document.querySelectorAll('.vehicle-item');
            
            vehicles.forEach(vehicle => {
                const status = vehicle.dataset.status;
                const type = vehicle.dataset.type;
                const search = vehicle.dataset.search;
                
                let show = true;
                
                if (statusFilter && status !== statusFilter) {
                    show = false;
                }
                
                if (typeFilter && type !== typeFilter) {
                    show = false;
                }
                
                if (searchInput && !search.includes(searchInput)) {
                    show = false;
                }
                
                vehicle.style.display = show ? 'block' : 'none';
            });
        }

        // Appliquer les filtres en temps réel
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('typeFilter').addEventListener('change', applyFilters);
        document.getElementById('searchInput').addEventListener('input', applyFilters);
    </script>
</body>
</html>







