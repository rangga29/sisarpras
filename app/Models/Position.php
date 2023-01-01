<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function consumers()
    {
        return $this->hasMany(Consumer::class, 'position_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}