<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string url
 */
class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
          'url'
        , 'image_owner__id'
        , 'image_owner_type'
    ];

    public function imageOwner(): MorphTo
    {
        return $this->morphTo();
    }
}
