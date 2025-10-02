<?php

namespace App\Http\Controllers;

use App\Models\Navette;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the home page using the same design as /search
     */
    public function index(Request $request)
    {
        $popularDestinations = Navette::select(['departure', 'destination'])
            ->groupBy('departure', 'destination')
            ->limit(8)
            ->get();

        $specialOffers = Navette::where('is_special_offer', true)
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('job.search', compact('popularDestinations', 'specialOffers'));
    }
}