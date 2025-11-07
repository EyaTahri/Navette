<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Connexion / Inscription</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-hero{background:linear-gradient(135deg,rgba(9,132,227,.95),rgba(0,184,148,.95)),url('https://images.unsplash.com/photo-1518684079-3c830dcef090?q=80&w=1600&auto=format&fit=crop');background-size:cover;background-position:center;padding:3rem 0;margin-bottom:2rem}
        .brand-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.15);backdrop-filter:blur(6px);border-radius:999px;color:#fff;padding:.35rem .75rem;font-weight:600}
        .auth-card{background:#fff;border-radius:18px;box-shadow:0 20px 50px rgba(0,0,0,.12)}
        .feature{display:flex;align-items:center;gap:.75rem;color:#eaf7ff}
        .feature .icon{width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center}
        .form-control{border-radius:12px;border:2px solid #eef1f4;padding:12px 15px}
        .form-control:focus{border-color:#00b894;box-shadow:0 0 0 .2rem rgba(0,184,148,.18)}
        .btn-primary{background:linear-gradient(135deg,#0984e3,#00b894);border:none;border-radius:12px}
        .btn-outline-secondary{border-radius:12px}
        .switch-link{cursor:pointer;color:#0984e3}
        .switch-link:hover{text-decoration:underline}
        .trust-bar{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center}
        .trust-item{color:#dff7ff;display:flex;align-items:center;gap:.5rem}
        .hero-carousel{margin-top:2rem;border-radius:18px;overflow:hidden;box-shadow:0 20px 45px rgba(0,0,0,.2);background:rgba(0,0,0,.2)}
        .hero-carousel .carousel-item{height:220px}
        .hero-carousel img{width:100%;height:100%;object-fit:cover;transform:scale(1.12);transition:transform 5s ease}
        .hero-carousel .carousel-item.active img{transform:scale(1)}
        .carousel-control-prev,.carousel-control-next{filter:invert(1);opacity:.85}
    </style>
</head>
<body>

    <div class="auth-hero">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="text-center text-white mb-3">
              <div class="brand-badge"><i class="fa-solid fa-car-side"></i> Covoiturage Express</div>
              <h1 class="fw-bold mt-2">Rejoignez la route intelligente</h1>
              <p class="mb-0">Réservez vos trajets en toute simplicité et modernité</p>
            </div>
            <div class="trust-bar mt-3">
              <div class="trust-item"><i class="fa-solid fa-clock"></i> Réservation rapide, simple et moderne</div>
            </div>
            <div id="heroCarousel" class="hero-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="https://images.unsplash.com/photo-1493238792000-8113da705763?auto=format&fit=crop&w=1600&q=80" alt="Partage de trajet" />
                </div>
                <div class="carousel-item">
                  <img src="https://images.unsplash.com/photo-1529429617124-aee175571e8f?auto=format&fit=crop&w=1600&q=80" alt="Voiture en déplacement" />
                </div>
                <div class="carousel-item">
                  <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80" alt="Covoitureurs heureux" />
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mb-5">
      <div class="row justify-content-center">
        <div class="col-xl-8 col-xxl-7">
          <div class="auth-card p-0 overflow-hidden">
            <div class="row g-0">
              <div class="col-12 p-4 p-lg-5">
                @if ($errors->any())
                  <div id="login-error-messages" class="d-none">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
                <div class="mb-3">
                  <h2 class="h4 mb-1">Connexion</h2>
                  <p class="text-muted mb-0">Accédez à votre compte pour réserver vos navettes</p>
                </div>
                <form id="login-form" action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label">Adresse email</label>
                    <input type="email" name="email" class="form-control" placeholder="ex: jean@exemple.com" required />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" placeholder="********" required />
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="remember" name="remember">
                      <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                  <a href="{{ route('signup') }}" class="btn btn-outline-secondary w-100 mt-2" style="text-decoration:none;">Vous n'avez pas de compte ? Créer un compte</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>

    <div class="modal fade" id="loginAlertModal" tabindex="-1" aria-labelledby="loginAlertModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="loginAlertModalLabel"><i class="fa-solid fa-circle-exclamation text-warning me-2"></i>Connexion refusée</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p id="loginAlertModalMessage" class="mb-0"></p>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Compris</button>
          </div>
        </div>
      </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const loginErrors = document.getElementById('login-error-messages');
      const loginAlertModalElement = document.getElementById('loginAlertModal');
      const loginAlertModalMessage = document.getElementById('loginAlertModalMessage');
      const loginAlertModal = loginAlertModalElement ? new bootstrap.Modal(loginAlertModalElement) : null;

      if (loginErrors && loginAlertModal && loginAlertModalMessage) {
        const messages = Array.from(loginErrors.querySelectorAll('li')).map((li) => li.textContent.trim()).filter(Boolean);
        if (messages.length) {
          loginAlertModalMessage.textContent = messages.join('\n');
          loginAlertModal.show();
        }
      }
    </script>
</body>
</html>
