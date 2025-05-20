<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInList extends Model
{
    use HasFactory;

    protected $table = 'stock_in_list';

    protected $fillable = [
        'consumable_id',
        'quantity_added',
        'stock_price',
        'stock_expenses',
    ];

    // Relationship: belongs to a consumable
    public function consumable()
    {
        return $this->belongsTo(ConsumableList::class, 'consumable_id');
    }
}
