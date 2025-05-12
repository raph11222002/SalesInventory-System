<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'staff_id',
        'product_id',
        'product_group',
        'product_name',
        'quantity_ordered',
        'product_price',
        'amount',
        'payment_method',
    ];

    // Relationship: belongs to a consumable
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
