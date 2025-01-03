<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    
    protected $table = 'product';
    protected $primaryKey = 'product_ID';

    protected $fillable = [
        'supplier_ID',
        'category_Id',
        'product_name',
        'product_description',
        'unitPrice',
        'added_date',
        'typeOfUnit',
        'product_image',
    ];
    public function inventory() 
    {
        return $this->hasOne(inventory::class, 'product_id', 'product_ID');
    }

    public function supplier()
    {
        return $this->belongsTo(supplier::class, 'supplier_ID', 'supplier_ID');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_Id', 'category_id');
    }

    public function serial()
    {
        return $this->hasOne(serial::class, 'product_id', 'product_ID'); 
    }
}
