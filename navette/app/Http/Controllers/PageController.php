<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    // Home page method
    // public function home()
    // {
    //     return view('job.index');
    // }

    // About page method
    public function about()
    {
        return view('job.about');
    }

    // Reservation page method
    public function reservation()
    {
        return view('job.job-list');
    }

    // Contact page method
    public function contact()
    {
        return view('job.contact');
    }
    // public function profile()
    // {
    //     return view('job.profile');
    // }

    // 404 page method
    public function error404()
    {
        return view('job.404');
    }

    public function category()
    {
        $user = Auth::user();
        $availableVehicles = [];
        
        // Si l'utilisateur est une agence, récupérer ses véhicules disponibles
        if ($user && $user->role === 'AGENCE') {
            $availableVehicles = \App\Models\Vehicle::where('agency_id', $user->id)
                ->where('status', 'available')
                ->where('is_active', true)
                ->get(['id', 'brand', 'model', 'vehicle_type', 'capacity', 'license_plate']);
        }
        
        return view('job.category', compact('availableVehicles'));
    }
    public function testimonial()
    {
        return view('job.testimonial');
    }
}
