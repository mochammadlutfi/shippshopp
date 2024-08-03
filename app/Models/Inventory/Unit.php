<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'image', 'state', 'link',
    ];

}
