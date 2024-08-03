<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Carbon\Carbon;
use App\Models\Dukungan;
class StatistikRepository implements StatistikInterface
{
    protected $model;

    public function __construct()
    {

    }

    public function pendukung($caleg_id){
        $data = DB::table("dukungan as dn")
        ->select(DB::Raw('COUNT(*) as total'), DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"))
        ->where("dn.caleg_id", "=", $caleg_id)
        ->first();

        return $data;
    }

    
    public function relawan($caleg_id){
        $data = DB::table("dukungan as dn")
        ->select(DB::Raw('COUNT(*) as total'), DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"))
        ->where('dn.type', '=', 'Relawan')
        ->where("dn.caleg_id", "=", $caleg_id)
        ->first();

        return $data;
    }

    public function age($gender, $caleg_id, $type = NULL)
    {
        
        $ranges = [20,30,40,50,60,61];

        $data = Dukungan::where('caleg_id', $caleg_id)
        ->where('jk', '=', $gender)
        ->when(!empty($type), function($q) use ($type){
            return $q->where('type', $type);
        })
        ->get()
        ->map(function ($user) use ($ranges) {
            $age = Carbon::parse($user->tgl_lahir)->age;
            foreach($ranges as $key => $breakpoint)
            {
                if ($breakpoint >= $age)
                {
                    $user->range = $key;
                    break;
                }
            }
    
            return $user;
        })
        ->mapToGroups(function ($user, $key) {
            $val = 0;
            if($user){
                $val = $user;
            }
            return [$user->range => $val];
        })
        ->map(function ($group) {
            // dd($val);
            return count($group);
        })
        ->sortKeys()->toArray();
        
        $result = array();
        $i = 0;
        foreach($ranges as $r){
            if(array_key_exists($i, $data)){
                $result[] = $data[$i];
            }else{
                $result[] = 0;
            }
            $i++;
        }
    
        return $result;
    }

    public function wilayah($level, $caleg_id, $type = NULL){
        if($level == 0){
            $data = Dukungan::join("reg_kotakab as kota", function($join){
                $join->on("kota.id", "=", "dukungan.kota_id");
            })
            ->select("kota.nama as nama_daerah", DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"), DB::Raw('COUNT(*) as total'),
            DB::raw('(SELECT COUNT(*) FROM reg_kecamatan WHERE kota_id=kota.id) as sub_wilayah'))
            ->groupBy("kota.nama")
            ->when(!empty($type), function($query) use ($type){
                $query->where('dukungan.type', $type);
            })
            ->where("dukungan.caleg_id", "=", $caleg_id)
            ->get();

        }else if($level == 1){
            $data = Dukungan::join("reg_kecamatan as kec", function($join){
                $join->on("kec.id", "=", "dukungan.kecamatan_id");
            })
            ->select("kec.nama as nama_daerah", DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"), DB::Raw('COUNT(*) as total'), DB::raw('(SELECT COUNT(*) FROM reg_kelurahan WHERE kecamatan_id=kec.id) as sub_wilayah'))
            ->groupBy("kec.nama")
            ->when(!empty($type), function($query) use ($type){
                $query->where('dukungan.type', $type);
            })
            ->where("dukungan.caleg_id", "=", $caleg_id)
            ->get();

        }else{
            $data = Dukungan::join("reg_kelurahan as kel", function($join){
                $join->on("kel.id", "=", "dukungan.kelurahan_id");
            })
            ->select("kel.nama as nama_daerah", 
                DB::Raw("SUM( jk = 'l' ) as l"),
                DB::Raw("SUM( jk = 'p' ) as p"),
                DB::Raw('COUNT(*) as total')
            )
            ->groupBy("kel.nama")
            ->when(!empty($type), function($query) use ($type){
                $query->where('dukungan.type', $type);
            })
            ->where("dukungan.caleg_id", "=", $caleg_id)
            ->get();
        }

        return $data;
    }

    public function dapil($caleg_id){
        $data = DB::table("caleg as c")
        ->join("dapil as d", function($join){
            $join->on("d.id", "=", "c.dapil_id");
        })
        ->select("c.id", "d.kursi", "c.target", "d.dpt", "d.tps", DB::Raw('(SELECT COUNT(*) FROM dukungan WHERE caleg_id=c.id) as dukungan'))
        ->where("c.id", "=", $caleg_id)
        ->first();

        return $data;
    }
    
}