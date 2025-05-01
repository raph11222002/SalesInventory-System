<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id',
        'image_path',
        'product_group',
        'product_name',
        'product_price',
    ];

    /**
     * Get the inventories associated with the product.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_id');
    }    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}