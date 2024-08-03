<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


use App\Models\Wilayah\Kelurahan;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kota;
use App\Models\Wilayah\Provinsi;
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
        // dd($filter);
        // dd()

        $id = $request->id;
        if($type == 'provinsi'){
            $data = Provinsi::orderBy('nama', 'ASC')->get();
        }elseif($type == 'kota'){
            $data = Kota::orderBy('nama', 'ASC')
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->wherein('id', ['32.73', '32.77'])->get();
        }elseif($type == 'kecamatan'){
            $data = kecamatan::
            when($parent_id, function($query, $parent_id){
                $query->where('kota_id', $parent_id);
            })
            ->when(count($filter), function($query, $id) use ($filter){
                $query->whereIn('id', $filter);
            })
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })->orderBy('nama', 'ASC')->get();
        }else{
            $data = kelurahan::
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
