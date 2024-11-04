<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class repair extends Model
{
    use HasFactory;

    protected $table = 'repair';
    protected $primaryKey = 'repair_id';
    protected $fillable = [
        'order_id',
        'return_date',
        'return_reason',
    ];
    public function order()
    {
        return $this->belongsTo(orders::class, 'order_id', 'order_id'); 
    }
}
