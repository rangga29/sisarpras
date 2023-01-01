<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class NonConsCondition extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function non_cons_conditions()
    {
        return $this->hasMany(NonConsItem::class, 'non_cons_condition_id');
    }

    public function loan_conditions()
    {
        return $this->hasMany(LoanItem::class, 'con_loan_id');
    }

    public function loan_return_conditions()
    {
        return $this->hasMany(LoanItem::class, 'con_return_id');
    }

    public function placement_conditions()
    {
        return $this->hasMany(PlacementItem::class, 'con_placement_id');
    }

    public function placement_return_conditions()
    {
        return $this->hasMany(PlacementItem::class, 'con_return_id');
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