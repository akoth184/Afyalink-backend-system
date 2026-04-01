<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = \App\Models\Facility::where('is_active', true)
            ->orderBy('name')->paginate(10);
        return view('facilities.index', compact('facilities'));
    }

    /**
     * Show nearby hospitals for patients
     * Calculates distance between patient and facilities using Haversine formula
     */
    public function nearbyHospitals(Request $request)
    {
        // Get user's location from request (for demo) or patient profile
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');

        // If no coordinates provided, try to get from patient's facility
        $user = Auth::user();

        // Default coordinates (Nairobi) if not provided
        if (!$userLat || !$userLng) {
            $userLat = -1.2921; // Nairobi default
            $userLng = 36.8219;
        }

        // Get all active facilities with coordinates
        $facilities = Facility::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Calculate distance for each facility using Haversine formula
        $facilitiesWithDistance = $facilities->map(function ($facility) use ($userLat, $userLng) {
            $distance = $this->calculateDistance(
                $userLat,
                $userLng,
                $facility->latitude,
                $facility->longitude
            );

            return [
                'id' => $facility->id,
                'name' => $facility->name,
                'type' => $facility->type,
                'phone' => $facility->phone,
                'email' => $facility->email,
                'county' => $facility->county,
                'sub_county' => $facility->sub_county,
                'ward' => $facility->ward,
                'latitude' => $facility->latitude,
                'longitude' => $facility->longitude,
                'working_hours' => $facility->working_hours,
                'distance' => round($distance, 2), // Distance in km
            ];
        });

        // Sort by distance (nearest first)
        $facilitiesWithDistance = $facilitiesWithDistance->sortBy('distance')->values();

        // Return view for web requests
        if (!$request->expectsJson()) {
            return view('patient.nearby-hospitals', compact('facilitiesWithDistance', 'userLat', 'userLng'));
        }

        // Return JSON for API requests
        return response()->json([
            'success' => true,
            'data' => $facilitiesWithDistance,
        ]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lon1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lon2 Longitude of point 2
     * @return float Distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:hospital,clinic,health_center,dispensary',
            'mfl_code' => 'nullable|string|unique:facilities',
            'county' => 'required|string',
            'sub_county' => 'nullable|string',
            'ward' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'working_hours' => 'nullable|json',
        ]);

        // Set is_active to false by default - requires admin approval
        $data['is_active'] = false;

        return response()->json(Facility::create($data), 201);
    }

    public function show($id)
    {
        return response()->json(Facility::with(['users', 'patients'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);
        $facility->update($request->all());
        return response()->json($facility);
    }

    public function destroy($id)
    {
        Facility::findOrFail($id)->delete();
        return response()->json(['message' => 'Facility deleted']);
    }
}
