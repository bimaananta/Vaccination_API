<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    protected $table = 'medicals';
    public $timestamps = false;

    protected $fillable = [
        'spot_id', 'user_id', 'role'
    ];

    protected $hidden = [
        "spot_id",
        "user_id"
    ];
}
