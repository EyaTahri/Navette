<?php

namespace App\Http\Controllers;

use App\Models\Navette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Display the search form and some featured content.
     */
    public function index(Request $request)
    {
        // Popular destinations: group by departure/destination pairs and pick top 8
        $popularDestinations = Navette::select(['departure', 'destination'])
            ->groupBy('departure', 'destination')
            ->limit(8)
            ->get();

        // Special offers: where is_special_offer = true
        $specialOffers = Navette::where('is_special_offer', true)
            ->latest('created_at')
            ->limit(6)
            ->get();

        return view('job.search', compact('popularDestinations', 'specialOffers'));
    }

    /**
     * Handle the search results page.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'departure' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'departure_date' => ['nullable', 'date'],
            'departure_time' => ['nullable', 'date_format:H:i'],
            'vehicle_type' => ['nullable', 'string', 'max:255'],
            'min_capacity' => ['nullable', 'integer', 'min:1'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'special_offers' => ['nullable', 'boolean'],
            'sort_by' => ['nullable', 'in:departure_time,price,capacity'],
            'sort_order' => ['nullable', 'in:asc,desc'],
        ]);

        $query = Navette::query();

        if (!empty($validated['departure'])) {
            $query->where('departure', 'like', '%' . $validated['departure'] . '%');
        }

        if (!empty($validated['destination'])) {
            $query->where('destination', 'like', '%' . $validated['destination'] . '%');
        }

        if (!empty($validated['departure_date'])) {
            // Match date part of departure_datetime
            $query->whereDate('departure_datetime', $validated['departure_date']);
        }

        if (!empty($validated['departure_time'])) {
            // Match hour:minute on the same date if provided, otherwise any date
            $query->whereTime('departure_datetime', $validated['departure_time']);
        }

        if (!empty($validated['vehicle_type'])) {
            $query->where('vehicle_type', $validated['vehicle_type']);
        }

        if (!empty($validated['min_capacity'])) {
            $query->where('capacity', '>=', $validated['min_capacity']);
        }

        if (!empty($validated['max_price'])) {
            $query->where('price_per_person', '<=', $validated['max_price']);
        }

        if (!empty($validated['special_offers'])) {
            $query->where('is_special_offer', true);
        }

        // Sorting
        $sortBy = $validated['sort_by'] ?? 'departure_time';
        $sortOrder = $validated['sort_order'] ?? 'asc';

        switch ($sortBy) {
            case 'price':
                $query->orderBy('price_per_person', $sortOrder);
                break;
            case 'capacity':
                $query->orderBy('capacity', $sortOrder);
                break;
            case 'departure_time':
            default:
                $query->orderBy('departure_datetime', $sortOrder);
                break;
        }

        $navettes = $query->paginate(10);

        return view('job.search-results', compact('navettes'));
    }

    /**
     * Lightweight JSON API for async search/autocomplete.
     */
    public function apiSearch(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50']
        ]);

        $limit = (int) ($request->input('limit', 10));
        $q = $request->input('q');

        $query = Navette::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('departure', 'like', "%$q%")
                    ->orWhere('destination', 'like', "%$q%");
            });
        }

        $results = $query->latest('departure_datetime')
            ->limit($limit)
            ->get([
                'id',
                'departure',
                'destination',
                'departure_datetime',
                'vehicle_type',
                'brand',
                'capacity',
                'price_per_person',
                'is_special_offer',
            ]);

        return response()->json([
            'data' => $results,
        ]);
    }
}
