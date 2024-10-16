<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $fillable = [
        'order_id',
        'inventory_ID',
        'return_date',
        'date_arrived',
        'warranty_supplier',
        'image_delivery',
    ];

}
