<?php

namespace Tests\Models;

use App\Models\Meal;
use App\Models\Match;
use Tests\TestCase;

class MatchTest extends TestCase
{

    public function testRatingCalculationIsOkWhenDiffIsUnder400()
    {
        config(['mealmash.k_repartition' => ['0' => 15]]);

        $this->assertEquals(
            ['winner' => 1811, 'looser' => 1994],
            $this->calcRatingsForMatch(1800, 2005)
        );
    }

    private function calcRatingsForMatch($winnerRating = null, $looserRating = null): array
    {
        return Match::create([
            'winner_id' => $this->meal($winnerRating)->id,
            'looser_id' => $this->meal($looserRating)->id,
        ])->calcRatings()->mapWithKeys(function ($mealAndRating, $type) {
            return [$type => $mealAndRating['rating']];
        })->toArray();
    }

    private function meal($rating = null): Meal
    {
        return factory(Meal::class)->create(compact('rating'));
    }

    public function testRatingCalculationIsOkWhenDiffIsAbove400()
    {
        config(['mealmash.k_repartition' => ['0' => 20]]);

        $this->assertEquals(
            ['winner' => 1518, 'looser' => 2382],
            $this->calcRatingsForMatch(1500, 2400)
        );
    }

    public function testSumOfRatingsStaysTheSameWhenSameK()
    {
        config(['mealmash.k_repartition' => ['0' => 20]]);

        $newRatings = $this->calcRatingsForMatch(1500, 2400);
        $this->assertEquals(1500 + 2400, array_sum($newRatings));
    }

    public function testItOnlyDependsOnTheRatingsDiff()
    {
        config(['mealmash.k_repartition' => ['0' => 20]]);

        $newRatings1 = $this->calcRatingsForMatch(1000, 1300);
        $newRatings2 = $this->calcRatingsForMatch(2000, 2300);

        $this->assertEquals(
            $newRatings2['winner'] - $newRatings2['looser'],
            $newRatings1['winner'] - $newRatings1['looser']
        );
    }
}
