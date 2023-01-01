<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function cons_items()
    {
        return $this->hasMany(ConsItem::class, 'fund_id');
    }

    public function non_cons_items()
    {
        return $this->hasMany(NonConsItem::class, 'fund_id');
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