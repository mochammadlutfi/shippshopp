<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $table = 'testimoni';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama', 'phone', 'email', 'description'
    ];

    
}
