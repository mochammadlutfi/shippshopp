<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Storage;
use Carbon\Carbon;
use Image;

use App\Models\Dukungan;
use App\Models\User;
use App\Models\Caleg;



class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    
    public function index()
    {
        $data = Collect([
            [
                'title' => 'Profil Biodata',
                'sub_title' => 'Pengaturan informasi data diri',
                'icon_type' => 'image',
                'icon_src' => 'https://blue.kumparan.com/uikit-assets/assets/icons/profile-a511c2f486f99dfa2abdf99e78a848a1.svg',
                'route' => route('user.settings.profile'),
            ],
        ]);

        if(auth()->user()->hasRole('Caleg')){
            $data->push([
                'title' => 'Pencalonan',
                'sub_title' => 'Pengaturan informasi pencalonan',
                'icon_type' => 'image',
                'icon_src' => 'https://blue.kumparan.com/uikit-assets/assets/icons/profile-a511c2f486f99dfa2abdf99e78a848a1.svg',
                'route' => route('user.settings.caleg'),
            ]);
        }

        
        $data->push(
            [
                'title' => 'Alamat Email',
                'sub_title' => auth()->guard('web')->user()->email,
                'icon_type' => 'image',
                'icon_src' => 'https://blue.kumparan.com/uikit-assets/assets/icons/mail-5c0af36ee287b48288238ba4cbbe7da4.svg',
                'route' => route('user.settings.email'),
            ],
            [
                'title' => 'No Handphone',
                'sub_title' => 'Belum Ditambahkan',
                'icon_type' => 'image',
                'icon_src' => 'https://blue.kumparan.com/uikit-assets/assets/icons/smartphone-black-56938dcd68fd5ff772e52d4ebf7f6161.svg',
                'route' => route('user.settings.phone'),
            ],
            [
                'title' => 'Password',
                'sub_title' => '*****',
                'icon_type' => 'image',
                'icon_src' => 'https://blue.kumparan.com/uikit-assets/assets/icons/lock-73423076303023c063ea367b5823ca3e.svg',
                'route' => route('user.settings.password'),
            ],
        );

        return Inertia::render('Settings/Index',[
            'dataList' => $data->all(),
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = auth()->guard('web')->user();

        $data = Dukungan::where('id', $user->people_id)->first();

        return Inertia::render('Settings/Profile',[
            'value' => $data,
        ]);
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateProfil(Request $request)
    {
        $rules = [
            'nik' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'tmp_lahir' => 'required',
            'tgl_lahir' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
        ];

        $pesan = [
            'nik.required' => 'NIK Wajib Diisi!',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'jk.required' => 'Jenis Kelamin Wajib Diisi!',
            'tmp_lahir.required' => 'Tempat Lahir Wajib Diisi!',
            'tgl_lahir.required' => 'Tanggal Lahir Wajib Diisi!',
            'alamat.required' => 'Alamat Lengkap Wajib Diisi!',
            'rt.required' => 'RT Wajib Diisi!',
            'rw.required' => 'RW Wajib Diisi!',
            'kecamatan_id.required' => 'Kecamatan Wajib Diisi!',
            'kelurahan_id.required' => 'Kelurahan Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $user = auth()->guard('web')->user();
                $user->name = $request->nama;

                $data = Dukungan::where('id', $user->people_id)->first();
                $data->nik = $request->nik;
                $data->nama = $request->nama;
                $data->jk = $request->jk;
                $data->tgl_lahir = Carbon::parse($request->tgl_lahir)->format('Y-m-d');
                $data->tmp_lahir = $request->tmp_lahir;
                $data->alamat = $request->alamat;
                $data->rt = $request->rt;
                $data->rw = $request->rw;
                $data->tps = $request->tps;
                $data->kota_id = $request->kota_id;
                $data->kecamatan_id = $request->kecamatan_id;
                $data->kelurahan_id = $request->kelurahan_id;

                
                if(!empty($request->file('image'))){
                    $img = $this->uploadImage($request->file('image'));
                    $data->image = $img;
                    $user->avatar = $img;
                }

                $user->save();
                $data->save();
            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.settings.index');
        }
    }


    public function email()
    {
        $data = User::select('name', 'email', 'phone', 'avatar')
        ->where('id', auth()->guard('web')->user()->id)->first();

        return Inertia::render('Settings/Email',[
            'data' => $data,
        ]);
    }

    public function validate(Request $request)
    {
        $rules = [
            'password' => 'required|min:6',
        ];

        $pesan = [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password kurang dari 6 karakter!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'success'=> false,
                'message' => $validator->errors()
            ]);
        }else{
            $user = User::where('email', auth()->guard('web')->user()->email)->first();

            if (!Hash::check($request->password, $user->password)) {
                $error['password'] = array('Password salah!');
                return response()->json([
                    'success'=> false,
                    'message' => $error
                ]);
             }
            return response()->json([
                'success' => true, 
                'message'=>'success'
            ]);
        }
    }

    
    public function emailUpdate(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users,email|email',
        ];

        $pesan = [
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'email.email' => 'Format alamat email salah.',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                    $data = User::where('email', auth()->guard('web')->user()->email)->first();
                    $data->email = $request->email;
                    $data->email_verified_at = null;
                    $data->save();
                    
                    $data->sendEmailVerificationNotification();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.settings.index');
        }
    }
    
    public function phone()
    {
        $data = User::select('name', 'email', 'phone', 'avatar')
        ->where('id', auth()->guard('web')->user()->id)->first();

        return Inertia::render('Settings/Phone',[
            'data' => $data,
        ]);
    }


    public function password()
    {
        return Inertia::render('Settings/Password');
    }


    public function passwordUpdate(Request $request)
    {
        // dd($request->all());
        $rules = [
            'password_old' => 'required',
            'password_new' => 'required',
            'password_conf' => 'required',
        ];

        $pesan = [
            'password_old.required' => 'Password Lama Wajib diisi.',
            'password_new.required' => 'Password Baru Wajib Diisi.',
            'password_conf.required' => 'Konfirmasi Password Baru Wajib Diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                if (Hash::check($request->password_old, auth()->guard('web')->user()->password)) {
                    $data = User::find(auth()->guard('web')->user()->id);
                    $data->password = Hash::make($request->password_new);
                    $data->save();
                }else{
                    return back()->withErrors([
                        'password_old' => 'Password Lama Salah!'
                    ]);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.dashboard');
        }
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function caleg()
    {
        $user = auth()->guard('web')->user();

        $data = Caleg::where('id', $user->caleg_id)->first();
        

        return Inertia::render('Settings/Pencalonan',[
            'value' => $data,
        ]);
    }

    
    public function calegUpdate(Request $request)
    {
        $rules = [
            'level' => 'required',
            'provinsi_id' => 'required',
            'kota_id' => 'required',
            'dapil_id' => 'required',
            'target' => 'required',
            'nomor' => 'required',
        ];

        $pesan = [
            'level.required' => 'Tingkat Legislatif Wajib Diisi!.',
            'provinsi_id.required' => 'Provinsi Wajib Diisi!.',
            'kota_id.required' => 'Kota Wajib Diisi!.',
            'dapil_id.required' => 'Daerah Pemilihan Wajib Diisi!.',
            'target.required' => 'Target Dukungan Wajib Diisi!.',
            'nomor.required' => 'Nomor Pencalonan Wajib Diisi!.',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $user = auth()->guard('web')->user();

                    $data = Caleg::where('id', $user->caleg_id)->first();
                    $data->level = $request->level;
                    $data->provinsi_id = $request->provinsi_id;
                    $data->daerah_id = $request->kota_id;
                    $data->dapil_id = $request->dapil_id;
                    $data->target = $request->target;
                    $data->nomor = $request->nomor;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
                return back();
            }
            DB::commit();
            return redirect()->route('user.settings.index');
        }
    }
    
    private function uploadImage($file, $type = 'avatar'){

        $caleg_id = auth()->user()->caleg_id;
        $file_name = $caleg_id. '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = Image::make($file->getRealPath());
        if($type == 'avatar'){
            $destinationPath = storage_path('app/public/'.$caleg_id.'/people');
        }else{
            $destinationPath = storage_path('app/public/'.$caleg_id.'/ktp');
        }

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        if($type == 'avatar'){
            $width = 800;
            $heigth = 800;
        }else{
            $width = 1200;
            $heigth = 337;
        }

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return '/uploads/'.$caleg_id.'/people/'.$file_name;
    }

}
