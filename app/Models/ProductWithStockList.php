<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWithStockList extends Model
{
    use HasFactory;

    protected $table = 'product_with_stock_list';

    protected $fillable = [
        'product_id',
        'product_name',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productStockInList()
    {
        return $this->hasMany(ProductStockInList::class, 'product_id', 'product_id');
    }
}