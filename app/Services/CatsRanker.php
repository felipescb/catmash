<?php

namespace App\Services;

use App\Models\Cat;
use Illuminate\Support\Collection;

class CatsRanker
{
    /** @var  Collection */
    protected $cats;

    public function rank(Collection $cats): Collection
    {
        return $this
            ->assignInitialRankings($cats)
            ->adjustRankingsForTies()
            ->returnCats();
    }

    protected function returnCats(): Collection
    {
        return $this->cats->sortBy('rank');
    }

    protected function adjustRankingsForTies(): self
    {
        $this->cats = $this->cats->groupBy('rating')->map(function ($tiedRatings) {
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

    protected function assignInitialRankings(Collection $cats): self
    {
        $this->cats = $cats->sortByDesc('rating')
            ->zip(range(1, $cats->count()))
            ->mapSpread(function (Cat $cat, int $rank) {
                return array_merge($cat->toArray(), compact('rank'));
            });

        return $this;
    }
}
