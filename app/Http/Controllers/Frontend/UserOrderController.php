<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Sale\SaleOrder;
use App\Models\Sale\SaleOrderLine;

use Illuminate\Support\Facades\DB;

use Storage;
class UserOrderController extends Controller
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
    public function index()
    {
        return Inertia::render('UserOrder/Index');
    }


    public function show($id, Request $request)
    {
        $user_id = auth()->guard('web')->user();

        $data = SaleOrder::with([
            'lines' => function($q){
                return $q->with(['product']);
            }, 'customer', 'shipping'
        ])
        ->where('id', $id)->first();

        // return redirect()->to('https://app.sandbox.midtrans.com/snap/v4/redirection/'.$data->payment_ref);

        return Inertia::render('UserOrder/Show',[
            'data' => $data,
        ]);
    }

    public function test(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
    //  dd($request->all());   
        $data = SaleOrder::with([
            'lines' => function($q){
                return $q->with(['product']);
            }, 'customer', 'shipping'
        ])
        ->where('id', $request->id)->first();

        // return $this->redirect($data->payment_ref);
        // return redirect()->route('user.order.payment', $data->id);
        return Inertia::location('https://app.sandbox.midtrans.com/snap/v4/redirection/'.$data->payment_ref);


        
        // return redirect()->to('https://app.sandbox.midtrans.com/snap/v4/redirection/'.$data->payment_ref);

    }

    public function redirect($p){
        return redirect()->away('https://app.sandbox.midtrans.com/snap/v4/redirection/'.$p);

    }
    public function payment(Request $request)
    {
       
        if($request->order_id){
            $data = SaleOrder::where('uid', $request->order_id)->first();
    
            if($request->transaction_status == 'expire'){
                $data->state = 'cancel';
                $data->save();
            }elseif($request->transaction_status == 'settlement'){
                $data->payment_status = 'paid';
                $data->save();
            }
    
            return redirect()->route('user.order.show', $data->id);
        }else{
            return redirect()->route('user.order.index');
        }
    }

    public function state($id, Request $request)
    {
        DB::beginTransaction();
        try{

            $user_id = auth()->guard('web')->user()->id;

            $data = SaleOrder::where('id', $id)->first();
            $data->payment_status = $request->status;
            $data->payment_method = 'Transfer';
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return response()->json($data);
    }

    public function confirm($id,Request $request)
    {
        DB::beginTransaction();
        try{

            $user_id = auth()->guard('web')->user()->id;
            $data = SaleOrder::where('id', $id)
            ->where('customer_id', $user_id)->first();
            $data->state = 'done';
            $data->save();
            
        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('user.order.show', $data->id);
    }

    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;
        $id = $request->id;

        $query = SaleOrder::with(['lines' => function($q){
            return $q->with(['product']);
        }])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->where('nomor', 'LIKE', '%' . $search . '%');
        })
        ->when($request->status == 'unpaid', function ($q) {
            return $q->where('state', 'pending')
            ->where('payment_status', 'unpaid');
        })
        ->when($request->status == 'pending', function ($q) {
            return $q->where('state', 'pending')
            ->where('payment_status', 'paid');
        })
        ->when($request->status == 'process', function ($q) {
            return $q->whereIn('state',  'process')
            ->where('payment_status', 'paid');
        })
        ->when($request->status == 'shipped', function ($q) {
            return $q->where('state', '=', 'shipped')
            ->where('payment_status', 'paid');
        })
        ->when($request->status == 'done', function ($q) {
            return $q->where('state', '=', 'done')
            ->where('payment_status', 'paid');
        })
        ->when($request->status == 'cancel', function ($q) {
            return $q->where('state', '=', 'cancel');
        })
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            if($id){
                $data = $query->where('id', $id)->first();
            }else{
                $data = $query->get();
            }

        }
        return response()->json($data);
    }

}
