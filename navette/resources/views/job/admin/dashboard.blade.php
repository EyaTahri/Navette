<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrateur - Covoiturage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem 0;
            margin: 1rem;
            height: calc(100vh - 2rem);
            position: sticky;
            top: 1rem;
        }
        .sidebar-item {
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-item:hover, .sidebar-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-left-color: white;
            color: white;
        }
        .main-content {
            background: white;
            border-radius: 20px;
            margin: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }
        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }
        .stat-card.danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
        }
        .stat-card.info {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="sidebar">
                        <div class="text-center mb-4">
                            <h4 class="text-white fw-bold">
                                <i class="fas fa-shield-alt me-2"></i>
                                Admin Panel
                            </h4>
                        </div>
                        
                        <nav class="nav flex-column">
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('admin.users') }}" class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i>
                                Utilisateurs
                            </a>
                            <a href="{{ route('admin.agencies') }}" class="sidebar-item {{ request()->routeIs('admin.agencies') ? 'active' : '' }}">
                                <i class="fas fa-building me-2"></i>
                                Agences
                            </a>
                            <a href="{{ route('admin.navettes') }}" class="sidebar-item {{ request()->routeIs('admin.navettes') ? 'active' : '' }}">
                                <i class="fas fa-route me-2"></i>
                                Navettes
                            </a>
                            <a href="{{ route('admin.reservations') }}" class="sidebar-item {{ request()->routeIs('admin.reservations') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check me-2"></i>
                                Réservations
                            </a>
                            <a href="{{ route('admin.vehicles') }}" class="sidebar-item {{ request()->routeIs('admin.vehicles') ? 'active' : '' }}">
                                <i class="fas fa-car me-2"></i>
                                Véhicules
                            </a>
                            <a href="{{ route('admin.statistics') }}" class="sidebar-item {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                Statistiques
                            </a>
                        </nav>
                        
                        <div class="mt-auto p-3">
                            <div class="text-center">
                                <small class="text-white-50">Connecté en tant que</small>
                                <div class="fw-bold text-white">{{ Auth::user()->name }}</div>
                                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm mt-2">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="col-md-9">
                    <div class="main-content">
                        <!-- En-tête -->
                        <div class="admin-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h1 class="display-5 fw-bold mb-2">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        Dashboard Administrateur
                                    </h1>
                                    <p class="lead mb-0">Vue d'ensemble du système de covoiturage</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="text-white">
                                        <small>Dernière mise à jour</small>
                                        <div class="fw-bold">{{ now()->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques principales -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fw-bold mb-1">{{ $stats['total_users'] }}</h3>
                                            <small>Utilisateurs</small>
                                        </div>
                                        <i class="fas fa-users fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fw-bold mb-1">{{ $stats['total_agencies'] }}</h3>
                                            <small>Agences</small>
                                        </div>
                                        <i class="fas fa-building fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fw-bold mb-1">{{ $stats['total_reservations'] }}</h3>
                                            <small>Réservations</small>
                                        </div>
                                        <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="fw-bold mb-1">{{ number_format($revenue, 0) }} €</h3>
                                            <small>Revenus (30j)</small>
                                        </div>
                                        <i class="fas fa-euro-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Graphiques -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="chart-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Réservations des 6 derniers mois
                                    </h5>
                                    <canvas id="reservationsChart" height="100"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="chart-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        Véhicules par type
                                    </h5>
                                    <canvas id="vehiclesChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Alertes et actions -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="table-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        Navettes en attente ({{ $pendingNavettes->count() }})
                                    </h5>
                                    @if($pendingNavettes->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Trajet</th>
                                                        <th>Agence</th>
                                                        <th>Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingNavettes as $navette)
                                                    <tr>
                                                        <td>
                                                            <small>{{ $navette->departure }} → {{ $navette->destination }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $navette->creator->name }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $navette->created_at->format('d/m/Y') }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('navettes.accept', $navette->id) }}" 
                                                                   class="btn btn-success btn-sm">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="{{ route('navettes.refuse', $navette->id) }}" 
                                                                   class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center py-3">Aucune navette en attente</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="table-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-building text-info me-2"></i>
                                        Agences en attente ({{ $pendingAgencies->count() }})
                                    </h5>
                                    @if($pendingAgencies->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Email</th>
                                                        <th>Lieu</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingAgencies as $agency)
                                                    <tr>
                                                        <td>
                                                            <small>{{ $agency->name }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $agency->email }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $agency->place }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.approveAgency', $agency->id) }}" 
                                                                   class="btn btn-success btn-sm">
                                                                    <i class="fas fa-check"></i>
                                                                </a>
                                                                <a href="{{ route('admin.rejectAgency', $agency->id) }}" 
                                                                   class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center py-3">Aucune agence en attente</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Top destinations et réservations récentes -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        Top destinations
                                    </h5>
                                    @if($topDestinations->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($topDestinations as $destination)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>{{ $destination->destination }}</span>
                                                <span class="badge bg-primary rounded-pill">{{ $destination->count }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted text-center py-3">Aucune donnée disponible</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="table-container">
                                    <h5 class="fw-bold mb-3">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        Réservations récentes
                                    </h5>
                                    @if($recentReservations->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Client</th>
                                                        <th>Trajet</th>
                                                        <th>Prix</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentReservations as $reservation)
                                                    <tr>
                                                        <td>
                                                            <small>{{ optional($reservation->user)->name ?? 'Utilisateur supprimé' }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ $reservation->navette->departure }} → {{ $reservation->navette->destination }}</small>
                                                        </td>
                                                        <td>
                                                            <small>{{ number_format($reservation->total_price, 2) }} €</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge-status status-{{ $reservation->status }}">
                                                                {{ ucfirst($reservation->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center py-3">Aucune réservation récente</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Graphique des réservations
        const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
        new Chart(reservationsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($reservationsChart->pluck('month')->map(function($item) {
                    return Carbon\Carbon::create()->month($item)->format('M');
                })) !!},
                datasets: [{
                    label: 'Réservations',
                    data: {!! json_encode($reservationsChart->pluck('count')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique des véhicules par type
        const vehiclesCtx = document.getElementById('vehiclesChart').getContext('2d');
        new Chart(vehiclesCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($vehiclesByType->pluck('vehicle_type')) !!},
                datasets: [{
                    data: {!! json_encode($vehiclesByType->pluck('count')) !!},
                    backgroundColor: [
                        '#667eea',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>







