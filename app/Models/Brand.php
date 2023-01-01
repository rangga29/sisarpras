<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    public function cons_items()
    {
        return $this->hasMany(ConsItem::class, 'brand_id');
    }

    public function non_cons_items()
    {
        return $this->hasMany(NonConsItem::class, 'brand_id');
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