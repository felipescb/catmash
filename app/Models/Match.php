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
 * @property-read \App\Models\Cat $looser
 * @property-read \App\Models\Cat $winner
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
        return $this->belongsTo(Cat::class);
    }

    public function looser()
    {
        return $this->belongsTo(Cat::class);
    }

    public function calcRatings()
    {
        return [
            'winner' => $this->calcRating($this->winner),
            'looser' => $this->calcRating($this->looser),
        ];
    }

    protected function calcRating(Cat $cat)
    {
        // https://en.wikipedia.org/wiki/Elo_rating_system#Mathematical_details
        $R =  $cat->rating;
        $K = $cat->getKFactor();
        $S = $this->getMatchScore($cat);
        $E = $this->getExpectedScore($cat);

        $newRating = $R + $K * ($S - $E);

        // We doesn't want any player with a rank under a min_rating
        return max(config('catmash.min_rating'), (int) round($newRating));
    }

    protected function getMatchScore(Cat $cat)
    {
        return (int) $cat->is($this->winner);
    }

    protected function getExpectedScore(Cat $cat)
    {
        // 1 / (1 + 10^((Ra-Rb)/400)) => Qa / (Qa + Qb)

        $diff = $this->otherCat($cat)->rating - $cat->rating; // Ra - Rb
        $diffSign = $diff / abs($diff); // -1 or +1
        $diff = min(abs($diff), 400) * $diffSign; // |Ra-Rb| should be min 400

        return 1 / (1 + 10 ** ($diff / 400));
    }

    protected function otherCat(Cat $cat): Cat
    {
        return $cat->is($this->winner) ? $this->looser : $this->winner;
    }
}
