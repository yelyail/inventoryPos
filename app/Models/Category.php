<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'Category_ID';
 
    protected $fillable = ['Category_Name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'Category_ID');
    }

    public $timestamps = true;

}
