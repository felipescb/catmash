<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Meal
 *
 * @property string $id
 * @property string $url
 * @property int $rating
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Meal whereUrl($value)
 * @mixin \Eloquent
 */
class Meal extends Model
{
    protected $fillable = ['id', 'url', 'rating'];
    protected $keyType = 'string';
    public $incrementing = false;

    public function getKFactor(): int
    {
        return array_first(config('mealmash.k_repartition'), function ($_, $min_rate) {
            return $this->getRating() >= $min_rate;
        });
    }

    public function getRating(): int
    {
        return $this->rating ?? config('mealmash.default_rating');
    }
}
