<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['shop_id', 'status', 'message', 'images_fetched_count', 'images_failed_count'])]
class InstagramSyncLog extends Model
{
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
