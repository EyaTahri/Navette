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
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
        <h1 class="m-0 text-primary">Navette</h1>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <!-- Links for USER role -->
            @if(Auth::check() && Auth::user()->role === 'USER')
                <a href="{{ route('home') }}" class="nav-item nav-link">Accueil</a>
                <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                <a href="{{ route('navettes.reservations') }}" class="nav-item nav-link active">Réservation</a>
                <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                <a href="{{ route('profile') }}"class="nav-item nav-link">Profile</a>
            @endif
            
            <!-- Links for AGENCE role -->
            @if(Auth::check() && Auth::user()->role === 'AGENCE')
                
                <a href="{{ route('profile') }}"class="nav-item nav-link ">Profile</a>
                <a href="{{ route('create_navette') }}"class="nav-item nav-link ">Créer navette</a>
                <a href="{{ route('navettes.index') }}"class="nav-item nav-link ">Gérer navette</a>
                    
                
            @endif
             <!-- Links for ADMIN role -->
             @if(Auth::check() && Auth::user()->role === 'ADMIN')
                <a href="{{ route('home') }}" class="nav-item nav-link active">Accueil</a>
                <a href="{{ route('profile') }}"class="nav-item nav-link ">Profile</a>
                
            @endif
        </div>

        <!-- Logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <a href="#" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           Log Out<i class="fa fa-arrow-right ms-3"></i>
        </a>
    </div>
</nav>
        <!-- Navbar End -->


        <!-- Header End -->
        
        <!-- Header End -->


        <!-- Jobs Start -->
                            <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Tous les navettes réserver</h1>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                    <div class="tab-content">
                    <div class="container-xxl py-5">
                    <div class="container">
                        
                        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                            <div id="tab-1" class="tab-pane fade show p-0 active">

                            @foreach($reservations as $reservation)
    <div class="job-item p-4 mb-4 navette-item" 
         data-departure="{{ strtolower(optional($reservation->navette)->departure ?? '') }}" 
         data-arrival="{{ strtolower(optional($reservation->navette)->arrival ?? '') }}" 
         data-destination="{{ strtolower(optional($reservation->navette)->destination ?? '') }}">
        <div class="row g-4">
            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                <img class="flex-shrink-0 img-fluid border rounded" src="img/com-logo-{{ $loop->index + 1 }}.jpg" alt="" style="width: 80px; height: 80px;">
                <div class="text-start ps-4">
                    <!-- Confirmer ou annuler -->
                

                    <h5 class="mb-3">{{ optional($reservation->navette)->destination ?? 'N/A' }}</h5>
                    <span class="text-truncate me-3">
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ optional($reservation->navette)->departure ?? 'N/A' }}
                    </span>
                    <span class="text-truncate me-3">
                        <i class="far fa-clock text-primary me-2"></i>{{ optional($reservation->navette)->arrival ?? 'N/A' }}
                    </span>
                    <span class="text-truncate me-0">
    <i class="far fa-money-bill-alt text-primary me-2"></i>
    {{ $reservation->total_price}}
</span>

                    <span class="text-truncate me-0">
                        @if($reservation->status === 1)
                            <span style="color: green; margin-left:2rem;font-weight:bold">Confirmed</span>
                        @elseif($reservation->status === 0)
                            <span style="color: red; margin-left:2rem;font-weight:bold">Annuler</span>
                        @else
                            <span style="color: orange; margin-left:2rem;font-weight:bold">Pending</span>
                        @endif
                    </span>

                </div>
            </div>
            <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
            <div class="d-flex mb-3">
                    <form action="{{ route('reservations.updateStatus', [$reservation->id, 'accepted']) }}" method="POST" class="me-2">
                        @csrf
                        @if($reservation->status !== 1)
                        <button type="submit" class="btn btn-success">Confirmer</button>
                        @endif
                    </form>
                    <form action="{{ route('reservations.updateStatus', [$reservation->id, 'refused']) }}" method="POST">
                        @csrf
                        @if($reservation->status !== 0)
                        <button type="submit" class="btn btn-danger">Annuler</button>
                        @endif
                    </form>
                </div>
                <small class="text-truncate"><i class="far fa-calendar-alt text-primary me-2"></i>Date: {{ $reservation->created_at->format('d M, Y') }}</small>
            </div>
        </div>
    </div>
@endforeach

                                <!-- Uncomment below if you want a button to browse more jobs -->
                                <!-- <a class="btn btn-primary py-3 px-5" href="">Browse More Jobs</a> -->
                            </div>
                        </div>
                    </div>
                </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Jobs End -->


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Company</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Contact</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved. 
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
