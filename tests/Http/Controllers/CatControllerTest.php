<?php

namespace Tests\Http\Controllers;

use App\Models\Cat;

class CatControllerTest extends \Tests\TestCase
{
    public function testItShouldReturnAllCatsIfJsonXHR()
    {
        $cats = collect()->times(15, function ($id) {
            return tap([
                'id'     => 'cat' . $id,
                'url'    => 'url/to/cat' . $id,
                'rating' => $id <= 10 ? 11 - $id : null,
            ], function ($attributes) {
                Cat::create($attributes);
            });
        });

        $response = $this->getJson('/cats');

        $response->assertJsonStructure([
            'topRankedCats'   => ['*' => ['id', 'url', 'rating', 'rank']],
            'otherRankedCats' => ['*' => ['id', 'url', 'rating', 'rank']],
            'notRankedCats'   => ['*' => ['id', 'url', 'rating']],
        ]);

        $json = $response->json();
        $this->assertCount(3, $json);
        $this->assertCount(5, $json['topRankedCats']);
        $this->assertCount(5, $json['otherRankedCats']);
        $this->assertCount(5, $json['notRankedCats']);

        $cats = $cats->map(function ($cat, $key) {
            $rank = $key + 1;

            return $rank > 10 ? $cat : array_merge($cat, compact('rank'));
        });

        $this->assertEquals($cats->splice(0, 5)->toArray(), $json['topRankedCats']);
        $this->assertEquals($cats->splice(0, 5)->toArray(), $json['otherRankedCats']);
        $this->assertEquals($cats->splice(0, 5)->toArray(), $json['notRankedCats']);
    }

    public function testItShouldReturnViewIfNotXHR()
    {
        $response = $this->get('/cats');
        $response->assertViewIs('cats');
        $response->assertSeeText('CatMash');
        $response->assertSeeText('Mathieu TUDISCO');
    }
}
