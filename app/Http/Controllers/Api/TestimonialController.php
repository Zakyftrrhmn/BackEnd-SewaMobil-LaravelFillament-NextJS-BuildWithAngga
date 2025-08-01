<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TestimonialApiResource;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);

        $vehicleTestimonials = Testimonial::with(['vehicle'])
            ->limit($limit)
            ->get();

        return TestimonialApiResource::collection($vehicleTestimonials);
    }
}
