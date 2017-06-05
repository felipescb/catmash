<?php

namespace Tests\Models;

use App\Models\Cat;
use Tests\TestCase;

class CatTest extends TestCase
{
    public function testKFactorIsFunctionOfRatingAndConfig()
    {
        config(['catmash.k_repartition' => [
            '2400' => 1,
            '2000' => 2,
            '1000' => 3,
            '0'    => 4,
        ]]);

        $this->assertEquals(4, $this->cat(0)->getKFactor());
        $this->assertEquals(4, $this->cat(999)->getKFactor());
        $this->assertEquals(3, $this->cat(1000)->getKFactor());
        $this->assertEquals(3, $this->cat(1999)->getKFactor());
        $this->assertEquals(2, $this->cat(2000)->getKFactor());
        $this->assertEquals(2, $this->cat(2399)->getKFactor());
        $this->assertEquals(1, $this->cat(2400)->getKFactor());
        $this->assertEquals(1, $this->cat(5000)->getKFactor());
    }

    public function testWillReturnDefaultRatingIfNull()
    {
        config(['catmash.default_rating' => 134]);

        $this->assertEquals(null, $this->cat()->rating);
        $this->assertEquals(134, $this->cat()->getRating());
    }
    private function cat($rating = null, $create = false): Cat
    {
        return factory(Cat::class)->{$create ? 'create' : 'make'}(compact('rating'));
    }
}
