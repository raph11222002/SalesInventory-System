<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableList extends Model
{
    use HasFactory;

    protected $table = 'consumable_list';

    protected $fillable = ['admin_id', 'consumable_name'];

    public function stockInList()
    {
        return $this->hasMany(StockInList::class, 'consumable_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_consumable_needed')
            ->withPivot('quantity_needed')
            ->withTimestamps();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->stockIns()->sum('quantity_added');
    }
}

