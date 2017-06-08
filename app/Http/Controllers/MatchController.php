<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchStoreRequest;
use App\Models\Cat;
use App\Models\Match;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return new JsonResponse($this->getTwoRandomCats());
        }

        return view('home');
    }

    private function getTwoRandomCats()
    {
        return Cat::inRandomOrder()->take(2)->get(['id', 'url']);
    }

    public function store(MatchStoreRequest $request)
    {
        Match::create([
            'winner_id' => $request->input('winner'),
            'looser_id' => $request->input('looser'),
        ])->calcRatings()->each(function ($catAndRating) {
            ['cat' => $cat, 'rating' => $rating] = $catAndRating;
            $cat->update(compact('rating'));
        });

        return new JsonResponse($this->getTwoRandomCats(), 201);
    }
}
