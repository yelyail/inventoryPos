<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class technicians extends Model
{
    use HasFactory;

    protected $table = 'technician';

    protected $primaryKey = 'Technician_ID';
 
    protected $fillable = [
        'Name',
        'Position_Level'
    ];

    public $timestamps = false;
}