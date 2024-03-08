<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;

    protected $table = 'societies';
    public $timestamps = false;

    protected $fillable = [
        'id_card_number', 'password', 'name', 'born_date', 'gender', 'address', 'regional_id', 'login_tokens'
    ];

    protected $hidden = [
        "login_tokens"
    ];

    public function regional(){
        return $this->belongsTo(Regional::class);
    }
}
