<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'position_id',
        'unit_id'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function pickup_items()
    {
        return $this->hasMany(PickupItem::class, 'consumer_id');
    }

    public function loan_items()
    {
        return $this->hasMany(LoanItem::class, 'consumer_id');
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