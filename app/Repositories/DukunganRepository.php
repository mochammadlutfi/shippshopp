<?php

namespace App\Repositories;

use App\Models\Dukungan;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use DB;
use Carbon\Carbon;
class DukunganRepository implements DukunganInterface
{
    protected $model;

    public function __construct(Dukungan $model)
    {
        $this->model = $model;
        Carbon::setLocale('id');
    }


    public function all($req, $saksi_id = null, $paging)
    {
        $sort = !empty($req['sort']) ? $req['sort'] : 'id';
        $sortDir = !empty($req['sortDir']) ? $req['sortDir'] : 'desc';
        $limit = !empty($req['limit']) ? $req['limit'] : 25;
        $search = !empty($req['search']) ? $req['search'] : '';

        $kota_id = !empty($req['kota']) ? $req['kota'] : '';
        $kecamatan_id = !empty($req['kec']) ? $req['kec'] : '';
        $kelurahan_id = !empty($req['kel']) ? $req['kel'] : '';

        $query = Dukungan::with(['kota:id,nama', 'kecamatan:id,nama', 'kelurahan:id,nama'])
        ->when(!empty($kota_id), function($q) use ($kota_id) {
            $q->where('kota_id', $kota_id);
        })
        ->when(!empty($kecamatan_id), function($q) use ($kecamatan_id) {
            $q->where('kecamatan_id', $kecamatan_id);
        })
        ->when(!empty($kelurahan_id), function($q) use ($kelurahan_id) {
            $q->where('kelurahan_id', $kelurahan_id);
        })
        ->when(!empty($saksi_id), function($q) use ($saksi_id) {
            $q->where('user_id', '=', $saksi_id);
        })
        ->orderBy($sort, $sortDir)->get();

        $data = $query->map(function ($d) use($saksi_id) {
            if($d->image){
                $img = $d->image;
            }else{
                $img = 'media/placeholder/avatar.jpg';
            }
            $d->image = $img;
            $d->jk = ($d->jk == 'L') ? 'Laki-Laki' : 'Perempuan';

            return $d;
        });


        if($paging){
            $data = $data->paginate($limit);
        }else{
            $data = $data->all();
        }

        return $data;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id, $type = 'Pendukung', $uid = NULL)
    {
        $data = Dukungan::select("dukungan.*", "c.nama as kota", "d.nama as kec", "e.nama as kel")
        ->join("reg_kotakab as c", function($join){
            $join->on("c.id", "=", "dukungan.kota_id");
        })
        ->join("reg_kecamatan as d", function($join){
            $join->on("d.id", "=", "dukungan.kecamatan_id");
        })
        ->join("reg_kelurahan as e", function($join){
            $join->on("e.id", "=", "dukungan.kelurahan_id");
        })
        ->where('dukungan.id', $id)
        ->first();
        if (null == $data) {
            throw new ModelNotFoundException("User not found");
        }

        $data->jk = $data->jk == 'L' ? 'Laki-Laki' : 'Perempuan';
        $data->image = asset(($data->image) ? $data->image : 'media/placeholder/avatar.jpg');
        $data->ttl = $data->tmp_lahir.'/'.Carbon::createFromFormat('Y-m-d', $data->tgl_lahir)->translatedFormat('l, d F Y');
        $data->umur = Carbon::parse($data->tgl_lahir)->age;

        if($data->type == 'Relawan'){
            $data->account_id = User::where('people_id', $data->id)->where('caleg_id', $data->caleg_id)->first()->id;
            $data->rekrutan = DB::table("dukungan as dn")
            ->select(DB::Raw('COUNT(*) as total'), DB::Raw("COALESCE(SUM( jk = 'l' ),0) as l"), DB::Raw("COALESCE(SUM( jk = 'p' ),0) as p"))
            ->where("caleg_id", "=", $data->caleg_id)
            ->where("user_ref_id", "=", $data->user_id)
            ->first();
        }

        return $data;
    }

    
    public function edit($id)
    {
        $data = DB::table("caleg_pendukung as a")
        ->join("res_people as b", function($join){
            $join->on("a.people_id", "=", "b.id");
        })
        ->select("a.id", "a.type_id", "b.nama", "b.nik", "b.jk", "b.tmp_lahir", "b.tgl_lahir", "b.alamat", "b.rt", "b.rw", "b.kota_id", "b.kecamatan_id", "b.kelurahan_id", "b.image", "b.ktp", "b.email", "b.phone")
        ->where('a.id', $id)
        ->first();
        if (null == $data) {
            throw new ModelNotFoundException("User not found");
        }

        $data->image = asset(($data->image) ? $data->image : 'media/placeholder/avatar.jpg');
        
        $data->umur = Carbon::parse($data->tgl_lahir)->age;

        return $data;
    }

    
    public function rekrutan($searchType, $search, $sort, $sortDir, $limit, $caleg_id, $uid = null)
    {
        // dd($uid);
        $query = Dukungan::select("dukungan.*", "c.nama as kota", "d.nama as kec", "e.nama as kel")
        ->join("reg_kotakab as c", function($join){
            $join->on("c.id", "=", "dukungan.kota_id");
        })
        ->join("reg_kecamatan as d", function($join){
            $join->on("d.id", "=", "dukungan.kecamatan_id");
        })
        ->join("reg_kelurahan as e", function($join){
            $join->on("e.id", "=", "dukungan.kelurahan_id");
        })
        ->when(!empty($uid), function($query) use ($uid){
            $query->where('dukungan.user_ref_id', $uid);
        })
        ->when($search, function($query, $s){
                $query->where('b.nama', 'LIKE', '%' . $s . '%')
                ->orWhere('b.nik', 'LIKE', '%' . $s . '%')
                ->orWhere('b.email', 'LIKE', '%' . $s . '%')
                ->orWhere('b.phone', 'LIKE', '%' . $s . '%')
                ->orWhere('b.alamat', 'LIKE', '%' . $s . '%');
        })
        ->where('dukungan.caleg_id', $caleg_id)
        ->orderBy($sort, $sortDir)->get();

        $data = $query->map(function ($d) use($caleg_id, $uid) {
            if($d->image){
                $img = asset($d->image);
            }else{
                $img = asset('media/placeholder/avatar.jpg');
            }
            $d->image = $img;
            return $d;
        })->paginate($limit);

        return $data;
    }

