<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWithStock extends Model
{
    use HasFactory;

    protected $table = 'product_with_stock';

    protected $fillable = [
        'product_id',
        'product_name',
        'required_stock',
    ];
}
