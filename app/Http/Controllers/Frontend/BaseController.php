<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Dipantry\Rajaongkir\Models\ROProvince;
use Dipantry\Rajaongkir\Models\ROCity;
use Dipantry\Rajaongkir\Models\ROSubDistrict;
use Dipantry\Rajaongkir\Models\ROCountry;
use App\Models\Dapil;


use App\Models\DukunganLevel;

class BaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return response()->json([
            'app_version' => '1.0.1',
        ]);
    }

    /**
     * Get Wilayah
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function wilayah(Request $request)
    {
        $type = $request->type;
        $search = $request->search;
        $parent_id = $request->parent;
        $filter = !empty($request->filter) ? explode(',', $request->filter) : [];

        $id = $request->id;
        if($type == 'provinsi'){
            $data = ROProvince::orderBy('name', 'ASC')->get();
        }elseif($type == 'kota'){
            $data = ROCity::orderBy('name', 'ASC')
            ->when($parent_id, function($query, $parent_id){
                $query->where('province_id', $parent_id);
            })
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })->get();
        }elseif($type == 'kecamatan'){
            $data = ROSubDistrict::
            when($parent_id, function($query, $parent_id){
                $query->where('kota_id', $parent_id);
            })
            ->when(count($filter), function($query, $id) use ($filter){
                $query->whereIn('id', $filter);
            })
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })->orderBy('name', 'ASC')->get();
        }else{
            $data = ROCountry::
            when($parent_id, function($query, $parent_id){
                $query->where('kecamatan_id', $parent_id);
            })
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->when(count($filter), function($query, $id) use ($filter){
                $query->whereIn('id', $filter);
            })
            ->orderBy('nama', 'ASC')->get();
        }

        return response()->json($data);
    }

    /**
     * Get Dukungan Type
     *
     * @return \Illuminate\Http\Response
     */
    public function dukunganLevel(Request $request)
    {
        $id = $request->id;
        $data = DukunganLevel::orderBy('id', 'ASC')->get();

        return response()->json($data);
    }

    
    /**
     * Get Dukungan Type
     *
     * @return \Illuminate\Http\Response
     */
    public function dapil(Request $request)
    {
        $daerah_id = $request->daerah;
        $level = $request->level;
        
        $data = Dapil::where('daerah_id', $daerah_id)->where('level', $level)->get();

        return response()->json($data);
    }



    
}
