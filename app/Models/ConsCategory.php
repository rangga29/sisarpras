<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ConsCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'category_name',
        'category_slug',
    ];

    public function cons_categories()
    {
        return $this->hasMany(ConsSubCategory::class, 'cons_category_id');
    }

    public function getRouteKeyName()
    {
        return 'category_slug';
    }

    public function sluggable(): array
    {
        return [
            'category_slug' => [
                'source' => 'category_name'
            ]
        ];
    }
}