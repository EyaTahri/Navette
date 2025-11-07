<?php

namespace App\Http\Controllers;

use App\Models\Navette;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the home page using the same design as /search
     */
    public function index(Request $request)
    {
        // Redirect agencies to their offers dashboard
        if (Auth::check() && Auth::user()->role === 'AGENCE') {
            return redirect()->route('agency.offers.index');
        }

        // Récupérer les véhicules disponibles publiés par les agences
        $availableVehicles = Vehicle::where('is_active', true)
            ->where('status', 'available')
            ->with('agency')
            ->orderBy('vehicle_type', 'asc')
            ->orderBy('brand', 'asc')
            ->get();

        return view('job.search', compact('availableVehicles'));
    }
}