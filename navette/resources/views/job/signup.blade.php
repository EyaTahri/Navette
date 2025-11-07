<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inscription</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-hero{background:linear-gradient(135deg,rgba(9,132,227,.95),rgba(0,184,148,.95)),url('https://images.unsplash.com/photo-1518684079-3c830dcef090?q=80&w=1600&auto=format&fit=crop');background-size:cover;background-position:center;padding:3rem 0;margin-bottom:2rem}
        .brand-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.15);backdrop-filter:blur(6px);border-radius:999px;color:#fff;padding:.35rem .75rem;font-weight:600}
        .auth-card{background:#fff;border-radius:18px;box-shadow:0 20px 50px rgba(0,0,0,.12)}
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
        .nav-pills .nav-link{border-radius:12px;font-weight:600;color:#6c757d}
        .nav-pills .nav-link.active{background:linear-gradient(135deg,#0984e3,#00b894)}
        .auth-card h2{font-weight:700}
    </style>
    </head>
<body>

    <div class="auth-hero">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="text-center text-white mb-3">
              <div class="brand-badge"><i class="fa-solid fa-car-side"></i> Covoiturage Express</div>
              <h1 class="fw-bold mt-2">Inscrivez-vous et prenez la route</h1>
              <p class="mb-0">Devenez covoitureur ou agence en quelques minutes</p>
            </div>
            <div class="trust-bar mt-3">
              <div class="trust-item"><i class="fa-solid fa-clock"></i> Réservation rapide, simple et moderne</div>
              <div class="trust-item"><i class="fa-solid fa-users"></i> Communauté vérifiée</div>
            </div>
            <div id="signupHeroCarousel" class="hero-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="https://images.unsplash.com/photo-1526726538690-5cbf956ae2fd?q=80&w=1600&auto=format&fit=crop" alt="Inscription covoiturage" />
                </div>
                <div class="carousel-item">
                  <img src="https://images.unsplash.com/photo-1517677129300-07b130802f46?q=80&w=1600&auto=format&fit=crop" alt="Voyage partagé" />
                </div>
                <div class="carousel-item">
                  <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1600&auto=format&fit=crop" alt="Equipe agence" />
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#signupHeroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#signupHeroCarousel" data-bs-slide="next">
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
        <div class="col-xl-10 col-xxl-9">
          <div class="auth-card p-4 p-lg-5">
            @if ($errors->any())
              <div id="server-error-messages" class="d-none">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <ul class="nav nav-pills mb-3" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="signup-user-tab" data-bs-toggle="pill" data-bs-target="#signup-user-pane" type="button" role="tab">Inscription Utilisateur</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="signup-agency-tab" data-bs-toggle="pill" data-bs-target="#signup-agency-pane" type="button" role="tab">Inscription Agence</button>
              </li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane fade show active" id="signup-user-pane" role="tabpanel" aria-labelledby="signup-user-tab">
                <form method="POST" action="{{ route('register') }}" id="register-form-user">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Nom complet</label>
                      <input type="text" name="name" class="form-control" placeholder="Nom" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Téléphone</label>
                      <div class="input-group">
                        <span class="input-group-text">+216</span>
                        <input type="text" name="contactdetails" class="form-control" placeholder="12 345 678" pattern="[0-9]{8}" title="Entrez un numéro tunisien à 8 chiffres" required />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="jean@exemple.com" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Mot de passe</label>
                      <input type="password" name="password" class="form-control" placeholder="********" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Confirmer le mot de passe</label>
                      <input type="password" name="password_confirmation" class="form-control" placeholder="********" required />
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 mt-3">Créer mon compte</button>
                </form>
              </div>

              <div class="tab-pane fade" id="signup-agency-pane" role="tabpanel" aria-labelledby="signup-agency-tab">
                <form method="POST" action="{{ route('registerAgence') }}" id="register-form-agency">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Nom de l'agence</label>
                      <input type="text" name="name" class="form-control" placeholder="Agence X" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Lieu</label>
                      <select name="lieu" class="form-select" required>
                        <option value="" disabled selected>Choisir une gouvernance</option>
                        <option value="Ariana">Ariana</option>
                        <option value="Béja">Béja</option>
                        <option value="Ben Arous">Ben Arous</option>
                        <option value="Bizerte">Bizerte</option>
                        <option value="Gabès">Gabès</option>
                        <option value="Gafsa">Gafsa</option>
                        <option value="Jendouba">Jendouba</option>
                        <option value="Kairouan">Kairouan</option>
                        <option value="Kasserine">Kasserine</option>
                        <option value="Kebili">Kebili</option>
                        <option value="Le Kef">Le Kef</option>
                        <option value="Mahdia">Mahdia</option>
                        <option value="Manouba">Manouba</option>
                        <option value="Médenine">Médenine</option>
                        <option value="Monastir">Monastir</option>
                        <option value="Nabeul">Nabeul</option>
                        <option value="Sfax">Sfax</option>
                        <option value="Sidi Bouzid">Sidi Bouzid</option>
                        <option value="Siliana">Siliana</option>
                        <option value="Sousse">Sousse</option>
                        <option value="Tataouine">Tataouine</option>
                        <option value="Tozeur">Tozeur</option>
                        <option value="Tunis">Tunis</option>
                        <option value="Zaghouan">Zaghouan</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Téléphone</label>
                      <div class="input-group">
                        <span class="input-group-text">+216</span>
                        <input type="text" name="contactdetails" class="form-control" placeholder="12 345 678" pattern="[0-9]{8}" title="Entrez un numéro tunisien à 8 chiffres" required />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="contact@agence.com" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Mot de passe</label>
                      <input type="password" name="password" class="form-control" placeholder="********" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Confirmer le mot de passe</label>
                      <input type="password" name="password_confirmation" class="form-control" placeholder="********" required />
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">J'accepte les politiques du site</label>
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 mt-3">Créer mon agence</button>
                </form>
              </div>
            </div>

            <div class="mt-3 text-center">
              <a href="{{ route('login') }}" class="switch-link">Déjà un compte ? Se connecter</a>
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="alertModalLabel"><i class="fa-solid fa-circle-exclamation text-warning me-2"></i>Attention</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <p id="alertModalMessage" class="mb-0"></p>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Compris</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const userForm = document.getElementById('register-form-user');
      const agencyForm = document.getElementById('register-form-agency');
      const alertModalElement = document.getElementById('alertModal');
      const alertModalMessage = document.getElementById('alertModalMessage');
      const alertModal = alertModalElement ? new bootstrap.Modal(alertModalElement) : null;

      function showAlert(message) {
        if (alertModal && alertModalMessage) {
          alertModalMessage.textContent = message;
          alertModal.show();
        } else {
          window.alert(message);
        }
      }

      function passwordsMatch(form, passFieldName = 'password', confirmFieldName = 'password_confirmation') {
        const password = form.querySelector(`[name="${passFieldName}"]`);
        const confirm = form.querySelector(`[name="${confirmFieldName}"]`);
        if (!password || !confirm) {
          return true;
        }
        if (password.value !== confirm.value) {
          showAlert('Les mots de passe ne correspondent pas.');
          confirm.focus();
          return false;
        }
        return true;
      }

      if (userForm) {
        userForm.addEventListener('submit', function (event) {
          if (!passwordsMatch(userForm)) {
            event.preventDefault();
          }
        });
      }

      if (agencyForm) {
        agencyForm.addEventListener('submit', function (event) {
          if (!passwordsMatch(agencyForm)) {
            event.preventDefault();
          }
        });
      }

      const serverErrors = document.getElementById('server-error-messages');
      if (serverErrors) {
        const messages = Array.from(serverErrors.querySelectorAll('li')).map(li => li.textContent.trim()).filter(Boolean);
        if (messages.length) {
          showAlert(messages.join('\n'));
        }
      }
    </script>
  </body>
</html>