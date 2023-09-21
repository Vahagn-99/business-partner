<?php

namespace App\Models;

use App\Services\Files\HasImages;
use App\Services\Filters\HasFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * attributes
 * @property string name
 * @property float price
 * @property string description
 * @property boolean in_stock
 * @property int category_id
 * relations
 * @property Category category
 * @property Collection<Image> images
 */
class Product extends Model
{
    use HasFactory, HasFilter, HasImages;

    protected $table = 'products';
    protected $fillable = [
          'name'
        , 'price'
        , 'description'
        , 'in_stock'
        , 'category_id'
    ];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class
            , 'category_id'
            , 'id'
        );
    }
}
