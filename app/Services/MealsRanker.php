<?php

namespace App\Services;

use App\Models\Meal;
use Illuminate\Support\Collection;

class MealsRanker
{
    /** @var  Collection */
    protected $meals;

    public function rank(Collection $meals): Collection
    {
        return $this
            ->assignInitialRankings($meals)
            ->adjustRankingsForTies()
            ->returnMeals();
    }

    protected function returnMeals(): Collection
    {
        return $this->meals->sortBy('rank');
    }

    protected function adjustRankingsForTies(): self
    {
        $this->meals = $this->meals->groupBy('rating')->map(function ($tiedRatings) {
            return $this->applyMinRank($tiedRatings);
        })->collapse();

        return $this;
    }

    protected function applyMinRank(Collection $tiedRatings): Collection
    {
        return $tiedRatings->map(function ($rankedScore) use ($tiedRatings) {
            return array_merge($rankedScore, [
                'rank' => $tiedRatings->pluck('rank')->min(),
            ]);
        });
    }

    protected function assignInitialRankings(Collection $meals): self
    {
        $this->meals = $meals->sortByDesc('rating')
            ->zip(range(1, $meals->count()))
            ->mapSpread(function (Meal $meal, int $rank) {
                return array_merge($meal->toArray(), compact('rank'));
            });

        return $this;
    }
}
