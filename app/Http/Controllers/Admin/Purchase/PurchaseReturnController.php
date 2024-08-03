<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;
use App\Helpers\GeneralHelp;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnLine;
use App\Models\Payment;


class PurchaseReturnController extends Controller
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

        $query = PurchaseReturn::with(['purchase' => function($q){
            $q->select('id', 'supplier_id', 'ref', 'date')
            ->with(['supplier:id,name']);
        },])->withCount(['line']);

        // $query->when($request->status == 'pending' || empty($request->status), function ($q) {
        //     return $q->where('status', 'pending')->where('payment_status', 'paid');
        // });

        // $query->when($request->status == 'confirmed', function ($q) {
        //     return $q->where('status', 'confirmed')->where('payment_status', 'paid');
        // });

        // $query->when($request->status == 'dikirim', function ($q) {
        //     return $q->where('status', 'delivery')->where('payment_status', 'paid');
        // });

        // $query->when($request->status == 'selesai', function ($q) {
        //     return $q->where('status', 'done')->orwhere('status', 'cancel');
        // });

        $query->when($request->search, function ($q) {
            return $q->where('ref', 'LIKE', '%' . $search . '%');
        });
        
        $dataList = $query->orderBy('id', 'desc')->paginate(10);
 
        return Inertia::render('Backend/PurchaseReturn/Index', [
            'dataList' => $dataList,
        ]);
    }


    public function create($id){

        $data = Purchase::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'supplier', 'staff'])
        ->where('id', $id)->first();

        
        return Inertia::render('Backend/PurchaseReturn/Form',[
            'data' => $data
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'date' => 'required',
        ];

        $pesan = [
            'date.required' => 'Return Date Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new PurchaseReturn();
                $data->ref = GeneralHelp::generate_ref_sale_return();
                $data->date = Carbon::now();
                $data->purchase_id = $request->purchase_id;
                $data->discount_type = $request->discount_type;
                $data->discount_amount = $request->discount_amount;
                $data->discount_value = $request->discount_value;
                $data->total = $request->total;
                $data->grand_total = $request->grand_total;
                $data->staff_id = auth()->guard('admin')->user()->id;
                $data->status = 'draft';
                $data->payment_status = 'unpaid';
                $data->save();

                foreach($request->lines as $i){
                    $line = new PurchaseReturnLine();
                    $line->product_id = $i['id'];
                    $line->variant_id = $i['variant_id'];
                    $line->unit_price = $i['price'];
                    $line->qty = $i['qty_return'];
                    $line->sub_total = $i['subtotal'];
                    $data->line()->save($line);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.purchase.return.show', $data->id);
        }
    }

    /**
     * Show Sale Detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PurchaseReturn::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'purchase' => function($q){
            $q->with(['supplier:id,name']);
        }, 'staff'])
        ->where('id', $id)->first();

        $payment = Payment::where('paymenttable_type', 'App\Models\PurchaseReturn')->where('paymenttable_id', $data->id)->get();

        return Inertia::render('Backend/PurchaseReturn/Show',[
            'data' => $data,
            'payment' => $payment,
        ]);
    }

    
    public function edit($id, Request $request){
        
        $data = PurchaseReturn::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'staff'])
        ->where('id', $id)->first();

        return Inertia::render('Backend/PurchaseReturn/Form', [
            'data' => $data,
            'editMode' => true
        ]);
    }

    
    /**
     * Store a newly payment created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function payment(Request $request)
    {
        // dd($request->all());
        $rules = [
            'amount_received' => 'required',
            'paymenttable_type' => 'required',
        ];

        $pesan = [
            'amount_received.required' => 'Amount Received Must Be Filled!',
            'paymenttable_type.required' => 'Payment Type Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    
                    $data = new Payment();
                    $data->paymenttable_type = $request->paymenttable_type;
                    $data->paymenttable_id = $request->paymenttable_id;
                    $data->amount = $request->amount;
                    $data->amount_received = $request->amount_received;
                    $data->payment_method_id = $request->payment_method_id;
                    $data->change = $request->change;
                    $data->date = Carbon::now();
                    $data->validated_at = Carbon::now();
                    $data->validated_by = auth()->guard('admin')->user()->id;
                    $data->save();

                    $return = $request->paymenttable_type::where('id', $request->paymenttable_id)->first();
                    if($return->to_pay == 0){
                        $return->payment_status = 'paid';
                    }else if($return->to_pay < $return->grand_total){
                        $return->payment_status = 'partial';
                    }else{
                        $return->payment_status = 'unpaid';
                    }
                    $return->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.purchase.return.show', $return->id);
        }
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
            'date' => 'required',
        ];

        $pesan = [
            'date.required' => 'Return Date Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = PurchaseReturn::where('id', $request->id)->first();
                $data->ref = GeneralHelp::generate_ref_sale_return();
                $data->date = Carbon::now();
                $data->purchase_id = $request->purchase_id;
                $data->discount_type = $request->discount_type;
                $data->discount_amount = $request->discount_amount;
                $data->discount_value = $request->discount_value;
                $data->total = $request->total;
                $data->grand_total = $request->grand_total;
                $data->staff_id = auth()->guard('admin')->user()->id;
                $data->status = 'draft';
                $data->payment_status = 'unpaid';
                $data->save();

                foreach($request->lines as $i){
                    $line = new PurchaseReturnLine();
                    $line->product_id = $i['id'];
                    $line->variant_id = $i['variant_id'];
                    $line->unit_price = $i['price'];
                    $line->qty = $i['qty_return'];
                    $line->sub_total = $i['subtotal'];
                    $data->line()->save($line);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.purchase.return.show', $data->id);
        }
    }

        /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update_status(Request $request)
    {
        DB::beginTransaction();
        try{
            $return = PurchaseReturn::where('id', $request->id)->first();
            $return->status = $request->status;
            $return->save();

            if($request->status == 'done'){
                foreach($return->line as $l){
                    
                }
            }
            

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.order.index', $sale->id);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    

    private function uploadFiles($file){
        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs(
            'uploads/sliders',
            $file,
            $file_name
        );
        
        return 'uploads/sliders/'.$file_name;
    }

}
