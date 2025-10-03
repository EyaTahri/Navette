<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Navette</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        @include('job.partials.navbar')

        <!-- Profile Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <!-- <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Profile</h1> -->
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-md-3 wow fadeIn" data-wow-delay="0.1s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-user text-primary"></i>
                                    </div>
                                    <span>Name: {{ $user->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 wow fadeIn" data-wow-delay="0.3s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <span>Email: {{ $user->email }}</span>
                                </div>
                            </div>
                            @if(Auth::check() && Auth::user()->role !== 'AGENCE')
                            <div class="col-md-3 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <span>Contact: {{ $user->contactdetails }}</span>
                                </div>
                            </div>
                            @endif
                            @if(Auth::check() && Auth::user()->role === 'AGENCE')
                            <div class="col-md-3 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <span>Place: {{ $user->place }}</span>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-user-shield text-primary"></i>
                                    </div>
                                    <span>Role: {{ $user->role }}</span>
                                </div>
                            </div>
                            @if(Auth::check() && Auth::user()->role === 'ADMIN')
                            <div class="col-md-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="d-flex align-items-center bg-light rounded p-4">
                                    <div class="bg-white border rounded d-flex flex-shrink-0 align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fa fa-user text-primary"></i>
                                    </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                                    Créer Compte Agence 
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile End -->
         <!-- Create agence -->



        
<div class="container-xxl py-5" style="background: linear-gradient(135deg, rgba(102,126,234,0.06), rgba(118,75,162,0.06));">
    <div class="container">
        <div class="hero py-3 mb-3">
            <h1 class="h3"><i class="fas fa-user me-2"></i>Mon profil</h1>
        </div>
        <h2 class="mb-4">Navettes disponibles</h2>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
            <div class="tab-content">
                <div class="container-xxl py-5">
                    <div class="container">
                        
                        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                            <div id="tab-1" class="tab-pane fade show p-0 active">
                                @if ($navettes->isEmpty())
                                    <p>Aucune navette disponible pour le moment.</p>
                                @else
                                @foreach($navettes as $navette)
                                
    <div class="job-item p-4 mb-4">
        <div class="row g-4">
            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                <img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-{{ $loop->index + 1 }}.jpg" alt="" style="width: 80px; height: 80px;">
                <div class="text-start ps-4">
                    <h5 class="mb-3">{{ $navette->destination }}</h5>
                    <span class="text-truncate me-3">
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $navette->departure }}
                    </span>
                    <span class="text-truncate me-3">
                        <i class="far fa-clock text-primary me-2"></i>{{ $navette->arrival }}
                    </span>
                    <!-- pending accepted or refused -->
                   
                    <span class="text-truncate me-0">
    <i class="far fa-money-bill-alt text-primary me-2"></i>
    @php
        $pricePerPerson = ($navette->price_per_person ?? 0);
        $vehiclePrice = ($navette->vehicle_price) ?? 0;
        $brandPrice = ($navette->brand_price) ?? 0;

        $totalPrice = $pricePerPerson * $vehiclePrice + $brandPrice;
    @endphp
    ${{ $totalPrice > 0 ? number_format($totalPrice, 2) : 'N/A' }} DT
</span>
                    <span class="text-truncate me-0">
                        @if($navette->accepted  === 1)
                            <span style="color: green;">Confirmed</span>
                        @elseif($navette->accepted === 0)
                            <span style="color: red;">Rejected</span>
                        @else
                            <span style="color: orange;">Pending</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                <div class="d-flex mb-3">
                    <a class="btn btn-primary" href="{{ route('reservation.create', $navette->id) }}">Réserver</a>
                </div>
                <small class="text-truncate">
                    <i class="far fa-calendar-alt text-primary me-2"></i>Date Line: 01 Jan, 2045
                </small>
            </div>
        </div>
    </div>
@endforeach

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>



<hr class="my-5">

<div class="container-xxl py-5">
  <div class="container">
    <h2 class="mb-4">Mon historique de réservations</h2>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Trajet</th>
            <th>Date</th>
            <th>Passagers</th>
            <th>Prix</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservations as $reservation)
          <tr>
            <td>{{ $reservation->id }}</td>
            <td>{{ $reservation->navette->departure }} → {{ $reservation->navette->destination }}</td>
            <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $reservation->passenger_count }}</td>
            <td>{{ number_format($reservation->total_price, 2) }} €</td>
            <td>{{ ucfirst($reservation->status) }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Aucune réservation</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<hr class="my-5">

<div class="container-xxl py-5">
  <div class="container">
    <h2 class="mb-4">Mes navettes (brouillons)</h2>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Trajet</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($myNavettes as $n)
          <tr>
            <td>{{ $n->id }}</td>
            <td>{{ $n->departure }} → {{ $n->destination }}</td>
            <td>
              @if(is_null($n->accepted))
                <span class="badge bg-warning text-dark">En attente</span>
              @elseif($n->accepted)
                <span class="badge bg-success">Acceptée</span>
              @else
                <span class="badge bg-danger">Refusée</span>
              @endif
            </td>
            <td>
              <div class="d-flex gap-2">
                @if(is_null($n->accepted))
                  <a href="{{ route('edit_navette', $n->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                  <form action="{{ route('delete_navette', $n->id) }}" method="POST" onsubmit="return confirm('Supprimer cette navette ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Supprimer</button>
                  </form>
                @else
                  <small class="text-muted">Modification/suppression indisponible</small>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" class="text-center text-muted">Aucune navette</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalLabel">Créer Compte Agence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('registerAgence') }}" class="register-form" id="register-form">
                    @csrf
                    <div class="form-group mb-3">
                        
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nom de l'agence" required />
                    </div>
                    <div class="form-group mb-3">
                        
                        <input type="text" name="lieu" id="lieu" class="form-control" placeholder="Lieu de l'agence" required />
                    </div>
                    <div class="form-group mb-3">
                        
                        <input type="email" name="email" id="email" class="form-control" placeholder="Adresse email" required />
                    </div>
                    <div class="form-group mb-3">
                        
                        <input type="text" name="contactdetails" id="contactdetails" class="form-control" placeholder="contact details" required />
                    </div>
                    <div class="form-group mb-3" >
                
                        <input type="password" name="password" id="pass" class="form-control" placeholder="Password" required />
                    </div>
                    <div class="form-group mb-3">
                        
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Créer agence</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
</div>


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
