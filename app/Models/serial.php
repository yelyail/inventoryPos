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
    ];
    public $timestamps = true;
    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
}
