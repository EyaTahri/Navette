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
        .auth-hero{background:linear-gradient(135deg,rgba(102,126,234,.9),rgba(118,75,162,.9));padding:3rem 0;margin-bottom:2rem}
        .auth-card{background:#fff;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.1)}
        .hidden{display:none}
        .form-control{border-radius:10px;border:2px solid #e9ecef;padding:12px 15px}
        .form-control:focus{border-color:#667eea;box-shadow:0 0 0 .2rem rgba(102,126,234,.25)}
        .btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);border:none;border-radius:10px}
        .switch-link{cursor:pointer;color:#667eea}
        .switch-link:hover{text-decoration:underline}
    </style>
</head>
<body>

    <div class="auth-hero">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="text-center text-white mb-3">
              <h1 class="fw-bold">Bienvenue</h1>
              <p class="mb-0">Connectez-vous ou créez un compte pour réserver</p>
            </div>
            <div class="car-animation mt-4">
              <svg width="100%" height="140" viewBox="0 0 800 140" preserveAspectRatio="xMidYMid slice">
                <rect x="0" y="105" width="800" height="6" fill="rgba(255,255,255,0.6)"/>
                <g class="car">
                  <rect x="0" y="60" width="120" height="30" rx="6" fill="#ffffff" opacity="0.95"/>
                  <rect x="20" y="40" width="70" height="25" rx="6" fill="#ffffff" opacity="0.9"/>
                  <circle class="wheel" cx="30" cy="95" r="10" fill="#667eea"/>
                  <circle class="wheel" cx="90" cy="95" r="10" fill="#667eea"/>
                  <circle cx="55" cy="52" r="4" fill="#764ba2"/>
                  <circle cx="67" cy="52" r="4" fill="#764ba2"/>
                </g>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mb-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="auth-card p-4">
            <ul class="nav nav-pills mb-3" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="signin-tab" data-bs-toggle="pill" data-bs-target="#signin-pane" type="button" role="tab">Connexion</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="signup-tab" data-bs-toggle="pill" data-bs-target="#signup-pane" type="button" role="tab">Inscription</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="agency-tab" data-bs-toggle="pill" data-bs-target="#agency-pane" type="button" role="tab">Inscription Agence</button>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade show active" id="signin-pane" role="tabpanel">
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
                    <a href="{{ route('search.index') }}" class="switch-link">Retour à l'accueil</a>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
              </div>
              <div class="tab-pane fade" id="signup-pane" role="tabpanel">
                <form method="POST" action="{{ route('register') }}" id="register-form">
    @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Nom complet</label>
                      <input type="text" name="name" class="form-control" placeholder="Nom" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Téléphone</label>
                      <input type="text" name="contactdetails" class="form-control" placeholder="06 12 34 56 78" required />
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

              <div class="tab-pane fade" id="agency-pane" role="tabpanel">
                <form method="POST" action="{{ route('registerAgence') }}">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Nom de l'agence</label>
                      <input type="text" name="name" class="form-control" placeholder="Agence X" required />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Lieu</label>
                      <input type="text" name="lieu" class="form-control" placeholder="Casablanca" required />
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
          </div>
        </div>
      </div>
    </div>

    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
