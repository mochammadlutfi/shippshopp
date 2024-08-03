<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Inertia\Inertia;

use App\Models\User;
use App\Models\Inventory\Product;
use App\Models\Sale\SaleOrder;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = Collect([
            'customer' => User::latest()->get()->count(),
            'product' => Product::latest()->get()->count(),
            'sales' => SaleOrder::where('state', 'done')->get()->count(),
            'order' => SaleOrder::where('state', 'pending')->get()->count(),
        ]);

        return Inertia::render('Dashboard/Index',[
            'stats' => $stats
        ]);
    }

    
    public function aktivasi()
    {
        if(auth()->guard('web')->user()->caleg_id){

            return redirect()->route('user.dashboard');
        }else{
            return Inertia::render('Aktivasi');
        }
    }

    public function post_aktivasi(Request $request)
    {
        $validator = $this->validator_aktivasi($request->all());
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $caleg = new Caleg();
                    $caleg->user_id = auth()->guard('web')->user()->id;
                    $caleg->dapil_id = $request->dapil_id;
                    $caleg->level = $request->level;
                    $caleg->daerah_id = $request->daerah_id;
                    $caleg->target = $request->target;
                    $caleg->save();

                    $data = new Dukungan();
                    $data->nik = $request->nik;
                    $data->nama = $request->nama;
                    $data->jk = $request->jk;
                    $data->tgl_lahir = Carbon::parse($request->tgl_lahir)->format('Y-m-d');
                    $data->tmp_lahir = $request->tmp_lahir;
                    $data->alamat = $request->alamat;
                    $data->rt = $request->rt;
                    $data->rw = $request->rw;
                    $data->kota_id = $request->daerah_id;
                    $data->kecamatan_id = $request->kecamatan_id;
                    $data->kelurahan_id = $request->kelurahan_id;
                    $data->caleg_id = $caleg->id;
                    $data->save();

                    $user = auth()->guard('web')->user();
                    $user->caleg_id = $caleg->id;
                    $user->people_id = $data->id;
                    $user->save();

            }catch(\QueryException $e){
                DB::rollback();
                return redirect()->back();
            }
            DB::commit();
            return redirect()->route('user.dashboard');
        }
    }

    private function validator_aktivasi($request){

        $rules = [
            'nik' => 'required',
            'nama' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'jk' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'daerah_id' => 'required',
        ];

        $messages = [
            'nik.required' => 'NIK Wajib Diisi!',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'tmp_lahir.required' => 'Tempat Lahir Wajib Diisi!',
            'tgl_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
            'jk.required' => 'Jenis Kelamin Wajib Diisi!',
            'alamat.required' => 'Nama Lengkap Wajib Diisi!',
            'rt.required' => 'RT Wajib Diisi!',
            'rw.required' => 'RW Wajib Diisi!',
            'daerah_id.required' => 'RW Wajib Diisi!',
        ];

        return Validator::make($request, $rules, $messages);

    }

    private function relawan(){
        $relawan_id = auth()->user()->id;
        $caleg_id = auth()->guard('web')->user()->caleg_id;

        $overview = collect([
           'pendukung' => Dukungan::where('caleg_id', $caleg_id)->where('user_id', $relawan_id)->count(),
           'dpt' => 100000, 
        ]);

        return Inertia::render('DashboardRelawan',[
            'overview' => $overview,
        ]);
    }

    public function ageRange(){
        $caleg_id = auth()->guard('web')->user()->caleg_id;

        $ranges = [
            '-20' => 20,
            '21-30' => 30,
            '31-40' => 40,
            '41-50' => 50,
            '51-60' => 60,
            '60+' => 61
        ]; 
        
        $ranges = [20,30,40,50,60,61];
        
        $lk = Dukungan::where('caleg_id', $caleg_id)
            ->where('jk', 'L')
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
                // $val = 0;
                $val = ($user) ? $user : 0;
                return [$user->range => $val];
            })
            ->map(function ($group) {
                return count($group);
            })
            ->sortKeys()->toArray();
        
        $pr = Dukungan::where('caleg_id', $caleg_id)
            ->where('jk', 'P')
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
                // $val = 0;
                $val = ($user) ? $user : 0;
                return [$user->range => $val];
            })
            ->map(function ($group) {
                return count($group);
            })
            ->sortKeys()->toArray();

        // dd($result);
        return response()->json([
            'lk' => $this->ageGroup($lk),
            'pr' => $this->ageGroup($pr),
        ]);
    }

    private function ageGroup($data){
        $result = [];
        for($i=0; $i <= 5; $i++){
            if(array_key_exists($i, $data)){
                array_push($result, $data[$i]);
            }else{
                array_push($result, 0);
            }
        }

        return $result;
    }
}
