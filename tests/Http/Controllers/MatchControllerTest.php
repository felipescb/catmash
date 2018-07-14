<?php

namespace Tests\Http\Controllers;

use App\Models\Meal;
use App\Models\Match;

class MatchControllerTest extends \Tests\TestCase
{
    public function testReturnAnErrorIfAMealIsMissingInRequest()
    {
        $response = $this->postJson('matches', [
            'winner' => factory(Meal::class)->create()->id,
        ]);

        $response->assertStatus(422);
        $response->assertExactJson(['looser' => ["The looser field is required."]]);
    }

    public function testReturnAnErrorIfAMealDoesNotExists()
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
            'winner' => factory(Meal::class)->create()->id,
            'looser' => factory(Meal::class)->create()->id,
        ]);

        $this->assertEquals(1, Match::count());

        $response->assertStatus(201);
    }

    public function testRatingsAreUpdated()
    {
        config([
            'mealmash.default_rating' => 1000,
            'mealmash.k_repartition'  => ['0' => 20],
        ]);
        $this->disableExceptionHandling();

        [$winner, $looser] = factory(Meal::class, 2)->create();

        $this->assertEquals(null, $winner->rating);
        $this->assertEquals(null, $looser->rating);

        $this->postJson('matches', [
            'winner' => $winner->id,
            'looser' => $looser->id,
        ]);

        $this->assertEquals(1010, $winner->fresh()->rating);
        $this->assertEquals(990, $looser->fresh()->rating);
    }

    public function testItShouldListTwoRandomMeals()
    {
        $Meals = collect()->times(3, function ($id) {
            return tap([
                'id'  => 'Meal' . $id,
                'url' => 'url/to/Meal' . $id,
            ], function ($attributes) {
                Meal::create($attributes);
            });
        });

        $response = $this->getJson('/');
        $json = $response->json();

        $this->assertCount(2, $json);

        $response->assertJsonStructure([
            '*' => ['id', 'url'],
        ]);

        $this->assertNotEquals($json[0], $json[1]);
        $this->assertContains($json[0], $Meals->toArray());
        $this->assertContains($json[1], $Meals->toArray());
    }

    public function testReturnViewIfNotXhr()
    {
        $response = $this->get('/');
        $response->assertViewIs('home');
        $response->assertSeeText('MealMash');
        $response->assertSeeText('Mathieu TUDISCO');
    }
}
