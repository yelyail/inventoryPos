<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $fillable = [
        'category_name',
        'category_description',
        'brand_name',
    ];
}
