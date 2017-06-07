<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Services\CatsRanker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatController extends Controller
{
    public function index(Request $request, CatsRanker $catsRanker)
    {
        $rankedCats = $catsRanker->rank(Cat::whereNotNull('rating')->get(['id', 'url', 'rating']));
        $notRankedCats = Cat::whereNull('rating')->get(['id', 'url', 'rating']);

        return new JsonResponse(compact('rankedCats', 'notRankedCats'));
    }
}
