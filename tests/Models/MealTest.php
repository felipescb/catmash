<?php

namespace Tests\Models;

use App\Models\Meal;
use Tests\TestCase;

class MealTest extends TestCase
{
    public function testKFactorIsFunctionOfRatingAndConfig()
    {
        config(['mealmash.k_repartition' => [
            '2400' => 1,
            '2000' => 2,
            '1000' => 3,
            '0'    => 4,
        ]]);

        $this->assertEquals(4, $this->meal(0)->getKFactor());
        $this->assertEquals(4, $this->meal(999)->getKFactor());
        $this->assertEquals(3, $this->meal(1000)->getKFactor());
        $this->assertEquals(3, $this->meal(1999)->getKFactor());
        $this->assertEquals(2, $this->meal(2000)->getKFactor());
        $this->assertEquals(2, $this->meal(2399)->getKFactor());
        $this->assertEquals(1, $this->meal(2400)->getKFactor());
        $this->assertEquals(1, $this->meal(5000)->getKFactor());
    }

    public function testWillReturnDefaultRatingIfNull()
    {
        config(['mealmash.default_rating' => 134]);

        $this->assertEquals(null, $this->meal()->rating);
        $this->assertEquals(134, $this->meal()->getRating());
    }
    private function meal($rating = null, $create = false): meal
    {
        return factory(meal::class)->{$create ? 'create' : 'make'}(compact('rating'));
    }
}
