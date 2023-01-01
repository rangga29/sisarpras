<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConsItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'non_cons_sub_category_id',
        'brand_id',
        'shop_id',
        'fund_id',
        'room_id',
        'non_cons_condition_id',
        'unit_id',
        'item_code',
        'item_number',
        'name',
        'price',
        'purchase_date',
        'include',
        'image',
        'receipt',
        'description',
        'availability'
    ];

    public function non_cons_sub_category()
    {
        return $this->belongsTo(NonConsSubCategory::class, 'non_cons_sub_category_id');
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

    public function non_cons_condition()
    {
        return $this->belongsTo(NonConsCondition::class, 'non_cons_condition_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function loan_items()
    {
        return $this->hasMany(LoanItem::class, 'non_cons_item_id');
    }

    public function placement_items()
    {
        return $this->hasMany(PlacementItem::class, 'non_cons_item_id');
    }

    public function getRouteKeyName()
    {
        return 'item_code';
    }
}