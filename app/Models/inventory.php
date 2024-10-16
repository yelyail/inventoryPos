<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $primaryKey = 'Inventory_ID';

    protected $fillable = [
        'Product_ID',            // FK to product table
        'serial_number',
        'status',
        'Supplier_ID',           // FK to supplier table
        'date_arrived',
        'date_checked',
        'date_approved',
        'warranty_supplier',
        'deliveryR_photo'
    ];

    public function product()
{
    return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    
}

public function supplier()
{
    return $this->belongsTo(Supplier::class, 'Supplier_ID');
}

public function category()
{
    return $this->belongsTo(Category::class, 'Category_ID');
}

public function brand()
{
    return $this->belongsTo(Brand::class, 'Brand_ID');
}


    public $timestamps = false;
}
