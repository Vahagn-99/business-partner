<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * attributes
 * @property string name
 * @property int parent_id
 * @property int level
 * relations
 * @property Category parent
 * @property Collection<Category> subcategories
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
          'name'
        , 'parent_id'
        , 'level'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            __CLASS__
            , 'parent_id'
            , 'id'
        );
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(
            __CLASS__
            , 'parent_id'
            , 'id'
        );
    }

    public function products(): HasMany
    {
        return $this->hasMany(
            Product::class
            , 'category_id'
            , 'id'
        );
    }
}
