<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class NonConsSubCategory extends Model
{
    use Sluggable;

    protected $fillable = [
        'non_cons_category_id',
        'sub_category_code',
        'sub_category_name',
        'sub_category_slug',
    ];

    public function non_cons_category()
    {
        return $this->belongsTo(NonConsCategory::class, 'non_cons_category_id');
    }

    public function non_cons_items()
    {
        return $this->hasMany(NonConsItem::class, 'non_cons_sub_category_id');
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