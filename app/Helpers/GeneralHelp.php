<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Models\Caleg;
class GeneralHelp
{
    public static function currency($value){
        return number_format($value,0,',','.');
    }

    
    public static function setCalegLevel($v)
    {
        if($v == 'DPR RI'){
           return 1;
        }else if($v == 'DPRD Provinsi'){
            return 2;
        }else if($v == 'DRPD Kota/Kabupaten'){
            return 3;
        }else{
            return 4;
        }
    }

    
    public static function getTypeDukungan($v)
    {
        if($v > 2){
           return 'Relawan';
        }else{
            return 'Pendukung';
        }
    }

    public static function getCaleg(){

        $data = Caleg::select('caleg.*', 'dapil.nama', 'dapil.level', 'dapil.wilayah_type', 'dapil.daerah_id', 'dapil.wilayah_id', 'dapil.dpt', 'dapil.tps')
        ->where('caleg.id', auth()->guard('web')->user()->caleg_id)
        ->join('dapil', 'dapil.id','=', 'caleg.dapil_id')->first();

        return $data;
    }
}