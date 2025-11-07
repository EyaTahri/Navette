<?php

namespace App\Http\Controllers;

use App\Models\Reservation; 
use App\Models\Navette;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ReservationController extends Controller
{
    /**
     * Store a new reservation
     */
    public function store(Request $request)
    {
        Log::info('Store method started', ['request_data' => $request->all()]);

        // Si c'est une réservation rapide avec départ, destination, date, heure et véhicule
        if ($request->has('departure') && $request->has('destination') && $request->has('vehicle_id')) {
            // Validation pour réservation rapide
            $validatedData = $request->validate([
                'departure' => 'required|string|max:255',
                'destination' => 'required|string|max:255',
                'departure_date' => 'required|date',
                'departure_time' => 'required|string',
                'vehicle_id' => 'required|exists:vehicles,id',
                'passenger_count' => 'required|integer|min:1|max:20',
                'special_requests' => 'nullable|string|max:500',
                'payment_method' => 'required|in:cash,card,paypal',
            ]);

            $departure = $validatedData['departure'];
            $destination = $validatedData['destination'];
            
            // Combiner date et heure pour créer departure_datetime
            $departure_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validatedData['departure_date'] . ' ' . $validatedData['departure_time']);

            // Récupérer le véhicule
            $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']);

            // Chercher ou créer une navette pour ce trajet et ce véhicule
            $navette = Navette::firstOrCreate(
                [
                    'departure' => $departure,
                    'destination' => $destination,
                    'vehicle_id' => $vehicle->id,
                    'accepted' => true,
                ],
                [
                    'arrival' => $destination, // Le champ arrival est requis
                    'departure_datetime' => $departure_datetime,
                    'vehicle_type' => $vehicle->vehicle_type,
                    'brand' => $vehicle->brand,
                    'capacity' => $vehicle->capacity,
                    'price_per_person' => 50, // Prix par défaut raisonnable en DT
                    'vehicle_price' => 1,
                    'brand_price' => 0,
                    'creator' => $vehicle->agency_id,
                ]
            );
            
            // Mettre à jour la date/heure si la navette existait déjà
            if ($navette->wasRecentlyCreated === false) {
                $navette->update(['departure_datetime' => $departure_datetime]);
            }

            // Calculer le prix total
            $pricePerPerson = $navette->price_per_person ?? 50;
            $totalPrice = $pricePerPerson * $validatedData['passenger_count'];

            // Créer la réservation
            $insertData = [
                'user_id' => Auth::id(),
                'navette_id' => $navette->id,
                'passenger_count' => $validatedData['passenger_count'],
                'total_price' => $totalPrice,
                'payment_method' => $validatedData['payment_method'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ];

            if (Schema::hasColumn('reservations', 'contact_phone')) {
                $insertData['contact_phone'] = Auth::user()->contactdetails ?? '';
            }
            if (Schema::hasColumn('reservations', 'special_requests')) {
                $insertData['special_requests'] = $validatedData['special_requests'] ?? null;
            }

            $reservation = Reservation::create($insertData);

            Log::info('Reservation created', ['reservation_id' => $reservation->id]);

            return redirect()->route('profile')
                ->with('success', 'Réservation créée avec succès !');
        }

        // Ancienne méthode pour compatibilité (réservation avec navette_id)
        $validatedData = $request->validate([
            'navette_id' => 'required|exists:navettes,id',
            'passenger_count' => 'required|integer|min:1|max:20',
            'contact_phone' => 'nullable|string|max:20',
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,card,paypal',
        ]);

        // Utiliser le contact de l'utilisateur par défaut si non fourni
        if (empty($validatedData['contact_phone'])) {
            $validatedData['contact_phone'] = Auth::user()->contactdetails ?? '';
        }

        // Récupérer la navette
        $navette = Navette::findOrFail($validatedData['navette_id']);

        // Calculer le prix total
        $totalPrice = $this->calculateTotalPrice($navette, $validatedData['passenger_count']);

        // Créer la réservation
        $insertData = [
            'user_id' => Auth::id(),
            'navette_id' => $navette->id,
            'passenger_count' => $validatedData['passenger_count'],
            'total_price' => $totalPrice,
            'payment_method' => $validatedData['payment_method'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ];

        if (Schema::hasColumn('reservations', 'contact_phone')) {
            $insertData['contact_phone'] = $validatedData['contact_phone'];
        }
        if (Schema::hasColumn('reservations', 'special_requests')) {
            $insertData['special_requests'] = $validatedData['special_requests'] ?? null;
        }

        $reservation = Reservation::create($insertData);

        Log::info('Reservation created', ['reservation_id' => $reservation->id]);

        return redirect()->route('profile')
            ->with('success', 'Réservation créée avec succès !');
    }

    /**
     * Calculate total price for a navette
     */
    private function calculateTotalPrice($navette, $passengerCount)
    {
        $pricePerPerson = $navette->price_per_person ?? 0;
        $vehiclePrice = $navette->vehicle_price ?? 0;
        $brandPrice = $navette->brand_price ?? 0;
        $special = $navette->special ?? 0;

        $basePrice = $pricePerPerson * $vehiclePrice + $brandPrice;
        $totalPrice = $basePrice * $passengerCount;

        if ($special > 0) {
            $totalPrice = $totalPrice - ($totalPrice * $special / 100);
        }

        return $totalPrice;
    }



    public function updateStatus($id, $status)
    {
        // Find the reservation by ID
        $reservation = Reservation::findOrFail($id);

        // Validate the status to accept only 'accepted' or 'refused'
        if (!in_array($status, ['accepted', 'refused'])) {
            return response()->json(['message' => 'Invalid status'], 400);
        }

        // Update the reservation status based on the status passed
        $reservation->status = ($status === 'accepted'); // true for accepted, false for refused
        $reservation->save();

        // Return a response
        return redirect()->back()->withInput();
    }

    /**
     * Edit reservation by user when pending
     */
    public function userEdit($id)
    {
        $reservation = Reservation::with('navette')->findOrFail($id);
        
        // Vérifier que la réservation appartient à l'utilisateur connecté
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier que la réservation peut être modifiée (statut pending)
        if ($reservation->status !== 'pending') {
            return redirect()->route('profile')
                ->with('error', 'Modification impossible. La réservation n\'est plus en attente.');
        }
        
        return view('job.reservation-edit-user', compact('reservation'));
    }

    /**
     * Update reservation by user when pending
     */
    public function userUpdate(Request $request, $id)
    {
        $reservation = Reservation::with('navette')->findOrFail($id);
        
        // Vérifier que la réservation appartient à l'utilisateur connecté
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier que la réservation peut être modifiée (statut pending)
        if ($reservation->status !== 'pending') {
            return redirect()->route('profile')
                ->with('error', 'Modification impossible. La réservation n\'est plus en attente.');
        }

        $validated = $request->validate([
            'departure' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'departure_time' => 'required|string',
            'passenger_count' => 'required|integer|min:1|max:20',
            'contact_phone' => 'nullable|string|max:20',
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,card,paypal',
        ]);

        // Combiner date et heure pour créer departure_datetime
        $departure_datetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validated['departure_date'] . ' ' . $validated['departure_time']);
        
        // Mettre à jour la navette associée
        $navette = $reservation->navette;
        $navette->update([
            'departure' => $validated['departure'],
            'destination' => $validated['destination'],
            'arrival' => $validated['destination'],
            'departure_datetime' => $departure_datetime,
        ]);

        // Recalculer le prix total
        $totalPrice = $this->calculateTotalPrice($navette, $validated['passenger_count']);

        // Mettre à jour la réservation
        $updateData = [
            'passenger_count' => $validated['passenger_count'],
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'],
        ];

        if (Schema::hasColumn('reservations', 'contact_phone')) {
            $updateData['contact_phone'] = $validated['contact_phone'] ?? Auth::user()->contactdetails ?? '';
        }
        if (Schema::hasColumn('reservations', 'special_requests')) {
            $updateData['special_requests'] = $validated['special_requests'] ?? null;
        }

        $reservation->update($updateData);

        Log::info('Reservation updated by user', ['reservation_id' => $reservation->id]);

        return redirect()->route('profile')
            ->with('success', 'Réservation modifiée avec succès !');
    }

    /**
     * Delete reservation by user when pending
     */
    public function userDestroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Vérifier que la réservation appartient à l'utilisateur connecté
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier que la réservation peut être supprimée (statut pending)
        if ($reservation->status !== 'pending') {
            return redirect()->route('profile')
                ->with('error', 'Suppression impossible. La réservation n\'est plus en attente.');
        }

        $reservation->delete();

        Log::info('Reservation deleted by user', ['reservation_id' => $id]);

        return redirect()->route('profile')
            ->with('success', 'Réservation supprimée avec succès !');
    }

}
