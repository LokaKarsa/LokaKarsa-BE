<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    use HasFactory;

    // Izinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'sex',
        'region',
        'date_of_birth',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    /**
     * Get all of the progress records for the User.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
