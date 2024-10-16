<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $primaryKey = 'Supplier_ID';
 
    protected $fillable = [
        'Company_Name',
        'Contact',
        'Location'
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'Supplier_ID');
    }

    public $timestamps = false;
}
