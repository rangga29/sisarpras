<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $fillable = [
        'non_cons_item_id',
        'consumer_id',
        'unit_id',
        'loan_code',
        'con_loan_id',
        'con_return_id',
        'loan_date',
        'return_date',
        'description'
    ];

    public function non_cons_item()
    {
        return $this->belongsTo(NonConsItem::class, 'non_cons_item_id');
    }

    public function consumer()
    {
        return $this->belongsTo(Consumer::class, 'consumer_id');
    }

    public function loan_condition()
    {
        return $this->belongsTo(NonConsCondition::class, 'con_loan_id');
    }

    public function loan_return_condition()
    {
        return $this->belongsTo(NonConsCondition::class, 'con_return_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getRouteKeyName()
    {
        return 'loan_code';
    }
}