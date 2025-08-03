<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_code',
        'status',
    ];

    public function purchaseRecords()
    {
        return $this->hasMany(PurchaseRecord::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'item_id');
    }
}
