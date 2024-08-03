<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'reciever', 'phone', 'address', 'postal_code', 'area_id', 'lat', 'lng', 'is_primary', 'user_id'
    ];

}
