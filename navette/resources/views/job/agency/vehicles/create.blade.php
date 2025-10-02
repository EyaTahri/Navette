<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($vehicle) ? 'Modifier' : 'Ajouter' }} un Véhicule - {{ Auth::user()->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .form-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
        }
        .form-content {
            padding: 2rem;
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
        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .image-preview {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
        }
        .feature-tag {
            background: #e9ecef;
            border-radius: 20px;
            padding: 5px 15px;
            margin: 5px;
            display: inline-block;
            font-size: 0.9rem;
        }
        .feature-tag.selected {
            background: #667eea;
            color: white;
        }
        .section-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="form-card">
                        <!-- En-tête -->
                        <div class="form-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h1 class="display-5 fw-bold mb-2">
                                        <i class="fas fa-car me-2"></i>
                                        {{ isset($vehicle) ? 'Modifier le véhicule' : 'Ajouter un véhicule' }}
                                    </h1>
                                    <p class="lead mb-0">
                                        {{ isset($vehicle) ? 'Modifiez les informations de votre véhicule' : 'Ajoutez un nouveau véhicule à votre flotte' }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('agency.vehicles.index') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire -->
                        <div class="form-content">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h5>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ isset($vehicle) ? route('agency.vehicles.update', $vehicle->id) : route('agency.vehicles.store') }}" 
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($vehicle))
                                    @method('PUT')
                                @endif

                                <!-- Informations générales -->
                                <h4 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informations générales
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tag text-primary me-1"></i>
                                            Marque *
                                        </label>
                                        <input type="text" class="form-control" name="brand" 
                                               value="{{ old('brand', $vehicle->brand ?? '') }}" 
                                               placeholder="Ex: Peugeot, Mercedes" required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-car text-info me-1"></i>
                                            Modèle *
                                        </label>
                                        <input type="text" class="form-control" name="model" 
                                               value="{{ old('model', $vehicle->model ?? '') }}" 
                                               placeholder="Ex: 308, Sprinter" required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-bus text-success me-1"></i>
                                            Type de véhicule *
                                        </label>
                                        <select class="form-control" name="vehicle_type" required>
                                            <option value="">Choisir un type</option>
                                            <option value="Voiture" {{ old('vehicle_type', $vehicle->vehicle_type ?? '') == 'Voiture' ? 'selected' : '' }}>Voiture</option>
                                            <option value="Minibus" {{ old('vehicle_type', $vehicle->vehicle_type ?? '') == 'Minibus' ? 'selected' : '' }}>Minibus</option>
                                            <option value="Bus" {{ old('vehicle_type', $vehicle->vehicle_type ?? '') == 'Bus' ? 'selected' : '' }}>Bus</option>
                                            <option value="Van" {{ old('vehicle_type', $vehicle->vehicle_type ?? '') == 'Van' ? 'selected' : '' }}>Van</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar text-warning me-1"></i>
                                            Année *
                                        </label>
                                        <input type="number" class="form-control" name="year" 
                                               value="{{ old('year', $vehicle->year ?? '') }}" 
                                               min="1900" max="{{ date('Y') + 1 }}" required>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-users text-info me-1"></i>
                                            Capacité *
                                        </label>
                                        <input type="number" class="form-control" name="capacity" 
                                               value="{{ old('capacity', $vehicle->capacity ?? '') }}" 
                                               min="1" max="50" required>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-id-card text-danger me-1"></i>
                                            Plaque d'immatriculation *
                                        </label>
                                        <input type="text" class="form-control" name="license_plate" 
                                               value="{{ old('license_plate', $vehicle->license_plate ?? '') }}" 
                                               placeholder="Ex: AB-123-CD" required>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-palette text-secondary me-1"></i>
                                            Couleur
                                        </label>
                                        <input type="text" class="form-control" name="color" 
                                               value="{{ old('color', $vehicle->color ?? '') }}" 
                                               placeholder="Ex: Blanc, Noir">
                                    </div>
                                </div>

                                <!-- Caractéristiques techniques -->
                                <h4 class="section-title">
                                    <i class="fas fa-cogs me-2"></i>
                                    Caractéristiques techniques
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-gas-pump text-primary me-1"></i>
                                            Type de carburant *
                                        </label>
                                        <select class="form-control" name="fuel_type" required>
                                            <option value="">Choisir un carburant</option>
                                            <option value="gasoline" {{ old('fuel_type', $vehicle->fuel_type ?? '') == 'gasoline' ? 'selected' : '' }}>Essence</option>
                                            <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type ?? '') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                            <option value="electric" {{ old('fuel_type', $vehicle->fuel_type ?? '') == 'electric' ? 'selected' : '' }}>Électrique</option>
                                            <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type ?? '') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                                            <option value="lpg" {{ old('fuel_type', $vehicle->fuel_type ?? '') == 'lpg' ? 'selected' : '' }}>GPL</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-cog text-info me-1"></i>
                                            Transmission *
                                        </label>
                                        <select class="form-control" name="transmission" required>
                                            <option value="">Choisir une transmission</option>
                                            <option value="manual" {{ old('transmission', $vehicle->transmission ?? '') == 'manual' ? 'selected' : '' }}>Manuelle</option>
                                            <option value="automatic" {{ old('transmission', $vehicle->transmission ?? '') == 'automatic' ? 'selected' : '' }}>Automatique</option>
                                            <option value="semi_automatic" {{ old('transmission', $vehicle->transmission ?? '') == 'semi_automatic' ? 'selected' : '' }}>Semi-automatique</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Équipements -->
                                <h4 class="section-title">
                                    <i class="fas fa-star me-2"></i>
                                    Équipements
                                </h4>
                                
                                <div class="mb-4">
                                    <div class="row">
                                        @php
                                            $availableFeatures = [
                                                'air_conditioning' => 'Climatisation',
                                                'gps' => 'GPS',
                                                'bluetooth' => 'Bluetooth',
                                                'usb_ports' => 'Ports USB',
                                                'wifi' => 'WiFi',
                                                'leather_seats' => 'Sièges en cuir',
                                                'sunroof' => 'Toit ouvrant',
                                                'parking_sensors' => 'Capteurs de stationnement',
                                                'backup_camera' => 'Caméra de recul',
                                                'cruise_control' => 'Régulateur de vitesse',
                                                'abs' => 'ABS',
                                                'esp' => 'ESP',
                                                'airbags' => 'Airbags',
                                                'isofix' => 'Isofix',
                                                'wheelchair_access' => 'Accès fauteuil roulant'
                                            ];
                                            $selectedFeatures = old('features', $vehicle->features ?? []);
                                        @endphp
                                        
                                        @foreach($availableFeatures as $key => $label)
                                        <div class="col-md-3 col-sm-4 col-6 mb-2">
                                            <div class="feature-tag {{ in_array($key, $selectedFeatures) ? 'selected' : '' }}" 
                                                 onclick="toggleFeature('{{ $key }}')">
                                                <i class="fas fa-{{ in_array($key, $selectedFeatures) ? 'check' : 'plus' }} me-1"></i>
                                                {{ $label }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <input type="hidden" name="features" id="featuresInput" 
                                           value="{{ json_encode($selectedFeatures) }}">
                                </div>

                                <!-- Images -->
                                <h4 class="section-title">
                                    <i class="fas fa-images me-2"></i>
                                    Photos du véhicule
                                </h4>
                                
                                <div class="mb-4">
                                    <div class="image-preview" id="imagePreview">
                                        @if(isset($vehicle) && $vehicle->images)
                                            @foreach($vehicle->images as $image)
                                            <img src="{{ Storage::url($image) }}" alt="Véhicule" class="me-2">
                                            @endforeach
                                        @else
                                            <div class="text-center">
                                                <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Aucune image sélectionnée</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <input type="file" class="form-control mt-3" name="images[]" 
                                           id="imageInput" multiple accept="image/*" 
                                           onchange="previewImages(this)">
                                    <small class="text-muted">Vous pouvez sélectionner plusieurs images (max 2MB chacune)</small>
                                </div>

                                <!-- Tarifs -->
                                <h4 class="section-title">
                                    <i class="fas fa-euro-sign me-2"></i>
                                    Tarifs (optionnel)
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-calendar-day text-primary me-1"></i>
                                            Tarif journalier (€)
                                        </label>
                                        <input type="number" class="form-control" name="daily_rate" 
                                               value="{{ old('daily_rate', $vehicle->daily_rate ?? '') }}" 
                                               min="0" step="0.01">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-clock text-info me-1"></i>
                                            Tarif horaire (€)
                                        </label>
                                        <input type="number" class="form-control" name="hourly_rate" 
                                               value="{{ old('hourly_rate', $vehicle->hourly_rate ?? '') }}" 
                                               min="0" step="0.01">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-route text-success me-1"></i>
                                            Tarif au kilomètre (€)
                                        </label>
                                        <input type="number" class="form-control" name="km_rate" 
                                               value="{{ old('km_rate', $vehicle->km_rate ?? '') }}" 
                                               min="0" step="0.01">
                                    </div>
                                </div>

                                <!-- Dates importantes -->
                                <h4 class="section-title">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Dates importantes
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tools text-warning me-1"></i>
                                            Dernière maintenance
                                        </label>
                                        <input type="date" class="form-control" name="maintenance_date" 
                                               value="{{ old('maintenance_date', $vehicle->maintenance_date ?? '') }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-shield-alt text-danger me-1"></i>
                                            Expiration assurance
                                        </label>
                                        <input type="date" class="form-control" name="insurance_expiry" 
                                               value="{{ old('insurance_expiry', $vehicle->insurance_expiry ?? '') }}">
                                    </div>
                                </div>

                                <!-- Description -->
                                <h4 class="section-title">
                                    <i class="fas fa-align-left me-2"></i>
                                    Description
                                </h4>
                                
                                <div class="mb-4">
                                    <textarea class="form-control" name="description" rows="4" 
                                              placeholder="Décrivez les caractéristiques particulières de ce véhicule...">{{ old('description', $vehicle->description ?? '') }}</textarea>
                                </div>

                                @if(isset($vehicle))
                                <!-- Statut (pour l'édition) -->
                                <h4 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Statut du véhicule
                                </h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Statut</label>
                                        <select class="form-control" name="status">
                                            <option value="available" {{ old('status', $vehicle->status ?? '') == 'available' ? 'selected' : '' }}>Disponible</option>
                                            <option value="in_use" {{ old('status', $vehicle->status ?? '') == 'in_use' ? 'selected' : '' }}>En cours d'utilisation</option>
                                            <option value="maintenance" {{ old('status', $vehicle->status ?? '') == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                            <option value="out_of_service" {{ old('status', $vehicle->status ?? '') == 'out_of_service' ? 'selected' : '' }}>Hors service</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_active" 
                                                   value="1" {{ old('is_active', $vehicle->is_active ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold">
                                                Véhicule actif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Boutons d'action -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('agency.vehicles.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Annuler
                                    </a>
                                    
                                    <button type="submit" class="btn btn-submit btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        {{ isset($vehicle) ? 'Mettre à jour' : 'Créer le véhicule' }}
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
        function toggleFeature(featureKey) {
            const tag = document.querySelector(`[onclick="toggleFeature('${featureKey}')"]`);
            const featuresInput = document.getElementById('featuresInput');
            let features = JSON.parse(featuresInput.value || '[]');
            
            if (features.includes(featureKey)) {
                features = features.filter(f => f !== featureKey);
                tag.classList.remove('selected');
                tag.querySelector('i').className = 'fas fa-plus me-1';
            } else {
                features.push(featureKey);
                tag.classList.add('selected');
                tag.querySelector('i').className = 'fas fa-check me-1';
            }
            
            featuresInput.value = JSON.stringify(features);
        }

        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'me-2';
                            img.style.maxHeight = '200px';
                            img.style.borderRadius = '8px';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.innerHTML = `
                    <div class="text-center">
                        <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                        <p class="text-muted">Aucune image sélectionnée</p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>







