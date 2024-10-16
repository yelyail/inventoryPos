<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair_products extends Model
{
    use HasFactory;

    protected $table = 'repairing_products';

    protected $primaryKey = 'Repair_ID';


    protected $fillable = [
        'Inventory_ID',
        'Technician_ID',
        'date_repaired',
        'status'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'Inventory_ID');
    }

    public function technician()
    {
        return $this->belongsTo(Technicians::class, 'Technician_ID');
    }

     public function brand()
    {
        return $this->belongsTo(Brand::class, 'Brand_ID');
    }

    // Relationship with Category
   public function category()
    {
    return $this->belongsTo(Category::class, 'Category_ID', 'Category_ID');
    }

     public function product()
    {
    return $this->belongsTo(Product::class, 'Product_ID', 'Product_ID');
    
    }

    public $timestamps = false;
}
