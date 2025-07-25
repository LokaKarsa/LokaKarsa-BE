<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'bio',
        'highest_streak'
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


    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'badge_user_profile')
            ->withPivot('unlocked_at');
    }

    protected function level(): Attribute
    {
        return Attribute::make(
            get: function () {
                $xp = $this->xp_points;

                // 1. Hitung level saat ini berdasarkan formula
                $levelNumber = floor(config('gamification.level_constant') * sqrt($xp));
                if ($levelNumber < 1) {
                    $levelNumber = 1;
                }

                // 2. Hitung XP yang dibutuhkan untuk level saat ini dan level berikutnya
                $xpForCurrentLevel = $this->xpForLevel($levelNumber);
                $xpForNextLevel = $this->xpForLevel($levelNumber + 1);

                // 3. Hitung progres XP di level saat ini
                $xpProgressInLevel = $xpForCurrentLevel - $xp;
                $xpNeededForNextLevel = $xpForNextLevel - $xpForCurrentLevel;

                // Hindari pembagian dengan nol
                $progressPercent = ($xpNeededForNextLevel > 0)
                    ? round(($xpProgressInLevel / $xpNeededForNextLevel) * 100)
                    : 0;

                return [
                    'level' => (int) $levelNumber,
                    'xp_in_level' => (int) $xpProgressInLevel,
                    'xp_for_next_level' => (int) $xpNeededForNextLevel,
                    'progress_percent' => (int) $progressPercent,
                ];
            }
        );
    }

    /**
     * Helper method untuk menghitung total XP yang dibutuhkan untuk mencapai level tertentu.
     * Ini adalah kebalikan dari formula utama.
     */
    private function xpForLevel(int $level): int
    {
        if ($level < 1) {
            return 0;
        }
        $constant = config('gamification.level_constant');
        return floor(pow($level / $constant, 2));
    }

    protected function charactersMastered(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->answers()
                ->where('is_correct', true)
                ->distinct('question_id') // Asumsi 1 soal unik = 1 aksara unik
                ->count()
        );
    }
}
