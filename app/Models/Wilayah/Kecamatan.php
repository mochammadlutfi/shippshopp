<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'reg_kecamatan';
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';


    protected $fillable = [
        'name', 'district_id', 'alamat', 'postal_code'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
