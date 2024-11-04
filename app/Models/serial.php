<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class serial extends Model
{
    use HasFactory;
    protected $table = 'serial';
    protected $primaryKey = 'serial_id';

    protected $fillable = [
        'product_id',
        'serial_number',
        'status'
    ];
    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_ID');
    }

    public function replace() {
        return $this->hasMany(Replace::class, 'inventory_id', 'inventory_id'); // Ensure Replace is defined
    }
}
