<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de réservation - Covoiturage</title>
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
        .timeline-item.completed::before {
            background: #28a745;
            box-shadow: 0 0 0 3px #28a745;
        }
        .timeline-item.cancelled::before {
            background: #dc3545;
            box-shadow: 0 0 0 3px #dc3545;
        }
    </style>
</head>
<body>
    <div class="details-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="details-card">
                        <!-- En-tête -->
                        <div class="details-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h1 class="display-5 fw-bold mb-2">
                                        Réservation #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}
                                    </h1>
                                    <p class="lead mb-0">
                                        {{ $reservation->navette->departure }} 
                                        <i class="fas fa-arrow-right mx-2"></i>
                                        {{ $reservation->navette->destination }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
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

                        <!-- Contenu principal -->
                        <div class="details-content">
                            <div class="row">
                                <!-- Informations principales -->
                                <div class="col-lg-8">
                                    <!-- Informations du trajet -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-route me-2"></i>
                                            Informations du trajet
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Départ :</strong> {{ $reservation->navette->departure }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Destination :</strong> {{ $reservation->navette->destination }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Date :</strong> 
                                                    {{ $reservation->navette->departure_datetime ? $reservation->navette->departure_datetime->format('d/m/Y') : 'À définir' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Heure :</strong> 
                                                    {{ $reservation->navette->departure_datetime ? $reservation->navette->departure_datetime->format('H:i') : 'À définir' }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Véhicule :</strong> {{ $reservation->navette->vehicle_type }} - {{ $reservation->navette->brand }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Capacité :</strong> {{ $reservation->navette->capacity }} places
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informations de réservation -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-user me-2"></i>
                                            Informations de réservation
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Passagers :</strong> {{ $reservation->passenger_count }} passager{{ $reservation->passenger_count > 1 ? 's' : '' }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Contact :</strong> {{ $reservation->contact_phone }}
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Prix total :</strong> 
                                                    <span class="h5 text-success">{{ number_format($reservation->total_price, 2) }} €</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-2">
                                                    <strong>Mode de paiement :</strong>
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
                                                <p class="mb-2">
                                                    <strong>Statut paiement :</strong>
                                                    <span class="badge bg-{{ $reservation->payment_status === 'paid' ? 'success' : ($reservation->payment_status === 'refunded' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($reservation->payment_status) }}
                                                    </span>
                                                </p>
                                                <p class="mb-2">
                                                    <strong>Date réservation :</strong> {{ $reservation->created_at->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($reservation->special_requests)
                                    <!-- Demandes spéciales -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-comment-alt me-2"></i>
                                            Demandes spéciales
                                        </h4>
                                        <p class="mb-0">{{ $reservation->special_requests }}</p>
                                    </div>
                                    @endif

                                    @if($reservation->navette->description)
                                    <!-- Description du véhicule -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Description du véhicule
                                        </h4>
                                        <p class="mb-0">{{ $reservation->navette->description }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Timeline et actions -->
                                <div class="col-lg-4">
                                    <!-- Timeline du statut -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-history me-2"></i>
                                            Historique
                                        </h4>
                                        <div class="timeline">
                                            <div class="timeline-item completed">
                                                <h6 class="fw-bold mb-1">Réservation créée</h6>
                                                <small class="text-muted">{{ $reservation->created_at->format('d/m/Y à H:i') }}</small>
                                            </div>
                                            
                                            @if($reservation->status === 'confirmed')
                                            <div class="timeline-item completed">
                                                <h6 class="fw-bold mb-1">Réservation confirmée</h6>
                                                <small class="text-muted">{{ $reservation->updated_at->format('d/m/Y à H:i') }}</small>
                                            </div>
                                            @elseif($reservation->status === 'cancelled')
                                            <div class="timeline-item cancelled">
                                                <h6 class="fw-bold mb-1">Réservation annulée</h6>
                                                <small class="text-muted">{{ $reservation->updated_at->format('d/m/Y à H:i') }}</small>
                                            </div>
                                            @else
                                            <div class="timeline-item">
                                                <h6 class="fw-bold mb-1">En attente de confirmation</h6>
                                                <small class="text-muted">En cours...</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-cogs me-2"></i>
                                            Actions
                                        </h4>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('navettes.reservations') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-list me-2"></i>
                                                Mes réservations
                                            </a>
                                            
                                            <a href="{{ route('search.index') }}" class="btn btn-outline-success">
                                                <i class="fas fa-search me-2"></i>
                                                Nouvelle recherche
                                            </a>
                                            
                                            @if($reservation->status === 'pending')
                                            <form action="{{ route('reservation.cancel', $reservation->id) }}" method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                    <i class="fas fa-times me-2"></i>
                                                    Annuler la réservation
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informations de contact -->
                                    <div class="info-section">
                                        <h4 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-headset me-2"></i>
                                            Support
                                        </h4>
                                        <p class="mb-2">
                                            <i class="fas fa-phone me-2"></i>
                                            <strong>Support client :</strong><br>
                                            <small>01 23 45 67 89</small>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-envelope me-2"></i>
                                            <strong>Email :</strong><br>
                                            <small>support@covoiturage.com</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>







