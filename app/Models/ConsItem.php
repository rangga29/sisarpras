<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cons_sub_category_id',
        'brand_id',
        'shop_id',
        'fund_id',
        'room_id',
        'unit_id',
        'item_code',
        'name',
        'initial_amount',
        'taken_amount',
        'stock_amount',
        'price',
        'purchase_date',
        'image',
        'receipt',
        'description'
    ];

    public function cons_sub_category()
    {
        return $this->belongsTo(ConsSubCategory::class, 'cons_sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'fund_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function pickup_items()
    {
        return $this->hasMany(PickupItem::class, 'cons_item_id');
    }

    public function getRouteKeyName()
    {
        return 'item_code';
    }
}