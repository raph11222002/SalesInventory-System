<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockInList extends Model
{
    use HasFactory;

    protected $table = 'product_stock_in_list';

    protected $fillable = [
        'admin_id',
        'product_id',
        'quantity_added',
        'date_received',
    ];

    // Relationship: belongs to a consumable
    public function product()
    {
        return $this->belongsTo(ProductWithStockList::class, 'product_id');
    }
}
