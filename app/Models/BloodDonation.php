<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_donation_date',
        'blood_group',
        'phone',
    ];

    protected $casts = [
        'last_donation_date' => 'date',
    ];
}
