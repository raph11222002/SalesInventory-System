<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;

    protected $table = 'stock_list';

    protected $fillable = [
        'inventory_id',
        'quantity',
    ];

    /**
     * Relationship: StockList belongs to Inventory
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    /**
     * Optional: Access the related product via inventory
     */
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Inventory::class,
            'id',          // Foreign key on inventories
            'id',          // Foreign key on product_list
            'inventory_id',// Local key on stock_list
            'product_id'   // Local key on inventories
        );
    }
}
