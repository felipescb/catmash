<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Services\MealsRanker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request, MealsRanker $mealsRanker)
    {
        if ($request->expectsJson()) {
            return new JsonResponse($this->getMeals($mealsRanker));
        }

        return view('meals');
    }

    private function getMeals(MealsRanker $mealsRanker): array
    {
        $rankedMeals = $mealsRanker->rank(Meal::whereNotNull('rating')->get(['id', 'url', 'rating']));

        return [
            'topRankedMeals'   => $rankedMeals->where('rank', '<=', 5)->values(),
            'otherRankedMeals' => $rankedMeals->where('rank', '>', 5)->values(),
            'notRankedMeals'   => Meal::whereNull('rating')->get(['id', 'url', 'rating'])->values(),
        ];
    }
}
