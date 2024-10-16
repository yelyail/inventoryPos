<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id',
        'supplier_id',
        'user_id',
        'payment_id',
        'inventory_id',
        'order_date',
        'total_amount',
        'qty_order'
    ];
}
