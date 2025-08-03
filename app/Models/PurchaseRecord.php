<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRecord extends Model
{
    protected $fillable = [
        'item_id',
        'pack_qty',
        'purchase_rate',
        'purchase_qty',
        'sale_rate',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
