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
        'user_id',
        'payment_id',
        'inventory_id',
        'order_date',
        'total_amount',
        'qty_order',
        'status',
    ];
    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id','customer_id');
    }
    // Relationship with User (e.g., the person who created the order)
    public function user()
    {
        return $this->belongsTo(user::class, 'user_id', 'user_id');
    }

    // Relationship with Payment
    public function payment()
    {
        return $this->belongsTo(paymentMethod::class, 'payment_id', 'payment_id');
    }

    // Relationship with Inventory
    public function inventory()
    {
        return $this->belongsTo(inventory::class, 'inventory_id','inventory_id');
    }
}
