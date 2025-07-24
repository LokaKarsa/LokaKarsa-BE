<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_profile_id',
        'question_id',
        'is_correct',
    ];

    /**
     * Mendapatkan user yang memiliki jawaban ini.
     */
    public function userProfile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Mendapatkan soal dari jawaban ini.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
