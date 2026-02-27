<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query();
        if ($request->county) $query->where('county', $request->county);
        if ($request->type) $query->where('type', $request->type);
        return response()->json($query->where('is_active', true)->get());
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
        ]);
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