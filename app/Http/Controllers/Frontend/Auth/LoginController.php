<?php


namespace App\Http\Controllers\Frontend\Auth;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validate;
use Illuminate\Support\Facades\Validator;
use Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Redirect::setIntendedUrl(url()->previous());

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function login(Request $request)
     {
        $input = $request->all();
        // $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $rules = [
            'password' => 'required|string'
        ];

        $pesan = [
            'password.required' => 'Password Wajib Diisi!',
        ];

        // if($fieldType == 'email'){
            $rules['email'] = 'required|exists:users,email';
            $pesan['email.required'] = 'Alamat Email Wajib Diisi!';
            $pesan['email.exists'] = 'Alamat Email Belum Terdaftar!';
        // }else{
        //     $rules['email'] = 'required|exists:users,username|string';
        //     $pesan['email.required'] = 'Username Wajib Diisi!';
        //     $pesan['email.exists'] = 'Username Belum Terdaftar!';
        // }


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            if(auth()->guard('web')->attempt(array('email' => $input['email'], 'password' => $input['password']), true))
            {
                return redirect()->intended(RouteServiceProvider::HOME);
            }else{
                $gagal['password'] = array('Password salah!');
                return back()->withErrors($gagal);
            }
        }
     }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }

}
