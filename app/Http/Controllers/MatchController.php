<?php

namespace App\Http\Controllers;

use App\Http\Requests\MatchStoreRequest;
use App\Models\Match;

class MatchController extends Controller
{
    public function store(MatchStoreRequest $request)
    {
        Match::create([
            'winner_id' => $request->input('winner'),
            'looser_id' => $request->input('looser'),
        ])->calcRatings()->each(function ($catAndRating) {
            ['cat' => $cat, 'rating' => $rating] = $catAndRating;
            $cat->update(compact('rating'));
        });

        return new \Illuminate\Http\JsonResponse(['success' => 'true'], 201);
    }
}
