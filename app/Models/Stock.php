<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'item_id',
        'batch_code', 
        'expiry',
        'purchase_qty',
        'sale_qty',
        'foc',
        'in_stock',
        'supplier_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
