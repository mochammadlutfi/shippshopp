<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'reg_kotakab';
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id', 'provinsi_id');
    }
    
}
