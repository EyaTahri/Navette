<?php

namespace App\Http\Controllers;

use App\Models\Reservation; 
use App\Models\Navette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Afficher le formulaire de réservation
     */
    public function create($navetteId)
    {
        $navette = Navette::findOrFail($navetteId);
        
        // Vérifier que la navette est acceptée
        if (!$navette->accepted) {
            return redirect()->back()->with('error', 'Cette navette n\'est pas encore disponible.');
        }

        // Calculer le prix total
        $totalPrice = $this->calculateTotalPrice($navette);
        
        // Calculer les places disponibles
        $availableSeats = $this->calculateAvailableSeats($navette);
        
        return view('job.reservation-form', compact('navette', 'totalPrice', 'availableSeats'));
    }

    /**
     * Traiter la réservation
     */
    public function store(Request $request)
    {
        Log::info('Store method started', ['request_data' => $request->all()]);

        // Validation complète
        $validatedData = $request->validate([
            'navette_id' => 'required|exists:navettes,id',
            'passenger_count' => 'required|integer|min:1|max:20',
            'contact_phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash,card,paypal',
        ]);

        // Récupérer la navette
        $navette = Navette::findOrFail($validatedData['navette_id']);
        
        // Vérifier la disponibilité
        $availableSeats = $this->calculateAvailableSeats($navette);
        if ($validatedData['passenger_count'] > $availableSeats) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Seulement {$availableSeats} places disponibles.");
        }

        // Calculer le prix total
        $totalPrice = $this->calculateTotalPrice($navette, $validatedData['passenger_count']);

        Log::info('Total price calculated', ['total_price' => $totalPrice]);

        // Créer la réservation
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'navette_id' => $validatedData['navette_id'],
            'passenger_count' => $validatedData['passenger_count'],
            'contact_phone' => $validatedData['contact_phone'],
            'special_requests' => $validatedData['special_requests'],
            'total_price' => $totalPrice,
            'payment_method' => $validatedData['payment_method'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        Log::info('Reservation created', ['reservation_id' => $reservation->id]);

        // Rediriger vers la page de confirmation
        return redirect()->route('reservation.confirmation', $reservation->id)
            ->with('success', 'Réservation créée avec succès !');
    }

    /**
     * Afficher la page de confirmation de réservation
     */
    public function confirmation($reservationId)
    {
        $reservation = Reservation::with('navette')->findOrFail($reservationId);
        
        // Vérifier que l'utilisateur peut voir cette réservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('job.reservation-confirmation', compact('reservation'));
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show($reservationId)
    {
        $reservation = Reservation::with('navette')->findOrFail($reservationId);
        
        // Vérifier que l'utilisateur peut voir cette réservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('job.reservation-details', compact('reservation'));
    }

    /**
     * Annuler une réservation
     */
    public function cancel($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        // Vérifier que l'utilisateur peut annuler cette réservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que la réservation peut être annulée
        if ($reservation->status === 'cancelled') {
            return redirect()->back()->with('error', 'Cette réservation est déjà annulée.');
        }

        // Annuler la réservation
        $reservation->update([
            'status' => 'cancelled',
            'payment_status' => 'refunded'
        ]);

        return redirect()->back()->with('success', 'Réservation annulée avec succès.');
    }

    /**
     * Calculer le prix total d'une navette
     */
    private function calculateTotalPrice($navette, $passengerCount = 1)
    {
        $basePrice = $navette->price_per_person + $navette->vehicle_price + $navette->brand_price;
        $totalPrice = $basePrice * $passengerCount;
        
        // Appliquer la remise si c'est une offre spéciale
        if ($navette->is_special_offer && $navette->discount_percentage) {
            $discount = $totalPrice * ($navette->discount_percentage / 100);
            $totalPrice = $totalPrice - $discount;
        }

        return round($totalPrice, 2);
    }

    /**
     * Calculer les places disponibles
     */
    private function calculateAvailableSeats($navette)
    {
        $reservedSeats = $navette->reservations()
            ->where('status', 'confirmed')
            ->sum('passenger_count');

        return max(0, $navette->capacity - $reservedSeats);
    }

    /**
     * Mettre à jour le statut d'une réservation (pour les agences)
     */
    public function updateStatus($id, $status)
    {
        $reservation = Reservation::findOrFail($id);

        // Vérifier que l'utilisateur est l'agence propriétaire de la navette
        if ($reservation->navette->creator !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        // Valider le statut
        if (!in_array($status, ['confirmed', 'cancelled'])) {
            return response()->json(['message' => 'Statut invalide'], 400);
        }

        // Mettre à jour le statut
        $reservation->update([
            'status' => $status,
            'payment_status' => $status === 'confirmed' ? 'paid' : 'refunded'
        ]);

        return redirect()->back()->with('success', 'Statut de la réservation mis à jour.');
    }

    /**
     * API pour calculer le prix en temps réel
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'navette_id' => 'required|exists:navettes,id',
            'passenger_count' => 'required|integer|min:1|max:20',
        ]);

        $navette = Navette::findOrFail($request->navette_id);
        $totalPrice = $this->calculateTotalPrice($navette, $request->passenger_count);

        return response()->json([
            'total_price' => $totalPrice,
            'price_per_person' => $navette->price_per_person,
            'passenger_count' => $request->passenger_count,
            'discount_percentage' => $navette->discount_percentage,
            'is_special_offer' => $navette->is_special_offer,
        ]);
    }
}
