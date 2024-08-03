<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


use App\Models\Inventory\Brand;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Inventory/Brand/Index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Merk Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $data = new Brand();
                $data->name = $request->name;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.inventory.brand.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        {
            $rules = [
                'name' => 'required',
            ];
    
            $pesan = [
                'name.required' => 'Nama Merk Wajib Diisi!',
            ];
    
            $validator = Validator::make($request->all(), $rules, $pesan);
            if ($validator->fails()){
                return back()->withErrors($validator->errors());
            }else{
                DB::beginTransaction();
                try{
                    $data = Brand::where('id', $id)->first();
                    $data->name = $request->name;
                    $data->save();
    
                }catch(\QueryException $e){
                    DB::rollback();
                    return back();
                }
                DB::commit();
                return redirect()->route('admin.inventory.brand.index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Brand::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.inventory.brand.index');
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $elq = Brand::withCount('product')->orderBy('id', 'DESC');
        if($page){
            $data = $elq->paginate($limit);
        }else{
            $data = $elq->get();
        }
        return response()->json($data);
    }
}
