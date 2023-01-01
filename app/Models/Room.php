<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'unit_id'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function cons_items()
    {
        return $this->hasMany(ConsItems::class, 'room_id');
    }

    public function non_cons_items()
    {
        return $this->hasMany(NonConsItems::class, 'room_id');
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