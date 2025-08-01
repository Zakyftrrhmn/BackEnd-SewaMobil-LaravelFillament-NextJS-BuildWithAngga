<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryApiResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('vehicles')->get();
        return CategoryApiResource::collection($categories);
    }
    public function show(Category $category)
    {
        $category->load(['vehicles.category', 'vehicles.photos']);
        $category->loadCount('vehicles');
        return new CategoryApiResource($category);
    }
}
