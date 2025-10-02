<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    /**
     * Afficher la liste des véhicules de l'agence
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est une agence
        if ($user->role !== 'AGENCE') {
            abort(403, 'Accès non autorisé. Seules les agences peuvent gérer les véhicules.');
        }

        $vehicles = Vehicle::where('agency_id', $user->id)
            ->withCount('navettes')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('job.agency.vehicles.index', compact('vehicles'));
    }

    /**
     * Afficher le formulaire de création d'un véhicule
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role !== 'AGENCE') {
            abort(403, 'Accès non autorisé.');
        }

        return view('job.agency.vehicles.create');
    }

    /**
     * Enregistrer un nouveau véhicule
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'AGENCE') {
            abort(403, 'Accès non autorisé.');
        }

        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1|max:50',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate',
            'color' => 'nullable|string|max:100',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg',
            'transmission' => 'required|in:manual,automatic,semi_automatic',
            'features' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintenance_date' => 'nullable|date',
            'insurance_expiry' => 'nullable|date|after:today',
            'description' => 'nullable|string|max:1000',
            'daily_rate' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'km_rate' => 'nullable|numeric|min:0',
        ]);

        try {
            // Traitement des images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('vehicles', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // Créer le véhicule
            $vehicle = Vehicle::create([
                'agency_id' => $user->id,
                'brand' => $validatedData['brand'],
                'model' => $validatedData['model'],
                'vehicle_type' => $validatedData['vehicle_type'],
                'year' => $validatedData['year'],
                'capacity' => $validatedData['capacity'],
                'license_plate' => strtoupper($validatedData['license_plate']),
                'color' => $validatedData['color'],
                'fuel_type' => $validatedData['fuel_type'],
                'transmission' => $validatedData['transmission'],
                'features' => $validatedData['features'] ?? [],
                'images' => $imagePaths,
                'maintenance_date' => $validatedData['maintenance_date'],
                'insurance_expiry' => $validatedData['insurance_expiry'],
                'description' => $validatedData['description'],
                'daily_rate' => $validatedData['daily_rate'],
                'hourly_rate' => $validatedData['hourly_rate'],
                'km_rate' => $validatedData['km_rate'],
                'status' => 'available',
                'is_active' => true,
            ]);

            Log::info('Vehicle created successfully', ['vehicle_id' => $vehicle->id, 'agency_id' => $user->id]);

            return redirect()->route('agency.vehicles.index')
                ->with('success', 'Véhicule créé avec succès !');

        } catch (\Exception $e) {
            Log::error('Error creating vehicle', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du véhicule : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'un véhicule
     */
    public function show($id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('agency_id', $user->id)->findOrFail($id);

        $vehicle->load(['navettes.reservations']);

        return view('job.agency.vehicles.show', compact('vehicle'));
    }

    /**
     * Afficher le formulaire d'édition d'un véhicule
     */
    public function edit($id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('agency_id', $user->id)->findOrFail($id);

        return view('job.agency.vehicles.edit', compact('vehicle'));
    }

    /**
     * Mettre à jour un véhicule
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('agency_id', $user->id)->findOrFail($id);

        $validatedData = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1|max:50',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $id,
            'color' => 'nullable|string|max:100',
            'fuel_type' => 'required|in:gasoline,diesel,electric,hybrid,lpg',
            'transmission' => 'required|in:manual,automatic,semi_automatic',
            'features' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintenance_date' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
            'daily_rate' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'km_rate' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,in_use,maintenance,out_of_service',
            'is_active' => 'boolean',
        ]);

        try {
            // Traitement des nouvelles images
            $imagePaths = $vehicle->images ?? [];
            if ($request->hasFile('images')) {
                // Supprimer les anciennes images
                foreach ($imagePaths as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
                
                // Ajouter les nouvelles images
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('vehicles', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            // Mettre à jour le véhicule
            $vehicle->update([
                'brand' => $validatedData['brand'],
                'model' => $validatedData['model'],
                'vehicle_type' => $validatedData['vehicle_type'],
                'year' => $validatedData['year'],
                'capacity' => $validatedData['capacity'],
                'license_plate' => strtoupper($validatedData['license_plate']),
                'color' => $validatedData['color'],
                'fuel_type' => $validatedData['fuel_type'],
                'transmission' => $validatedData['transmission'],
                'features' => $validatedData['features'] ?? [],
                'images' => $imagePaths,
                'maintenance_date' => $validatedData['maintenance_date'],
                'insurance_expiry' => $validatedData['insurance_expiry'],
                'description' => $validatedData['description'],
                'daily_rate' => $validatedData['daily_rate'],
                'hourly_rate' => $validatedData['hourly_rate'],
                'km_rate' => $validatedData['km_rate'],
                'status' => $validatedData['status'],
                'is_active' => $validatedData['is_active'] ?? true,
            ]);

            Log::info('Vehicle updated successfully', ['vehicle_id' => $vehicle->id]);

            return redirect()->route('agency.vehicles.index')
                ->with('success', 'Véhicule mis à jour avec succès !');

        } catch (\Exception $e) {
            Log::error('Error updating vehicle', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du véhicule : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un véhicule (soft delete)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('agency_id', $user->id)->findOrFail($id);

        // Vérifier s'il y a des navettes actives avec ce véhicule
        $activeNavettes = $vehicle->navettes()->where('accepted', true)->count();
        
        if ($activeNavettes > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce véhicule car il est utilisé dans ' . $activeNavettes . ' navette(s) active(s).');
        }

        try {
            // Supprimer les images
            if ($vehicle->images) {
                foreach ($vehicle->images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            $vehicle->delete();

            Log::info('Vehicle deleted successfully', ['vehicle_id' => $vehicle->id]);

            return redirect()->route('agency.vehicles.index')
                ->with('success', 'Véhicule supprimé avec succès !');

        } catch (\Exception $e) {
            Log::error('Error deleting vehicle', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du véhicule : ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un véhicule
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('agency_id', $user->id)->findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:available,in_use,maintenance,out_of_service',
        ]);

        $vehicle->update(['status' => $validatedData['status']]);

        return redirect()->back()
            ->with('success', 'Statut du véhicule mis à jour !');
    }

    /**
     * API pour obtenir les véhicules disponibles d'une agence
     */
    public function getAvailableVehicles(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'AGENCE') {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $vehicles = Vehicle::where('agency_id', $user->id)
            ->where('status', 'available')
            ->where('is_active', true)
            ->get(['id', 'brand', 'model', 'vehicle_type', 'capacity', 'license_plate']);

        return response()->json(['vehicles' => $vehicles]);
    }
}






