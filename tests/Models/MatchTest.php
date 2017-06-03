<?php

namespace Tests\Models;

use App\Models\Cat;
use App\Models\Match;
use Tests\TestCase;

class MatchTest extends TestCase
{

    public function testRatingCalculationIsOkWhenDiffIsUnder400()
    {
        config(['catmash.k_repartition' => ['0' => 15]]);

        $this->assertEquals(
            ['winner' => 1811, 'looser' => 1994],
            $this->match(1800, 2005)->calcRatings()
        );
    }

    private function match($winnerRating = null, $looserRating = null): Match
    {
        return Match::create([
            'winner_id' => $this->cat($winnerRating)->id,
            'looser_id' => $this->cat($looserRating)->id,
        ]);
    }

    private function cat($rating = null): Cat
    {
        return factory(Cat::class)->create(compact('rating'));
    }

    public function testRatingCalculationIsOkWhenDiffIsAbove400()
    {
        config(['catmash.k_repartition' => ['0' => 20]]);

        $this->assertEquals(
            ['winner' => 1518, 'looser' => 2382],
            $this->match(1500, 2400)->calcRatings()
        );
    }

    public function testSumOfRatingsStaysTheSameWhenSameK()
    {
        config(['catmash.k_repartition' => ['0' => 20]]);

        $newRatings = $this->match(1500, 2400)->calcRatings();
        $this->assertEquals(1500 + 2400, array_sum($newRatings));
    }

    public function testItOnlyDependsOnTheRatingsDiff()
    {
        config(['catmash.k_repartition' => ['0' => 20]]);

        $newRatings1 = $this->match(1000, 1300)->calcRatings();
        $newRatings2 = $this->match(2000, 2300)->calcRatings();

        $this->assertEquals(
            $newRatings2['winner'] - $newRatings2['looser'],
            $newRatings1['winner'] - $newRatings1['looser']
        );
    }
}
