<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'inventory_name',
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}