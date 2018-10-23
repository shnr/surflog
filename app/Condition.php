<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = ['surf_datetime', 'location', 'location_lat', 'location_lng', 'condition', 'swell_height', 'swell_direction', 'wind_size', 'wind_direction', 'wetsuits', 'surfboard', 'comment', 'memo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
