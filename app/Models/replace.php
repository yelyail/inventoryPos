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
        'serial_id',
        'replace_date',
        'replace_reason',
    ];

    public function serial() {
        return $this->belongsTo(serial::class, 'serial_ide', 'serial_id ');
    }
    
    
    
}
