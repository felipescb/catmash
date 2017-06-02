<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cat
 *
 * @property int $id
 * @property string $url
 * @property int $rating
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Cat whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Cat whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Cat whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Cat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Cat whereUrl($value)
 * @mixin \Eloquent
 */
class Cat extends Model
{
    protected $fillable = ['id', 'url', 'rating'];
    protected $keyType = 'string';
    public $incrementing = false;
}
