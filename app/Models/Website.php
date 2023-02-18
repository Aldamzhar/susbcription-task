<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function subscriptions(): HasMany {
        return $this->hasMany(Subscription::class,'website_id');
    }

    public function posts(): HasMany {
        return $this->hasMany(Post::class, 'website_id');
    }

    protected $table = 'websites';
}
