<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = ['notification_thresholds'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($userProfile) {
            if (is_null($userProfile->notification_thresholds)) {
                $userProfile->notification_thresholds = json_encode(['precipitation' => 10, 'uv_index' => 6]);
            }
        });
    }
}
