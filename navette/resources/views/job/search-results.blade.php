<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche - Covoiturage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navette-card {
            border: 1px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .navette-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .special-badge {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .price-highlight {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            text-align: center;
        }
        .filter-sidebar {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            height: fit-content;
        }
        .search-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
        }
        .no-results i {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('search.index') }}">
                <i class="fas fa-car text-primary me-2"></i>
                Covoiturage Navette
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('search.index') }}">
                    <i class="fas fa-search me-1"></i>
                    Nouvelle recherche
                </a>
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Connexion
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Search Summary -->
        <div class="search-summary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">
                        <i class="fas fa-search me-2"></i>
                        Résultats de recherche
                    </h4>
                    <p class="mb-0">
                        @if(request('departure') || request('destination'))
                            {{ request('departure') ?: 'Toutes les villes' }} 
                            <i class="fas fa-arrow-right mx-2"></i>
                            {{ request('destination') ?: 'Toutes les destinations' }}
                        @else
                            Toutes les navettes disponibles
                        @endif
                        @if(request('departure_date'))
                            - {{ \Carbon\Carbon::parse(request('departure_date'))->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-light text-dark fs-6">
                        {{ $navettes->total() }} résultat{{ $navettes->total() > 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <h5 class="mb-3">
                        <i class="fas fa-filter me-2"></i>
                        Filtres
                    </h5>
                    
                    <form method="GET" action="{{ route('search.results') }}">
                        <!-- Preserve existing search parameters -->
                        @foreach(request()->except(['vehicle_type', 'min_capacity', 'max_price', 'special_offers']) as $key => $value)
                            @if($value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type de véhicule</label>
                            <select class="form-select" name="vehicle_type" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="Voiture" {{ request('vehicle_type') == 'Voiture' ? 'selected' : '' }}>Voiture</option>
                                <option value="Minibus" {{ request('vehicle_type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                                <option value="Bus" {{ request('vehicle_type') == 'Bus' ? 'selected' : '' }}>Bus</option>
                                <option value="Van" {{ request('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Capacité minimum</label>
                            <input type="number" class="form-control" name="min_capacity" 
                                   value="{{ request('min_capacity') }}" min="1" onchange="this.form.submit()">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Prix maximum</label>
                            <input type="number" class="form-control" name="max_price" 
                                   value="{{ request('max_price') }}" min="0" onchange="this.form.submit()">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="special_offers" 
                                       value="1" {{ request('special_offers') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label fw-bold">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    Offres spéciales
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sync me-1"></i>
                            Appliquer les filtres
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="col-md-9">
                @if($navettes->count() > 0)
                    <div class="row">
                        @foreach($navettes as $navette)
                        <div class="col-lg-6 mb-4">
                            <div class="navette-card h-100">
                                <div class="card-body">
                                    <!-- Header with special offer badge -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                {{ $navette->departure }} 
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                {{ $navette->destination }}
                                            </h5>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $navette->departure_datetime ? $navette->departure_datetime->format('d/m/Y H:i') : 'Date à définir' }}
                                            </small>
                                        </div>
                                        @if($navette->is_special_offer)
                                        <span class="special-badge">
                                            <i class="fas fa-star me-1"></i>
                                            OFFRE SPÉCIALE
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Vehicle details -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Véhicule</small>
                                            <strong>{{ $navette->vehicle_type }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Marque</small>
                                            <strong>{{ $navette->brand }}</strong>
                                        </div>
                                    </div>

                                    <!-- Capacity and availability -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Capacité</small>
                                            <strong>
                                                <i class="fas fa-users me-1"></i>
                                                {{ $navette->capacity }} places
                                            </strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Disponibles</small>
                                            <strong class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ $navette->available_seats ?? $navette->capacity }} places
                                            </strong>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($navette->description)
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit($navette->description, 100) }}
                                    </p>
                                    @endif

                                    <!-- Price and action -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="price-highlight">
                                            <div class="h5 mb-0">
                                                {{ number_format($navette->total_price ?? $navette->price_per_person, 2) }} €
                                            </div>
                                            <small>par personne</small>
                                        </div>
                                        
                                        <div class="text-end">
                                            @auth
                                                <a href="{{ route('reservation.create', $navette->id) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-calendar-plus me-1"></i>
                                                    Réserver
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" 
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-sign-in-alt me-1"></i>
                                                    Se connecter pour réserver
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $navettes->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- No results -->
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Aucun résultat trouvé</h3>
                        <p class="text-muted mb-4">
                            Aucune navette ne correspond à vos critères de recherche.
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Suggestions</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Vérifiez l'orthographe des villes
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Essayez des dates différentes
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Élargissez vos critères de recherche
                                            </li>
                                        </ul>
                                        <a href="{{ route('search.index') }}" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>
                                            Nouvelle recherche
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Covoiturage Navette. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
