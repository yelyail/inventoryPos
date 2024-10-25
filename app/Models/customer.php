<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'customer_address',
    ];
    public $timestamps = true;
    public function order()
    {
        return $this->hasMany(order::class, 'customer_id');
    }
}
