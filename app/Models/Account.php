<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'user';
 
    protected $fillable = ['Username', 'Password', 'Position'];

    public $timestamps = false;
}
