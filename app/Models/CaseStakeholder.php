<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStakeholder extends Model
{
    use HasFactory;
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

    public function cases()
    {
        return $this->belongsTo(Cases::class);
    }
}
