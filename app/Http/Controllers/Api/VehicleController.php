<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\VehicleApiResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    //
    public function index()
    {
        $vehicles = Vehicle::with(['category'])->get();
        foreach ($vehicles as $vehicle) {
            $vehicle->category->loadCount('vehicles');
        }

        return VehicleApiResource::collection($vehicles);
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['category', 'photos', 'testimonials']);
        $vehicle->category->loadCount('vehicles');
        return new VehicleApiResource($vehicle);
    }

    public function popular(Request $request)
    {
        $limit = $request->query('limit', 10);

        $popularVehicles = Vehicle::where('is_popular', true)->with(['category'])->limit($limit)->get();

        foreach ($popularVehicles as $vehicle) {
            $vehicle->category->loadCount('vehicles');
        }

        return VehicleApiResource::collection($popularVehicles);
    }
}
