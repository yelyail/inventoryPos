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
        'product_name',
        'unitPrice',
        'added_date',
        'typeOfUnit',
        'product_image',
    ];
    public function inventory()
    {
        return $this->hasMany(inventory::class, 'product_id', 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(supplier::class, 'supplier_id', 'supplier_id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_Id', 'category_id');
    }
    public function serial()
    {
        return $this->hasMany(serial::class, 'product_id', 'product_id');
    }

}
