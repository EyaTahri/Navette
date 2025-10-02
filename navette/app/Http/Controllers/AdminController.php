<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Navette;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Afficher le dashboard administrateur
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est administrateur
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette page.');
        }

        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'total_agencies' => User::where('role', 'AGENCE')->count(),
            'total_vehicles' => Vehicle::count(),
            'total_navettes' => Navette::count(),
            'total_reservations' => Reservation::count(),
            'pending_navettes' => Navette::whereNull('accepted')->count(),
            'pending_agencies' => User::where('role', 'AGENCE')->whereNull('email_verified_at')->count(),
            'active_reservations' => Reservation::where('status', 'confirmed')->count(),
        ];

        // Revenus des 30 derniers jours
        $revenue = Reservation::where('status', 'confirmed')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('total_price');

        // Graphique des réservations par mois (6 derniers mois)
        $reservationsChart = Reservation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Navettes en attente de validation
        $pendingNavettes = Navette::with(['creator', 'vehicle'])
            ->whereNull('accepted')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Agences en attente de validation
        $pendingAgencies = User::where('role', 'AGENCE')
            ->whereNull('email_verified_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Réservations récentes
        $recentReservations = Reservation::with(['navette', 'navette.creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top destinations
        $topDestinations = Navette::select('destination', DB::raw('COUNT(*) as count'))
            ->where('accepted', true)
            ->groupBy('destination')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Véhicules par type
        $vehiclesByType = Vehicle::select('vehicle_type', DB::raw('COUNT(*) as count'))
            ->groupBy('vehicle_type')
            ->get();

        return view('job.admin.dashboard', compact(
            'stats',
            'revenue',
            'reservationsChart',
            'pendingNavettes',
            'pendingAgencies',
            'recentReservations',
            'topDestinations',
            'vehiclesByType'
        ));
    }

    /**
     * Gérer les utilisateurs
     */
    public function users()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $users = User::withCount(['navettes', 'reservations'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('job.admin.users', compact('users'));
    }

    /**
     * Gérer les agences
     */
    public function agencies()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $agencies = User::where('role', 'AGENCE')
            ->withCount(['vehicles', 'navettes'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('job.admin.agencies', compact('agencies'));
    }

    /**
     * Mettre à jour un utilisateur (admin)
     */
    public function updateUser(Request $request, $id)
    {
        $current = Auth::user();
        if ($current->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contactdetails' => ['nullable', 'string', 'max:255'],
            'place' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:USER,AGENCE,ADMIN'],
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->back()->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprimer un utilisateur (admin)
     */
    public function destroyUser($id)
    {
        $current = Auth::user();
        if ($current->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Mettre à jour une agence (admin)
     */
    public function updateAgency(Request $request, $id)
    {
        $current = Auth::user();
        if ($current->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'place' => ['nullable', 'string', 'max:255'],
            'contactdetails' => ['nullable', 'string', 'max:255'],
        ]);

        $agency = User::where('role', 'AGENCE')->findOrFail($id);
        $agency->update($validated);

        return redirect()->back()->with('success', 'Agence mise à jour.');
    }

    /**
     * Supprimer une agence (admin)
     */
    public function destroyAgency($id)
    {
        $current = Auth::user();
        if ($current->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $agency = User::where('role', 'AGENCE')->findOrFail($id);
        $agency->delete();

        return redirect()->back()->with('success', 'Agence supprimée.');
    }

    /**
     * Valider une agence
     */
    public function approveAgency($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $agency = User::where('role', 'AGENCE')->findOrFail($id);
        $agency->update(['email_verified_at' => now()]);

        return redirect()->back()->with('success', 'Agence validée avec succès !');
    }

    /**
     * Rejeter une agence
     */
    public function rejectAgency($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $agency = User::where('role', 'AGENCE')->findOrFail($id);
        $agency->delete();

        return redirect()->back()->with('success', 'Agence rejetée et supprimée !');
    }

    /**
     * Gérer les navettes
     */
    public function navettes()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $navettes = Navette::with(['creator', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('job.admin.navettes', compact('navettes'));
    }

    /**
     * Gérer les réservations
     */
    public function reservations()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $reservations = Reservation::with(['navette', 'navette.creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('job.admin.reservations', compact('reservations'));
    }

    /**
     * Gérer les véhicules
     */
    public function vehicles()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        $vehicles = Vehicle::with(['agency'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('job.admin.vehicles', compact('vehicles'));
    }

    /**
     * Statistiques détaillées
     */
    public function statistics()
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            abort(403, 'Accès non autorisé.');
        }

        // Statistiques par mois (12 derniers mois)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'reservations' => Reservation::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => Reservation::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->where('status', 'confirmed')
                    ->sum('total_price'),
            ];
        }

        // Top agences par revenus
        $topAgencies = User::where('role', 'AGENCE')
            ->withSum('navettes.reservations', 'total_price')
            ->orderBy('navettes_reservations_sum_total_price', 'desc')
            ->limit(10)
            ->get();

        return view('job.admin.statistics', compact('monthlyStats', 'topAgencies'));
    }

    /**
     * API pour les graphiques
     */
    public function chartData(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'ADMIN') {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $type = $request->get('type', 'reservations');

        switch ($type) {
            case 'reservations':
                $data = Reservation::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('COUNT(*) as count')
                    )
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            case 'revenue':
                $data = Reservation::select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_price) as total')
                    )
                    ->where('status', 'confirmed')
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            default:
                $data = [];
        }

        return response()->json($data);
    }
}






