<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['product_id', 'quantity'];
}
