<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_code',
        'item_name',
        'pack_size',
        'sale_qty',
        'foc',
        'sale_rate',
        'amount',
        'pdf_path', 
    ];

    // Relationship: belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
