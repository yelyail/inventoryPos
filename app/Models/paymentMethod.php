<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentMethod extends Model
{
    use HasFactory;

    protected $table = 'paymentmethod';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'paymentType',
        'reference_num',
        'amount_paid',
        'discount',
    ];
    public $timestamps = true;
    public function order()
    {
        return $this->hasMany(orders::class, 'payment_id','payment_id');
    }

}
