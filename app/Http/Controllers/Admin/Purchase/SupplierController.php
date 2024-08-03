<?php

namespace App\Http\Controllers\Admin\Purchase;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\Purchase\Supplier;
use App\Models\Purchase\SupplierProduct;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
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
        return Inertia::render('Purchase/Supplier/Index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Purchase/Supplier/Form');
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
                    $data = new Supplier();
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->phone = $request->phone;
                    $data->pic = $request->pic;
                    $data->address = $request->address;
                    $data->save();

                    
                foreach($request->lines as $i){
                    $line = new SupplierProduct();
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $data->product()->save($line);
                }

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
                // return back();
            }
            DB::commit();
            return redirect()->route('admin.purchase.supplier.index');
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
        $data = Supplier::where('id', $id)->first();

        $data->lines = SupplierProduct::with(['product', 'variant'])
        ->where('supplier_id', $data->id)->get();

        return Inertia::render('Purchase/Supplier/Form',[
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
        // dd($request->all());
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
                    $data = Supplier::where('id', $id)->first();
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->phone = $request->phone;
                    $data->pic = $request->pic;
                    $data->address = $request->address;
                    $data->save();

                    
                foreach($request->lines as $i){
                    if(array_key_exists("id", $i)){
                        $line_id = $i['id'];
                    }else{
                        $line_id = null;
                    }

                    $line = SupplierProduct::firstOrNew(['id' =>  $line_id]);
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->supplier_id = $data->id;
                    $data->product()->save($line);
                }
                $remove = SupplierProduct::where('supplier_id', $data->id)->whereIn('id', $request->lines_deleted)->delete();


            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
            return redirect()->route('admin.purchase.supplier.index');
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
                $data = Supplier::where('id', $id)->first();
                $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
            // return back();
        }
        DB::commit();
        return redirect()->route('admin.purchase.supplier.index');
    }

    public function product(Request $request)
    {

        $data = Supplier::where('id', $request->id)->first();
        $data->lines = SupplierProduct::with(['product', 'variant' => function($q){
            return $q->with('purchase_unit')->where('stock', '<', 20);
        }])
        ->where('supplier_id', $data->id)->get();
        
        return response()->json($data);
    }

    public function data(Request $request)
    {
        $keyword = $request->q;
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;


        $elq = Supplier::when($request->q, function($query, $search){
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
