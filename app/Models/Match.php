<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Match
 *
 * @property int $id
 * @property int $winner_id
 * @property int $looser_id
 * @property \Carbon\Carbon $created_at
 * @property-read \App\Models\Meal $looser
 * @property-read \App\Models\Meal $winner
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Match whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Match whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Match whereLooserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Match whereWinnerId($value)
 * @mixin \Eloquent
 */
class Match extends Model
{
    protected $fillable = ['winner_id', 'looser_id'];

    public function setUpdatedAt($value)
    {
        // We don't have any 'updated_at' field.
        return $this;
    }

    public function winner()
    {
        return $this->belongsTo(Meal::class);
    }

    public function looser()
    {
        return $this->belongsTo(Meal::class);
    }

    public function calcRatings()
    {
        return collect(['winner', 'looser'])->mapWithKeys(function ($type) {
            return [$type => [
                'meal'    => $this->$type,
                'rating' => $this->calcRating($this->$type),
            ]];
        });
    }

    protected function calcRating(Meal $meal)
    {
        // https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
        $R = $meal->getRating();
        $K = $meal->getKFactor();
        $S = $this->getMatchScore($meal);
        $E = $this->getExpectedScore($meal);

        $newRating = $R + $K * ($S - $E);

        // We doesn't want any player with a rank under a min_rating
        return max(config('mealmash.min_rating'), (int) round($newRating));
    }

    protected function getMatchScore(Meal $meal)
    {
        return (int) $meal->is($this->winner);
    }

    protected function getExpectedScore(Meal $meal)
    {
        // 1 / (1 + 10^((Ra-Rb)/400)) => Qa / (Qa + Qb)

        $diff = $this->otherMeal($meal)->getRating() - $meal->getRating(); // Ra - Rb
        $diffSign = $diff < 0 ? -1 : 1; // -1 or +1
        $diff = min(abs($diff), 400) * $diffSign; // |Ra-Rb| should be min 400

        return 1 / (1 + 10 ** ($diff / 400));
    }

    protected function otherMeal(Meal $meal): Meal
    {
        return $meal->is($this->winner) ? $this->looser : $this->winner;
    }
}
