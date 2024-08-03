<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\UserAddress;

use App\Models\Sale\SaleOrder;
use App\Models\Sale\SaleOrderLine;

use Carbon\Carbon;
use Midtrans\Notification;
class CheckoutController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }

    public function index(Request $request)
    {

        if ($request->isMethod('post')) {
            Session::put('cart', $request->cart);
            // $cart = $request->cart;
            $cart = Cart::with(['product'])->whereIn('id', $request->cart)->get();
        }else if($request->isMethod('get')){
            if(Session::has('cart') && count(Session::get('cart')) > 0){
            //    $cart = ;
               $cart = Cart::with(['product'])->whereIn('id', Session::get('cart'))->get();
            }else{
                return redirect()->route('cart.index');
            }
        }

        $user_id = auth()->guard('web')->user()->id;
        $address = UserAddress::where('user_id', $user_id)->where('is_main', 1)->first();

        return Inertia::render('Checkout/Shipping',[
            'cart' => $cart,
            'address' => $address,
        ]);
    }

    public function payment(Request $request)
    {
        if ($request->isMethod('post')) {
            Session::put('checkout', $request->all());
            $data = $request->all();
        }else if($request->isMethod('get')){
            if(Session::has('checkout') && count(Session::get('checkout')) > 0){
                $data = Session::get('checkout');
            }else{
                return redirect()->route('cart.index');
            }
        }
        
        return Inertia::render('Frontend/Checkout/Payment',[
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{

            // $code = GeneralHelp::generate_payment_code($request->payment_method);
            $user = auth()->guard('web')->user();

            $data = new SaleOrder();
            $data->date = Carbon::now();
            $data->nomor = $this->getNumber();
            $data->uid = uniqid();
            $data->customer_id = $user->id;
            $data->delivery_id = $request->address_id;
            $data->total = $request->total;
            $data->shipping_cost = $request->shipping_cost;
            $data->grand_total = $request->total + $request->shipping_cost;
            $data->state = 'pending';
            $data->payment_status = 'unpaid';
            $data->payment_method = 'Transfer';
            $data->save();

            $lines = array();
            foreach($request->lines as $i){
                $line = new SaleOrderLine();
                $line->product_id = $i['product_id'];
                $line->harga = $i['harga'];
                $line->qty = $i['qty'];
                $line->subtotal = $i['harga'] * $i['qty'];
                $data->lines()->save($line);

                $lines[] = [
                    'id' => $line->id,
                    'price' => $line->harga,
                    'quantity' => $line->qty,
                    'name' => $i['product']['nama'],
                ];

                $cart = Cart::where('id', '=', $i['id'])->delete();
            }

            $lines[] = [
                'id' => '0',
                'price' => $request->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Kirim',
            ];
            
            $payload = [
                'transaction_details' => [
                    'order_id'     => $data->uid,
                    'gross_amount' => $request->total + $request->shipping_cost,
                ],
                'customer_details' => [
                    'first_name' => $user->nama,
                    'email'      => $user->email,
                ],
                'item_details' => $lines,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($payload);
            $data->payment_ref = $snapToken;
            $data->save();

            // $cart = Cart::whereIn('id')

            Session::forget('cart');

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }
        DB::commit();
        return redirect()->to('https://app.sandbox.midtrans.com/snap/v4/vtweb/'.$snapToken);
    }

    
    private function getNumber()
    {
        $q = SaleOrder::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'SO/';
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code . date('ym') .'/'.sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code . date('ym') .'/'. sprintf("%05s", $no);
        }
    }

    public function callbackPayment(){
        $notification = new \Midtrans\Notification();

        $orderNumber = $notification->order_id;
        $order = SaleOrder::where('ref', $order_id)->first();

        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($notification->fraud_status) ? ($notification->fraud_status == 'accept') : true;

        if($transactionStatus == 'capture' || $transactionStatus == 'settlement'){
            $order->payment_status = 'paid';
        }else if($transactionStatus == 'expire'){
            $order->state = 'cancel';
        }else if($transactionStatus == 'cancel'){
            $order->state = 'cancel';
        }
        $order->save();

        return response()
        ->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diproses',
        ]);
    }

    public function state($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SaleOrder::where('id', $id)->first();
            $data->state = 'paid';
            $data->payment_method = 'Transfer';
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('user.order.show', $id);
    }

}
