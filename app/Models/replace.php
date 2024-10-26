<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class replace extends Model
{
    use HasFactory;
    protected $table = 'replace';
    protected $primaryKey = 'replace_id';
    protected $fillable = [
        'inventory_id',
        'replace_date',
        'replace_reason',
        'replace_status',
    ];

    public function inventory() {
        return $this->belongsTo(inventory::class, 'inventory_id', 'inventory_id');
    }
    
    
    
}
