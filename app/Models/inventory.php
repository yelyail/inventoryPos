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
        'product_id',
        'date_arrived',
        'warranty_supplier',
        'status',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'product_id');
    }
}
