<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sold_products extends Model
{
    use HasFactory;

    protected $table = 'sold_products';

    protected $primaryKey = 'Sold_ID';


    protected $fillable = [
        'Inventory_ID',
        'InvoiceNum',
        'status',
        'date_sold'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'Inventory_ID');
    }

    public $timestamps = false;
}
