<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\Inventory\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        return Inertia::render('Cart/Index');
    }

    
    public function data(Request $request)
    {
        $user = auth()->guard('web')->user();
        $data = array();
        if($user){
            $data = Cart::where('user_id', $user->id)
            ->with(['product'])
            ->orderBy('id', 'DESC')
            ->get();
        }

        return response()->json($data, 200);
    }

    public function shipment(Request $request){
        

        return Inertia::render('Frontend/Cart/shipment');
    }


        /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try{
            $cart = Cart::firstOrNew([
                'user_id' => auth()->guard('web')->user()->id, 
                'product_id' => $request->product_id, 
            ]);
            $cart->qty = $cart->qty + $request->qty;
            $cart->harga = $request->price;
            $cart->save();

            $product = Product::where('id', $request->product_id)->first();
        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('product.show', $product->slug);
    }

       /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        $rules = [
            'id' => 'required',
        ];

        $pesan = [
            'id.required' => 'Nama Brand Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $cart = Cart::find($request->id);
                $cart->qty = $request->qty;
                $cart->save();

                $data = Cart::where('user_id', auth()->guard('web')->user()->id)
                ->with(['product', 'variant'])
                ->orderBy('created_at', 'DESC')->get();

                $coba = Inertia::share(['cart' => $data]);

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->back();
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
            $hapus_db = Cart::destroy($id);
        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('cart.index');
    }

    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroySelected()
    {
        DB::beginTransaction();
        try{
            $hapus_db = Cart::whereIn('id', $request->ids)->delete();
        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('cart.index');
    }

}
