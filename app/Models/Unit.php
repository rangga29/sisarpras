<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'slug'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'unit_id');
    }

    public function consumers()
    {
        return $this->hasMany(Consumer::class, 'unit_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'unit_id');
    }

    public function cons_items()
    {
        return $this->hasMany(ConsItem::class, 'unit_id');
    }

    public function pickup_items()
    {
        return $this->hasMany(PickupItem::class, 'unit_id');
    }

    public function non_cons_items()
    {
        return $this->hasMany(NonConsItem::class, 'unit_id');
    }

    public function loan_items()
    {
        return $this->hasMany(LoanItem::class, 'unit_id');
    }

    public function placement_item()
    {
        return $this->hasMany(PlacementItem::class, 'unit_id');
    }
}