<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cases extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'stakeholders' => 'array',
    ];

    protected $hidden = [
        'user',
    ];

    protected $appends = [
        'lawyer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function casestakeholder()
    {
        return $this->HasMany(CaseStakeholder::class);
    }

    public function getLawyerAttribute(): ?string
    {
        if ($this->user) {
            return $this->user->name;
        }
        return null;
    }
}
