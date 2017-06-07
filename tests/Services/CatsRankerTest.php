<?php

namespace Tests\Services;

use App\Services\CatsRanker;

class CatsRankerTest extends \Tests\TestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    public function testRankingIsOk()
    {
        $rankedCats = (new CatsRanker)->rank($this->getCats());

        $this->assertEquals($this->expectedRanking(), $rankedCats->pluck('rank', 'id')->toArray());
    }

    protected function getCats()
    {
        $uniqueCats = collect()->times(15, function ($time) {
            return \App\Models\Cat::make([
                'id'     => 'cat' . $time,
                'url'    => 'url/to/cat' . $time,
                'rating' => $time,
            ]);
        });

        $doubleCats = collect()->times(10, function ($time) {
            return \App\Models\Cat::make([
                'id'     => 'cat' . ($time + 15),
                'url'    => 'url/to/cat' . ($time + 15),
                'rating' => $time + 5,
            ]);
        });

        $tripleCats = collect()->times(5, function ($time) {
            return \App\Models\Cat::make([
                'id'     => 'cat' . ($time + 25),
                'url'    => 'url/to/cat' . ($time + 25),
                'rating' => $time + 10,
            ]);
        });

        return $uniqueCats->merge($doubleCats)->merge($tripleCats);
    }

    private function expectedRanking(): array
    {
        return [
            'cat15' => 1, 'cat25' => 1, 'cat30' => 1,
            'cat14' => 4, 'cat24' => 4, 'cat29' => 4,
            'cat13' => 7, 'cat23' => 7, 'cat28' => 7,
            'cat12' => 10, 'cat22' => 10, 'cat27' => 10,
            'cat11' => 13, 'cat21' => 13, 'cat26' => 13,
            'cat10' => 16, 'cat20' => 16,
            'cat9'  => 18, 'cat19' => 18,
            'cat8'  => 20, 'cat18' => 20,
            'cat7'  => 22, 'cat17' => 22,
            'cat6'  => 24, 'cat16' => 24,
            'cat5'  => 26,
            'cat4'  => 27,
            'cat3'  => 28,
            'cat2'  => 29,
            'cat1'  => 30,
        ];
    }
}
