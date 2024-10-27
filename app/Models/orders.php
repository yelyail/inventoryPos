<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'product_id',
        'total_amount',
        'qtyOrder',
    ];
    public function orderreceipts()
    {
        return $this->hasMany(orderreceipts::class);
    }   
    public function product()
    {
        return $this->belongsTo(product::class);
    }
}
