<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Navette;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
 // Alias the Navette model

use Exception;

class UserProfileController extends Controller
{
    public function showNavettes()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Navettes disponibles (acceptées)
        $navettes = Navette::where('accepted', true)
            ->latest('created_at')
            ->get();

        // Réservations de l'utilisateur (historique)
        $reservations = Reservation::with('navette')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        // Navettes créées par l'utilisateur (modifiables si non acceptées)
        $myNavettes = Navette::where('creator', $user->id)
            ->latest('created_at')
            ->get();

        // Pass data to the view
        return view('job.profile', compact('user', 'navettes', 'reservations', 'myNavettes'));
    }
}
