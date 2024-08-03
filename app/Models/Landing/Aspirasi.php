<?php

namespace App\Models\Landing;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $table = 'aspirasi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'nama', 'phone', 'email', 'description'
    ];

    
}
