<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Date;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'contact', 'appointment_date', 'client_name', 'services'];

    protected $dates = ['appointment_date'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($reservation) {
            if ($reservation->appointment_date < Date::today()) {
                throw new \Exception("Appointment date can't be in the past");
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
