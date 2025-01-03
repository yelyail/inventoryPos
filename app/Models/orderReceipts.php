<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderReceipts extends Model
{
    use HasFactory;
    protected $table = 'orderreceipts';
    protected $primaryKey = 'orderreceipts_id';

    protected $fillable = [
        'customer_id',
        'payment_id',
        'order_id',
        'order_date',
        'status',
    ];
    public function customer()  
    {
        return $this->belongsTo(customer::class, 'customer_id', 'customer_id');
    }

    public function payment()
    {
        return $this->belongsTo(paymentMethod::class, 'payment_id', 'payment_id'); 
    }

    public function orders()
    {
        return $this->hasMany(orders::class, 'order_id', 'order_id');
    }

}
