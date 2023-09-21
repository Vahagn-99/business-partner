<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int cart_id
 * @property int product_id
 * @property int quantity
 */
class CartProduct extends Pivot
{
    public $timestamps = true;
    protected $table = 'cart_product';
    protected $fillable = [
          'cart_id'
        , 'product_id'
        , 'quantity'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class
            , 'product_id'
            , 'id'
        );
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(
            Cart::class
            , 'cart_id'
            , 'id'
        );
    }
}
