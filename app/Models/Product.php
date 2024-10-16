<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'Product_ID';

    protected $fillable = [
        'Image',
        'Category_ID',
        'Brand_ID',
        'product_name',
        'description',
        'status',

    ];

    public $timestamps = true;

    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'Brand_ID');
    }

    // Relationship with Category
   public function category()
{
    return $this->belongsTo(Category::class, 'Category_ID', 'Category_ID');
}
public function inventory()
{
    return $this->hasMany(Inventory::class);
}



}
