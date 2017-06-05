<?php

namespace Tests\Http\Controllers;

use App\Models\Cat;
use App\Models\Match;

class MatchControllerTest extends \Tests\TestCase
{
    public function testReturnAnErrorIfACatIsMissingInRequest()
    {
        $response = $this->postJson('matches', [
            'winner' => factory(Cat::class)->create()->id,
        ]);

        $response->assertStatus(422);
        $response->assertExactJson(['looser' => ["The looser field is required."]]);
    }

    public function testReturnAnErrorIfACatDoesNotExists()
    {
        $response = $this->postJson('matches', [
            'winner' => str_random(8),
            'looser' => str_random(8),
        ]);

        $response->assertStatus(422);
        $response->assertExactJson([
            'looser' => ['The selected looser is invalid.'],
            'winner' => ['The selected winner is invalid.'],
        ]);
    }

    public function testCreateANewMatch()
    {

        $this->disableExceptionHandling();

        $this->assertEquals(0, Match::count());

        $response = $this->postJson('matches', [
            'winner' => factory(Cat::class)->create()->id,
            'looser' => factory(Cat::class)->create()->id,
        ]);

        $this->assertEquals(1, Match::count());

        $response->assertStatus(201);
    }

    public function testRatingsAreUpdated()
    {
        config([
            'catmash.default_rating' => 1000,
            'catmash.k_repartition'  => ['0' => 20],
        ]);
        $this->disableExceptionHandling();

        [$winner, $looser] = factory(Cat::class, 2)->create();

        $this->assertEquals(null, $winner->rating);
        $this->assertEquals(null, $looser->rating);

        $this->postJson('matches', [
            'winner' => $winner->id,
            'looser' => $looser->id,
        ]);

        $this->assertEquals(1010, $winner->fresh()->rating);
        $this->assertEquals(990, $looser->fresh()->rating);
    }

    public function testItShouldListTwoRandomCats()
    {
        $cats = collect()->times(3, function ($id) {
            return tap([
                'id'  => 'cat' . $id,
                'url' => 'url/to/cat' . $id,
            ], function ($attributes) {
                Cat::create($attributes);
            });
        });

        $response = $this->getJson('/');
        $json = $response->json();

        $this->assertCount(2, $json);

        $response->assertJsonStructure([
            '*' => ['id', 'url'],
        ]);

        $this->assertNotEquals($json[0], $json[1]);
        $this->assertContains($json[0], $cats->toArray());
        $this->assertContains($json[1], $cats->toArray());
    }
}
