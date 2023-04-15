<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price','quantity'];
}
