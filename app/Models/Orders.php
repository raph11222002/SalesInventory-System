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
        'total_amount',
        'customer_payment',
        'change_amount',
        'payment_method',
    ];

    // Relationship: belongs to a consumable
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
