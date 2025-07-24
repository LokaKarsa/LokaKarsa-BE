<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon_url',
        'type',
        'condition_value',
    ];

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(UserProfile::class, 'badge_user_profile')
            ->withPivot('unlocked_at');
    }
}
