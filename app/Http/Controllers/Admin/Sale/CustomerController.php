<?php

namespace App\Http\Controllers\Admin\Sale;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
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
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        return Inertia::render('Sale/Customer/Index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Sale/Customer/Form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Pelanggan Wajib Diisi!',
            'phone.required' => 'No Handphone Wajib Diisi!',
            'email.required' => 'Alamat Email Wajib Diisi!',
            'email.unique' => 'Alamat Email Sudah Digunakan!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = new User();
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->phone = $request->phone;
                    $data->password = Hash::make($request->password);
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
                // return back();
            }
            DB::commit();
            return redirect()->route('admin.sale.customer.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('base::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = User::where('id', $id)->first();

        return Inertia::render('Sale/Customer/Form',[
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Supplier Wajib Diisi!',
            'phone.required' => 'No Handphone Wajib Diisi!',
            'address.required' => 'Alamat Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = User::where('id', $id)->first();
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->phone = $request->phone;
                    $data->pic = $request->pic;
                    $data->address = $request->address;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
                // return back();
            }
            DB::commit();
            return redirect()->route('admin.sale.customer.index');
        }
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
                $data = User::where('id', $id)->first();
                $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
            // return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.customer.index');
    }

    public function data(Request $request)
    {
        $keyword = $request->q;
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;


        $elq = User::when($request->q, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('phone', 'LIKE', '%' . $search . '%')
            ->orWhere('email', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('created_at', 'DESC');
        if($page){
            $data = $elq->paginate($limit);
        }else{
            $data = $elq->get();
        }
        return response()->json($data);
    }
}
