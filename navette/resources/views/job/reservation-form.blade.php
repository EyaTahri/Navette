<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - {{ $navette->departure }} vers {{ $navette->destination }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .reservation-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .reservation-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .navette-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 2rem;
            border-bottom: 1px solid #dee2e6;
        }
        .form-section {
            padding: 2rem;
        }
        .price-display {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-reserve {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-reserve:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .special-offer-badge {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .passenger-counter {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .counter-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .counter-btn:hover {
            background: #667eea;
            color: white;
        }
        .counter-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
            min-width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="reservation-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="reservation-card">
                        <!-- Informations sur la navette -->
                        <div class="navette-info">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-3">
                                        <i class="fas fa-route text-primary me-2"></i>
                                        {{ $navette->departure }} 
                                        <i class="fas fa-arrow-right mx-3 text-muted"></i>
                                        {{ $navette->destination }}
                                    </h2>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <i class="fas fa-calendar-alt text-success me-2"></i>
                                                <strong>Date de départ :</strong>
                                                {{ $navette->departure_datetime ? $navette->departure_datetime->format('d/m/Y H:i') : 'À définir' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <i class="fas fa-car text-info me-2"></i>
                                                <strong>Véhicule :</strong>
                                                {{ $navette->vehicle_type }} - {{ $navette->brand }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-0">
                                                <i class="fas fa-users text-warning me-2"></i>
                                                <strong>Capacité :</strong>
                                                {{ $navette->capacity }} places
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-0">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <strong>Disponibles :</strong>
                                                {{ $availableSeats }} places
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 text-end">
                                    @if($navette->is_special_offer)
                                    <div class="special-offer-badge mb-3">
                                        <i class="fas fa-star me-1"></i>
                                        OFFRE SPÉCIALE -{{ $navette->discount_percentage }}%
                                    </div>
                                    @endif
                                    
                                    <div class="price-display">
                                        <div class="h4 mb-1">{{ number_format($navette->price_per_person, 2) }} €</div>
                                        <small>par personne</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de réservation -->
                        <div class="form-section">
                            <h3 class="mb-4">
                                <i class="fas fa-edit text-primary me-2"></i>
                                Détails de la réservation
                            </h3>

                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('reservation.store') }}" method="POST" id="reservationForm">
                                @csrf
                                <input type="hidden" name="navette_id" value="{{ $navette->id }}">

                                <!-- Nombre de passagers -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-users text-primary me-1"></i>
                                            Nombre de passagers
                                        </label>
                                        <div class="passenger-counter">
                                            <button type="button" class="counter-btn" onclick="decreasePassengers()">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="counter-display" id="passengerCount">1</span>
                                            <button type="button" class="counter-btn" onclick="increasePassengers()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="passenger_count" id="passengerCountInput" value="1">
                                        <small class="text-muted">Maximum {{ $availableSeats }} places disponibles</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-euro-sign text-success me-1"></i>
                                            Prix total
                                        </label>
                                        <div class="price-display" id="totalPriceDisplay">
                                            <div class="h4 mb-1" id="totalPrice">{{ number_format($totalPrice, 2) }} €</div>
                                            <small id="priceBreakdown">1 passager</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations de contact -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-phone text-info me-1"></i>
                                            Téléphone de contact *
                                        </label>
                                        <input type="tel" class="form-control" name="contact_phone" 
                                               value="{{ old('contact_phone', Auth::user()->contactdetails ?? '') }}" 
                                               placeholder="06 12 34 56 78" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-credit-card text-warning me-1"></i>
                                            Mode de paiement *
                                        </label>
                                        <select class="form-control" name="payment_method" required>
                                            <option value="">Choisir un mode de paiement</option>
                                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                            <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Demandes spéciales -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-comment-alt text-secondary me-1"></i>
                                        Demandes spéciales (optionnel)
                                    </label>
                                    <textarea class="form-control" name="special_requests" rows="3" 
                                              placeholder="Ex: Siège enfant, bagages volumineux, arrêt spécifique...">{{ old('special_requests') }}</textarea>
                                </div>

                                <!-- Informations utilisateur -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-user text-primary me-1"></i>
                                            Nom complet
                                        </label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-envelope text-info me-1"></i>
                                            Email
                                        </label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Retour
                                    </a>
                                    
                                    <button type="submit" class="btn btn-reserve btn-lg">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Confirmer la réservation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let passengerCount = 1;
        const maxPassengers = {{ $availableSeats }};
        const basePrice = {{ $navette->price_per_person }};
        const discountPercentage = {{ $navette->discount_percentage ?? 0 }};
        const isSpecialOffer = {{ $navette->is_special_offer ? 'true' : 'false' }};

        function updatePassengerCount() {
            document.getElementById('passengerCount').textContent = passengerCount;
            document.getElementById('passengerCountInput').value = passengerCount;
            updatePrice();
        }

        function increasePassengers() {
            if (passengerCount < maxPassengers) {
                passengerCount++;
                updatePassengerCount();
            }
        }

        function decreasePassengers() {
            if (passengerCount > 1) {
                passengerCount--;
                updatePassengerCount();
            }
        }

        function updatePrice() {
            let totalPrice = basePrice * passengerCount;
            
            if (isSpecialOffer && discountPercentage > 0) {
                const discount = totalPrice * (discountPercentage / 100);
                totalPrice = totalPrice - discount;
            }

            document.getElementById('totalPrice').textContent = totalPrice.toFixed(2) + ' €';
            
            const breakdown = passengerCount === 1 ? '1 passager' : passengerCount + ' passagers';
            if (isSpecialOffer && discountPercentage > 0) {
                document.getElementById('priceBreakdown').textContent = 
                    breakdown + ' (remise -' + discountPercentage + '%)';
            } else {
                document.getElementById('priceBreakdown').textContent = breakdown;
            }
        }

        // Initialiser le prix
        updatePrice();
    </script>
</body>
</html>






