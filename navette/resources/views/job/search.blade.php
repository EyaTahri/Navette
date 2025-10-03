<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Navettes - Covoiturage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .search-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="cars" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23cars)"/></svg>');
            opacity: 0.3;
        }
        .search-form {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
        }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .special-offer-card {
            border: 2px solid #ff6b6b;
            border-radius: 15px;
            transition: transform 0.3s ease;
            background: linear-gradient(135deg, #fff5f5, #ffe8e8);
        }
        .special-offer-card:hover {
            transform: translateY(-5px);
        }
        .popular-destination {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .popular-destination:hover {
            background: #667eea;
            color: white;
            border-color: #5a6fd8;
        }
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="road" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><rect width="100" height="20" y="40" fill="%23ffffff" opacity="0.1"/><rect width="100" height="20" y="80" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="1200" height="400" fill="url(%23road)"/><circle cx="200" cy="200" r="30" fill="%23ffffff" opacity="0.1"/><circle cx="800" cy="150" r="40" fill="%23ffffff" opacity="0.1"/><circle cx="1000" cy="300" r="25" fill="%23ffffff" opacity="0.1"/></svg>');
            background-size: cover;
            background-position: center;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
        .stats-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 3rem 0;
            margin: 3rem 0;
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            display: block;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-car text-primary me-2"></i>
                Covoiturage Navette
            </a>
            <div class="navbar-nav ms-auto">
                @auth
                    @if(Auth::user()->role === 'ADMIN')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-shield-alt me-1"></i>
                            Admin
                        </a>
                    @endif
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Déconnexion
                    </a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Section Héro -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Voyagez malin, voyagez ensemble
                    </h1>
                    <p class="lead text-white mb-4">
                        Trouvez votre navette idéale parmi des milliers de trajets disponibles. 
                        Économisez sur vos déplacements tout en réduisant votre empreinte carbone.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#search" class="btn btn-light btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Rechercher maintenant
                        </a>
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            S'inscrire
                        </a>
                        @endguest
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="feature-icon" style="position: absolute; top: 20%; left: 10%;">
                            <i class="fas fa-route"></i>
                        </div>
                        <div class="feature-icon" style="position: absolute; top: 60%; right: 20%;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-icon" style="position: absolute; bottom: 20%; left: 30%;">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <i class="fas fa-car fa-5x text-white opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Trajets disponibles</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">5K+</span>
                        <span class="stat-label">Utilisateurs actifs</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Agences partenaires</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number">50%</span>
                        <span class="stat-label">Économie moyenne</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-container" id="search">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center text-white mb-4">
                        <h1 class="display-4 fw-bold mb-3">Trouvez votre navette idéale</h1>
                        <p class="lead">Recherchez parmi des milliers de trajets disponibles</p>
                    </div>
                    
                    <form class="search-form" action="{{ route('search.results') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                    Départ
                                </label>
                                <input type="text" class="form-control" name="departure" 
                                       placeholder="Ville de départ" value="{{ request('departure') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                    Destination
                                </label>
                                <input type="text" class="form-control" name="destination" 
                                       placeholder="Ville d'arrivée" value="{{ request('destination') }}">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt text-success me-1"></i>
                                    Date
                                </label>
                                <input type="date" class="form-control" name="departure_date" 
                                       value="{{ request('departure_date') }}">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-clock text-warning me-1"></i>
                                    Heure
                                </label>
                                <input type="time" class="form-control" name="departure_time" 
                                       value="{{ request('departure_time') }}">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-users text-info me-1"></i>
                                    Passagers
                                </label>
                                <input type="number" class="form-control" name="min_capacity" 
                                       placeholder="Min" min="1" value="{{ request('min_capacity') }}">
                            </div>
                        </div>
                        
                        <!-- Filtres avancés -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type de véhicule</label>
                                <select class="form-control" name="vehicle_type">
                                    <option value="">Tous les types</option>
                                    <option value="Voiture" {{ request('vehicle_type') == 'Voiture' ? 'selected' : '' }}>Voiture</option>
                                    <option value="Minibus" {{ request('vehicle_type') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                                    <option value="Bus" {{ request('vehicle_type') == 'Bus' ? 'selected' : '' }}>Bus</option>
                                    <option value="Van" {{ request('vehicle_type') == 'Van' ? 'selected' : '' }}>Van</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Prix maximum</label>
                                <input type="number" class="form-control" name="max_price" 
                                       placeholder="Prix max" min="0" value="{{ request('max_price') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Trier par</label>
                                <select class="form-control" name="sort_by">
                                    <option value="departure_time" {{ request('sort_by') == 'departure_time' ? 'selected' : '' }}>Heure de départ</option>
                                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Prix</option>
                                    <option value="capacity" {{ request('sort_by') == 'capacity' ? 'selected' : '' }}>Capacité</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Ordre</label>
                                <select class="form-control" name="sort_order">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="special_offers" 
                                           value="1" {{ request('special_offers') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        Offres spéciales uniquement
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-search btn-lg">
                                    <i class="fas fa-search me-2"></i>
                                    Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Destinations -->
    <div class="container mb-5">
        <h2 class="text-center mb-4">
            <i class="fas fa-fire text-danger me-2"></i>
            Destinations populaires
        </h2>
        <div class="row">
            @foreach($popularDestinations as $destination)
            <div class="col-md-3 col-sm-6">
                <div class="popular-destination text-center" 
                     onclick="fillDestination('{{ $destination->departure }}', '{{ $destination->destination }}')">
                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                    <h6 class="mb-1">{{ $destination->departure }}</h6>
                    <small class="text-muted">vers</small>
                    <h6 class="mb-0">{{ $destination->destination }}</h6>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @auth
    @if(isset($availableNavettes) && $availableNavettes->count() > 0)
    <!-- Réservation rapide -->
    <div class="container mb-5">
        <h2 class="text-center mb-4">
            <i class="fas fa-bolt text-warning me-2"></i>
            Réservation rapide
        </h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('reservation.store') }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Choisir un trajet</label>
                        <select name="navette_id" class="form-select" required>
                            @foreach($availableNavettes as $n)
                              <option value="{{ $n->id }}">
                                {{ $n->departure }} → {{ $n->destination }}
                                {{ $n->departure_datetime ? ' - '. $n->departure_datetime->format('d/m H:i') : '' }}
                              </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Passagers</label>
                        <input type="number" name="passenger_count" class="form-control" value="1" min="1" max="20" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Téléphone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ Auth::user()->contactdetails ?? '' }}" placeholder="06 12 34 56 78" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Demandes spéciales</label>
                        <textarea name="special_requests" class="form-control" rows="2" placeholder="Optionnel"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Paiement</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Espèces</option>
                            <option value="card">Carte bancaire</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                    <div class="col-md-8 text-end">
                        <button class="btn btn-search btn-lg" type="submit">
                            <i class="fas fa-calendar-check me-2"></i>
                            Réserver maintenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <!-- Special Offers -->
    @if($specialOffers->count() > 0)
    <div class="container mb-5">
        <h2 class="text-center mb-4">
            <i class="fas fa-star text-warning me-2"></i>
            Offres spéciales
        </h2>
        <div class="row">
            @foreach($specialOffers as $offer)
            <div class="col-md-4 mb-4">
                <div class="card special-offer-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-danger">OFFRE SPÉCIALE</span>
                            <span class="text-success fw-bold">-{{ $offer->discount_percentage }}%</span>
                        </div>
                        <h5 class="card-title">{{ $offer->departure }} → {{ $offer->destination }}</h5>
                        <p class="card-text">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $offer->departure_datetime ? $offer->departure_datetime->format('d/m/Y H:i') : 'Date à définir' }}
                        </p>
                        <p class="card-text">
                            <i class="fas fa-car me-1"></i>
                            {{ $offer->vehicle_type }} - {{ $offer->brand }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">
                                {{ number_format($offer->price_per_person, 2) }} €
                            </span>
                            <small class="text-muted">par personne</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-car text-primary me-2"></i>
                        Covoiturage Navette
                    </h5>
                    <p class="text-muted">
                        La plateforme de référence pour vos déplacements en navette. 
                        Économique, écologique et pratique.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Recherche</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Réservation</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Paiement</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Support</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Entreprise</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">À propos</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Carrières</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Presse</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Légal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">CGU</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Confidentialité</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Cookies</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Mentions légales</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="fas fa-phone me-2"></i>01 23 45 67 89</li>
                        <li><i class="fas fa-envelope me-2"></i>contact@covoiturage.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Paris, France</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; 2024 Covoiturage Navette. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">Fait avec <i class="fas fa-heart text-danger"></i> pour l'environnement</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillDestination(departure, destination) {
            document.querySelector('input[name="departure"]').value = departure;
            document.querySelector('input[name="destination"]').value = destination;
        }

        // Auto-complete pour les villes
        document.addEventListener('DOMContentLoaded', function() {
            const departureInput = document.querySelector('input[name="departure"]');
            const destinationInput = document.querySelector('input[name="destination"]');
            
            // Ajouter la logique d'auto-complétion ici si nécessaire
        });
    </script>
</body>
</html>
