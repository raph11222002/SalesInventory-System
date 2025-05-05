<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductConsumable extends Model
{
    use HasFactory;

    protected $table = 'product_consumable_needed';

    protected $fillable = [
        'product_id',
        'product_name',
        'consumable_id',
        'consumable_name',
        'quantity_needed',
    ];

    public function consumable()
    {
        return $this->belongsTo(ConsumableList::class, 'consumable_id');
    }
}
