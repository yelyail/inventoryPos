<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    use HasFactory;
    
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    
    protected $fillable = [
        'supplier_name',
        'supplier_address',
        'supplier_email',
        'supplier_phone',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(product::class, 'supplier_id', 'supplier_id'); 
    }
}
