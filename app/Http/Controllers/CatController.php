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
        if ($request->expectsJson()) {
            return new JsonResponse($this->getCats($catsRanker));
        }

        return view('cats');
    }

    private function getCats(CatsRanker $catsRanker): array
    {
        $rankedCats = $catsRanker->rank(Cat::whereNotNull('rating')->get(['id', 'url', 'rating']));

        return [
            'topRankedCats'   => $rankedCats->where('rank', '<=', 5)->values(),
            'otherRankedCats' => $rankedCats->where('rank', '>=', 5)->values(),
            'notRankedCats'   => Cat::whereNull('rating')->get(['id', 'url', 'rating'])->values(),
        ];
    }
}
