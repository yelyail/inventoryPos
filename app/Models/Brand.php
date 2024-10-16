<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $primaryKey = 'Brand_ID';
 
    protected $fillable = ['Brand_Name'];

     public function products()
    {
        return $this->hasMany(Product::class, 'Brand_ID');
    }

    public $timestamps = true;

}
