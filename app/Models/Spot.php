<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;

    protected $table = 'spots';
    public $timestamps = false;

    protected $fillable = [
        'regional_id', 'name', 'address', 'serve', 'capacity'
    ];

    public function regional(){
        return $this->belongsTo(Regional::class);
    }

    public function vaccination(){
        return $this->hasMany(Vaccination::class);
    }


    public function available_vaccines(){
        return $this->belongsToMany(Vaccine::class, 'spot_vaccines');
    }

}

