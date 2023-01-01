<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementItem extends Model
{
    protected $fillable = [
        'non_cons_item_id',
        'room_id',
        'unit_id',
        'placement_code',
        'con_placement_id',
        'con_return_id',
        'placement_date',
        'return_date',
        'description'
    ];

    public function non_cons_item()
    {
        return $this->belongsTo(NonConsItem::class, 'non_cons_item_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function placement_condition()
    {
        return $this->belongsTo(NonConsCondition::class, 'con_placement_id');
    }

    public function placement_return_condition()
    {
        return $this->belongsTo(NonConsCondition::class, 'con_return_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getRouteKeyName()
    {
        return 'placement_code';
    }
}