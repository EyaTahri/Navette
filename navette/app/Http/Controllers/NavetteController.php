<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Navette;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class NavetteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $navettes = Navette::all();
        return view('job.testimonial', compact('navettes', 'user'));
    }

    public function indexReservations()
    {
        // If agency, redirect to the agency reservations list
        if (Auth::check() && Auth::user()->role === 'AGENCE') {
            return redirect()->route('agency.reservations.index');
        }

        // Fetch reservations for the authenticated user and eager load the navette relationship
        $reservations = Reservation::with('navette')->where('user_id', Auth::id())->get();
    
        // Pass only the reservations to the view
        return view('job.job-list', compact('reservations')); // Adjust the view name as needed
    }

    public function store(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all());

            $validatedData = $request->validate([
                'destination' => 'required|string|max:255',
                'departure' => 'required|string|max:255',
                'arrival' => 'required|string|max:255',
                'departure_datetime' => 'nullable|date',
                'arrival_datetime' => 'nullable|date|after:departure_datetime',
                'vehicle_type' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'price_per_person' => 'required|numeric|min:0',
                'vehicle_price' => 'required|numeric|min:0',
                'brand_price' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'is_special_offer' => 'boolean',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'special' => 'nullable|numeric|min:0',
            ]);

            $validatedData['creator'] = Auth::id();

            $navette = Navette::create($validatedData);

            $totalPrice = $navette->price_per_person + $navette->vehicle_price + $navette->brand_price;

            return redirect()->route('create_navette')->with('success', 'Navette créée avec succès');

        } catch (ValidationException $e) {
            Log::error('Validation failed: ', ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Error occurred while storing navette data: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request data: ', $request->all());

            $validatedData = $request->validate([
                'destination' => 'required|string|max:255',
                'departure' => 'required|string|max:255',
                'arrival' => 'required|string|max:255',
                'vehicle_type' => 'required|string|max:255',
                'brand' => 'required|string|max:255',
                'price_per_person' => 'required|numeric|min:0',
                'vehicle_price' => 'required|numeric|min:0',
                'brand_price' => 'required|numeric|min:0',
                'special' => 'nullable|numeric|min:0',
            ]);

            $navette = Navette::findOrFail($id);
            $navette->update($validatedData);

            return redirect()->route('navettes.index')->with('success', 'Navette mise à jour avec succès');

        } catch (ValidationException $e) {
            Log::error('Validation failed during update: ', ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Error occurred while updating navette data: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function accept($id)
    {
        $navette = Navette::findOrFail($id);
        $navette->accepted = true;
        $navette->save();

        return redirect()->back()->withInput();
    }

    public function refuse($id)
    {
        $navette = Navette::findOrFail($id);
        $navette->accepted = false;
        $navette->save();

        return redirect()->back()->withInput();
    }

    public function destroy($id)
    {
        $navette = Navette::findOrFail($id);
        $navette->delete();

    return redirect()->route('navettes.index')->with('success', 'Navette deleted successfully');
}
}

    /**
     * Liste des offres de l'agence
     */
    public function offersIndex()
    {
        $navettes = Navette::where('creator', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('job.agency.offers.index', compact('navettes'));
    }

    /**
     * Publier une offre spéciale
     */
    public function publishOffer($id)
    {
        $navette = Navette::where('creator', auth()->id())->findOrFail($id);
        $navette->is_special_offer = true;

        if (request()->has('discount_percentage')) {
            $navette->discount_percentage = max(0, min(100, (int) request('discount_percentage')));
        }

        $navette->save();
        return redirect()->back()->with('success', 'Offre publiée.');
    }

    /**
     * Retirer une offre spéciale
     */
    public function removeOffer($id)
    {
        $navette = Navette::where('creator', auth()->id())->findOrFail($id);
        $navette->is_special_offer = false;
        $navette->discount_percentage = null;
        $navette->save();

        return redirect()->back()->with('success', 'Offre retirée.');
    }
}
