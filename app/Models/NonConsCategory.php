<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class NonConsCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'category_code',
        'category_name',
        'category_slug',
    ];

    public function non_cons_categories()
    {
        return $this->hasMany(NonConsSubCategory::class, 'non_cons_category_id');
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