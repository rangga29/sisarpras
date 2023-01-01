<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupItem extends Model
{
    protected $fillable = [
        'cons_item_id',
        'consumer_id',
        'unit_id',
        'pickup_code',
        'pickup_date',
        'amount',
        'description'
    ];

    public function cons_item()
    {
        return $this->belongsTo(ConsItem::class, 'cons_item_id');
    }

    public function consumer()
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getRouteKeyName()
    {
        return 'pickup_code';
    }
}