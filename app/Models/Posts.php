<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posts extends Model
{
    protected $fillable = [
        'title',
        'body'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
