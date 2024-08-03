<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'reg_provinsi';
    
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';


    public function kota()
    {
        return $this->hasMany(Kota::class, 'provinsi_id');
    }

}
