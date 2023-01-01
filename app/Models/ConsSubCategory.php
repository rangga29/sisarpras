<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ConsSubCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'cons_category_id',
        'sub_category_name',
        'sub_category_slug',
    ];

    public function cons_category()
    {
        return $this->belongsTo(ConsCategory::class, 'cons_category_id');
    }

    public function cons_items()
    {
        return $this->hasMany(ConsItem::class, 'cons_sub_category_id');
    }

    public function getRouteKeyName()
    {
        return 'sub_category_slug';
    }

    public function sluggable(): array
    {
        return [
            'sub_category_slug' => [
                'source' => 'sub_category_name'
            ]
        ];
    }
}