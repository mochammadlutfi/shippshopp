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

use App\Models\User;
use App\Models\UserAddress;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    
    public function index()
    {
        $user = auth()->guard('web')->user();

        $data = User::select('name', 'email', 'phone')
        ->where('id', $user->id)->first();

        return Inertia::render('User/Index',[
            'data' => $data
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password()
    {

        return Inertia::render('User/Password');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateProfil(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $pesan = [
            'name.required' => 'Full Name must be filled.',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = User::where('id', auth()->guard('web')->user()->id)->first();
                    $data->name = $request->name;
                    if($data->avatar != $request->avatar){
                        if(Storage::disk('public')->exists($data->avatar))
                        {
                            Storage::disk('public')->delete($data->avatar);
                        }
                        if($request->hasFile('avatar')){
                            $data->avatar = $this->uploadFiles($request->file('avatar'), Str::slug($request->username, '-'));
                        }else{
                            $data->avatar = null;
                        }
                    }
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('user.profil');
        }
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'password_old' => 'required',
            'password_new' => 'required',
            'password_confirmation' => 'required',
        ];

        $pesan = [
            'password_old.required' => 'Password Lama wajib diisi',
            'password_new.required' => 'Password Baru wajib diisi',
            'password_confirmation.required' => 'Konfirmasi Password Baru wajib diisi',
            'password_old.min' => 'Password Lama kurang dari 6 karakter',
            'password_new.min' => 'Password Baru kurang dari 6 karakter',
            'password_confirmation.min' => 'Konfirmasi Password kurang dari 6 karakter',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            $user = User::where('id', auth()->guard('web')->user()->id)->first();

            if (!Hash::check($request->password_old, $user->password)) {
                $error['password_old'] = array('Password Lama salah!');
                return back()->withErrors($error);
            }else

            $user->password = Hash::make($request->password_new);
            $user->save();

            return redirect()->route('home');
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



}
