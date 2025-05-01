<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'inventory_name',
        'total_quantity',
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function stockItems()
    {
        return $this->hasMany(Stocks::class, 'inventory_id');
    }

    // Accessor for dynamic total quantity
    public function getTotalQuantityAttribute()
    {
        return $this->stockItems->sum('quantity');
    }
}