    public function wilayah($type, $relawan_id = null, $searchType, $search, $sort, $sortDir, $limit){
        if($type == 'kota'){
            $data = Dukungan::join("reg_kotakab as kota", function($join){
                $join->on("kota.id", "=", "dukungan.kota_id");
            })
            ->select("kota.nama as nama_daerah", DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"), DB::Raw('COUNT(*) as rekrutan'),
            DB::raw('(SELECT COUNT(*) FROM reg_kecamatan WHERE kota_id=kota.id) as sub_wilayah'))
            ->groupBy("kota.nama")
            // ->where('dukungan.user_ref_id', $relawan_id)
            ->when(!empty($relawan_id), function($q) use ($relawan_id) {
                $q->where('dukungan.user_ref_id', '=',$relawan_id);
            })
            ->get();
        }else if($type == 'kecamatan'){
            $data = Dukungan::join("reg_kecamatan as kec", function($join){
                $join->on("kec.id", "=", "dukungan.kecamatan_id");
            })
            ->select("kec.nama as nama_daerah", DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"), DB::Raw('COUNT(*) as rekrutan'), DB::raw('(SELECT COUNT(*) FROM reg_kelurahan WHERE kecamatan_id=kec.id) as sub_wilayah'))
            ->groupBy("kec.nama")
            ->when(!empty($relawan_id), function($q) use ($relawan_id) {
                $q->where('dukungan.user_ref_id', '=',$relawan_id);
            })
            ->get();
        }else{
            $data = Dukungan::join("reg_kelurahan as kel", function($join){
                $join->on("kel.id", "=", "dukungan.kelurahan_id");
            })
            ->select("kel.nama as nama_daerah", DB::Raw("SUM( jk = 'l' ) as l"), DB::Raw("SUM( jk = 'p' ) as p"), DB::Raw('COUNT(*) as rekrutan'))
            ->groupBy("kel.nama")
            ->where('dukungan.user_ref_id', $relawan_id)
            ->get();
        }

        return $data;
    }
}