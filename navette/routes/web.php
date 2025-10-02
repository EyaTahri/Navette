
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NavetteController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route for the login form (accessible without authentication)
// Route for showing the login form
Route::get('/', function () {
    return redirect()->route('search.index');  // Rediriger vers la page de recherche
})->name('home');

// Routes de recherche (accessibles sans authentification)
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->name('api.search');

// Route de connexion
Route::get('/login', function () {
    return view('job.auth.auth');
})->name('login');

// Route for processing the login request
Route::post('/login', [AuthController::class, 'login'])->name('login');


// routes/api.php

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/registerAgence', [AuthController::class, 'registerAgence'])->name('registerAgence');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// Protected routes that require authentication
Route::group(['middleware' => 'auth'], function () {
    Route::put('/user/{id}', [AuthController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/user/{id}', [AuthController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/reservation', [NavetteController::class, 'indexReservations'])->name('navettes.reservations');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/404', [PageController::class, 'error404'])->name('404');
    Route::get('/profile' , [PageController::class , 'profile'])->name('profile');
    // Agences routes
    Route::get('/create', [PageController::class, 'category'])->name('create_navette');
    Route::post('/create/navette', [NavetteController::class, 'store'])->name('creetenav');
    Route::get('/navettes', [NavetteController::class, 'index'])->name('navettes.index');
    Route::get('/navettes/{id}/edit', [NavetteController::class, 'edit'])->name('edit_navette');
    Route::delete('/navettes/{id}', [NavetteController::class, 'destroy'])->name('delete_navette');
    Route::put('/navette/{id}', [NavetteController::class, 'update'])->name('updateNav');
    
    // Routes de gestion des véhicules pour les agences
    Route::prefix('agency')->name('agency.')->group(function () {
        Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');
        Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
        Route::post('/vehicles/{id}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.updateStatus');
        Route::get('/api/vehicles/available', [VehicleController::class, 'getAvailableVehicles'])->name('api.vehicles.available');
    });
    // Routes de réservation améliorées
    Route::get('/reservation/create/{id}', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation/confirmation/{id}', [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
    Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::post('/reservation/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::post('/api/reservation/calculate-price', [ReservationController::class, 'calculatePrice'])->name('api.reservation.calculate-price');
    
    // Routes existantes (pour compatibilité)
    Route::post('/creereservation/{id}', [ReservationController::class, 'store'])->name('creeteres');
    Route::post('/navettes/{id}/accept', [NavetteController::class, 'accept'])->name('navettes.accept');
    Route::post('/navettes/{id}/refuse', [NavetteController::class, 'refuse'])->name('navettes.refuse');
    Route::post('/reservations/{id}/status/{status}', [ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');

    //user Routes 
    Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('profile');
    Route::get('/profile', [UserProfileController::class, 'showNavettes'])->name('profile');
    
    // Routes administrateur
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/agencies', [AdminController::class, 'agencies'])->name('agencies');
        Route::post('/agencies/{id}/approve', [AdminController::class, 'approveAgency'])->name('approveAgency');
        Route::post('/agencies/{id}/reject', [AdminController::class, 'rejectAgency'])->name('rejectAgency');
        Route::get('/navettes', [AdminController::class, 'navettes'])->name('navettes');
        Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
        Route::get('/vehicles', [AdminController::class, 'vehicles'])->name('vehicles');
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::get('/api/chart-data', [AdminController::class, 'chartData'])->name('api.chartData');
    });
});
