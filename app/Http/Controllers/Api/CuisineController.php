<?php

namespace App\Http\Controllers\Api;

use App\CuisineSearch\CuisineSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CuisineController extends Controller
{
    /**
     * Discover cuisine data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function discovery(Request $request)
    {
        $cuisineQuery = (new CuisineSearch)->apply($request);

        return response()->json($cuisineQuery);
    }
}
