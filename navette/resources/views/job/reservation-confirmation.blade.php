<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation - Covoiturage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .confirmation-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .confirmation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .success-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .reservation-details {
            padding: 2rem;
        }
        .detail-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .qr-code {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        .action-buttons {
            background: #f8f9fa;
            padding: 2rem;
            border-top: 1px solid #dee2e6;
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
                @auth
                    @if(Auth::user()->role === 'ADMIN')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-shield-alt me-1"></i>
                            Admin
                        </a>
                    @endif
                    <a class="nav-link" href="{{ route('logout') }}">
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
    <div class="confirmation-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-card">
                        <!-- En-tête de succès -->
                        <div class="success-header">
                            <div class="mb-3">
                                <i class="fas fa-check-circle fa-4x"></i>
                            </div>
                            <h1 class="display-4 fw-bold mb-3">Réservation confirmée !</h1>
                            <p class="lead mb-0">Votre réservation a été créée avec succès</p>
                        </div>

                        <!-- Détails de la réservation -->
                        <div class="reservation-details">
                            <h3 class="mb-4">
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Détails de la réservation
                            </h3>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-hashtag me-1"></i>
                                            Numéro de réservation
                                        </h6>
                                        <p class="h5 mb-0">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Statut
                                        </h6>
                                        <span class="status-badge status-{{ $reservation->status }}">
                                            @switch($reservation->status)
                                                @case('pending')
                                                    <i class="fas fa-clock me-1"></i>En attente
                                                    @break
                                                @case('confirmed')
                                                    <i class="fas fa-check me-1"></i>Confirmée
                                                    @break
                                                @case('cancelled')
                                                    <i class="fas fa-times me-1"></i>Annulée
                                                    @break
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-route me-1"></i>
                                            Trajet
                                        </h6>
                                        <p class="mb-1">
                                            <strong>{{ $reservation->navette->departure }}</strong> 
                                            <i class="fas fa-arrow-right mx-2"></i>
                                            <strong>{{ $reservation->navette->destination }}</strong>
                                        </p>
                                        <small class="text-muted">
                                            {{ $reservation->navette->departure_datetime ? $reservation->navette->departure_datetime->format('d/m/Y à H:i') : 'Date à définir' }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-car me-1"></i>
                                            Véhicule
                                        </h6>
                                        <p class="mb-1">{{ $reservation->navette->vehicle_type }} - {{ $reservation->navette->brand }}</p>
                                        <small class="text-muted">Capacité: {{ $reservation->navette->capacity }} places</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-users me-1"></i>
                                            Passagers
                                        </h6>
                                        <p class="mb-0">{{ $reservation->passenger_count }} passager{{ $reservation->passenger_count > 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-euro-sign me-1"></i>
                                            Prix total
                                        </h6>
                                        <p class="h5 mb-0 text-success">{{ number_format($reservation->total_price, 2) }} €</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-phone me-1"></i>
                                            Contact
                                        </h6>
                                        <p class="mb-0">{{ $reservation->contact_phone }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-credit-card me-1"></i>
                                            Paiement
                                        </h6>
                                        <p class="mb-0">
                                            @switch($reservation->payment_method)
                                                @case('cash')
                                                    <i class="fas fa-money-bill-wave me-1"></i>Espèces
                                                    @break
                                                @case('card')
                                                    <i class="fas fa-credit-card me-1"></i>Carte bancaire
                                                    @break
                                                @case('paypal')
                                                    <i class="fab fa-paypal me-1"></i>PayPal
                                                    @break
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($reservation->special_requests)
                            <div class="detail-item">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fas fa-comment-alt me-1"></i>
                                    Demandes spéciales
                                </h6>
                                <p class="mb-0">{{ $reservation->special_requests }}</p>
                            </div>
                            @endif

                            <div class="detail-item">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Date de réservation
                                </h6>
                                <p class="mb-0">{{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <!-- Code QR (simulation) -->
                        <div class="text-center p-4">
                            <div class="qr-code d-inline-block">
                                <i class="fas fa-qrcode fa-3x text-muted mb-2"></i>
                                <p class="mb-0 text-muted">Code QR de réservation</p>
                                <small class="text-muted">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</small>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="action-buttons">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('reservation.show', $reservation->id) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-eye me-2"></i>
                                        Voir les détails
                                    </a>
                                </div>
                                
                                <div class="col-md-4">
                                    <a href="{{ route('navettes.reservations') }}" class="btn btn-outline-info w-100">
                                        <i class="fas fa-list me-2"></i>
                                        Mes réservations
                                    </a>
                                </div>
                                
                                <div class="col-md-4">
                                    <a href="{{ route('search.index') }}" class="btn btn-outline-success w-100">
                                        <i class="fas fa-search me-2"></i>
                                        Nouvelle recherche
                                    </a>
                                </div>
                            </div>
                            
                            @if($reservation->status === 'pending')
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <form action="{{ route('reservation.cancel', $reservation->id) }}" method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-times me-2"></i>
                                            Annuler la réservation
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>







