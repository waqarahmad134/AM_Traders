<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'invoice_number',
        'item_name',
        'pack_size',
        'sale_qty',
        'foc',
        'sale_rate',
        'amount',
        'tax',
        'sub_total',
        'batch_code',
        'expiry',
        'pdf_path', 
    ];

    // Relationship: belongsTo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
