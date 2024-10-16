<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'supplier_id',
        'category_Id',
        'serial_number',
        'product_name',
        'unitPrice',
        'stock_quantity',
        'warranty_product',
        'added_date',
        'typeOfUnit',
        'product_image',
    ];
}
