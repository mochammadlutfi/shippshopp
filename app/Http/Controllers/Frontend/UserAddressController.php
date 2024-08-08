<?php

namespace App\Http\Controllers\Frontend;



use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\UserAddress;
class UserAddressController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return Inertia::render('UserAddress/Index');
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        return Inertia::render('UserAddress/Form');
    }

    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'name' => 'required',
            'reciever' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $pesan = [
            'name.required' => 'Label Alamat Wajib Diisi!',
            'reciever.required' => 'Nama Penerima Wajib Diisi!',
            'phone.required' => 'No Handphone Penerima Wajib Diisi!',
            'address.required' => 'Alamat Lengkap Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
            
        }else{
            DB::beginTransaction();
            try{

                $user_id = auth()->guard('web')->user()->id;
                if($request->is_main == 1){
                   UserAddress::where('user_id', $user_id)->update(['is_main' => 0]);
                }

                $data = new UserAddress();
                $data->user_id = auth()->guard('web')->user()->id;
                $data->name = $request->name;
                $data->reciever = $request->reciever;
                $data->phone = $request->phone;
                $data->address = $request->address;
                $data->is_main = $request->is_main;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.address.index');
        }
    }

    public function edit($id, Request $request)
    {
        $data = UserAddress::find($request->id);

        return Inertia::render('UserAddress/Form',[
            'data' => $data,
        ]);
    }

        /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'reciever' => 'required',
            'phone' => 'required',
            'reciever' => 'required',
            'address' => 'required',
        ];

        $pesan = [
            'name.required' => 'Label Alamat Wajib Diisi!',
            'reciever.required' => 'Nama Penerima Wajib Diisi!',
            'phone.required' => 'No Handphone Penerima Wajib Diisi!',
            'address.required' => 'Alamat Lengkap Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = UserAddress::find($request->id);
                    $data->user_id = auth()->guard('web')->user()->id;
                    $data->name = $request->name;
                    $data->reciever = $request->reciever;
                    $data->phone = $request->phone;
                    $data->address = $request->address;
                    $data->is_main = $request->is_main;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.address.index');
        }
    }
    
    public function data(Request $request)
    {
        // $id = $request->id;
        $main = $request->main;
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $user_id = auth()->guard('web')->user()->id;
        $query = UserAddress::where('user_id', $user_id)
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('address', 'LIKE', '%' . $search . '%')
            ->orWhere('reciever', 'LIKE', '%' . $search . '%')
            ->orWhere('phone', 'LIKE', '%' . $search . '%')
            ->orWhere('area', 'LIKE', '%' . $search . '%');
        })->orderBy('is_main', 'DESC');

        if($page){
            $data = $query->paginate($limit);
        }else if($main == 1){
            $data = $query->where('is_main', '=', 1)->first();
        }else{
            $data = $query->get();
        }
        return response()->json($data);
    }

    public function main(Request $request)
    {
        $id = $request->id;
        DB::beginTransaction();
        try{
            $user_id = auth()->guard('web')->user()->id;
            $default = UserAddress::where('user_id', $user_id)->update(['is_primary' => 0]);
    
            $primary = UserAddress::where('user_id', $user_id)->where('id', $id)->update(['is_primary' => 1]);

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('user.address.index');
    }

        /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $hapus_db = UserAddress::destroy($id);
        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('user.address.index');
    }

    public function geocode(Request $request)
    {
        $response = \GoogleMaps::load('geocoding')
        ->setParamByKey('latlng', $request->latlng)
        ->setParamByKey('language', 'id')
        // ->setParamByKey('location_type', 'ROOFTOP')
        // ->setParamByKey('result_type', 'street_address')
        ->get();
        $data = json_decode($response, true);
        // dd($data);

        
        $matrix = \GoogleMaps::load('distancematrix')
        ->setParamByKey('destinations', $request->latlng)
        ->setParamByKey('origins', "1.3745500859605386, 100.34362375967166")
        ->setParamByKey('language', 'id')
        ->setParamByKey('result_type', 'street_address')
        ->get();
        $d = json_decode($matrix, true);
        // dd($data);
        $jarak = 0;
        $address = '';
        if($d['status'] == 'OK'){
            $jarak = $d['rows'][0]['elements'][0]['distance']['value'];
        }

        if($data['status'] == 'OK'){
            $address = $data['results'][0]['formatted_address'];
        }
        
        return response()->json([
            'jarak' => $jarak,
            'data' => $address,
        ], 200);
    }

    
    public function jsonStore(Request $request)
    {
        // dd($request->all());
        $rules = [
            'name' => 'required',
            'reciever' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $pesan = [
            'name.required' => 'Label Alamat Wajib Diisi!',
            'reciever.required' => 'Nama Penerima Wajib Diisi!',
            'phone.required' => 'No Handphone Penerima Wajib Diisi!',
            'address.required' => 'Alamat Lengkap Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'result' => $this->errorBag($validator->errors()),
            ], 401);
            
        }else{
            DB::beginTransaction();
            try{

                $user_id = auth()->guard('web')->user()->id;
                if($request->is_main == 1){
                   UserAddress::where('user_id', $user_id)->update(['is_main' => 0]);
                }

                $data = new UserAddress();
                $data->user_id = auth()->guard('web')->user()->id;
                $data->name = $request->name;
                $data->reciever = $request->reciever;
                $data->phone = $request->phone;
                $data->address = $request->address;
                $data->is_main = $request->is_main;
                $data->lat = $request->lat;
                $data->lng = $request->lng;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'result' => $e
                ], 401);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'result' => $data
            ], 200);
        }
    }
    
    private function errorbag($data){

        return (object) collect($data)->map(function ($errors) {
            return $errors[0];
        })->toArray();
    }

}
