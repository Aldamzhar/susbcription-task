<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'title',
        'description'
    ];

    protected $table = 'posts';

    public function website(): BelongsTo {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
