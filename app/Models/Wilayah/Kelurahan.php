<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'reg_kelurahan';
    public $incrementing = false;
    protected $casts = ['id' => 'string'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';


    protected $fillable = [
        'nama', 'kota'
    ];

    protected $appends = [
        // 'daerah'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function getDaerahAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->kecamatan->kota->provinsi->nama)).', ';
        $daerah .= ucwords(strtolower($this->kecamatan->kota->nama)).', Kec. ';
        $daerah .= ucwords(strtolower($this->kecamatan->nama)).', ';
        $daerah .= ucwords(strtolower($this->nama)).', ';
        return  $daerah;
    }


}
