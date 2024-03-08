<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $table = 'vaccinations';
    public $timestamps = false;

    protected $fillable = [
        'dose', 'date', 'society_id', 'spot_id', 'vaccine_id', 'doctor_id', 'officer_id' 
    ];

    protected $hidden = [
        "spot_id",
        "doctor_id",
        "society_id",
        "officer_id"
    ];

    public function spot(){
        return $this->belongsTo(Spot::class);
    }

    public function vaccine(){
        return $this->belongsTo(Vaccine::class);
    }
    public function vaccinator(){
        return $this->belongsTo(Medical::class, 'doctor_id', 'id');
    }
}
