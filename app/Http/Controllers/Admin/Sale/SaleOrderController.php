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
use App\Models\Sale;
use App\Models\SaleLine;
use App\Models\Payment;    
use Barryvdh\DomPDF\Facade\Pdf;



class SaleOrderController extends Controller
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

        $startDate = !empty($request->startDate) ? Carbon::parse($request->startDate)->format('Y-m-d') : Carbon::now()->startofmonth()->format('Y-m-d');
        $endDate = !empty($request->endDate) ? Carbon::parse($request->endDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $sort = !empty($request->sort) ? $request->sort : 'date';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $query = Sale::
        select("sales.*", "b.id as customer_id", 'b.name as customer')
        ->join('users as b', 'b.id', '=', 'sales.customer_id')
        ->withCount(['line'])
        ->where('is_pos', 0)->where('is_web', 0)
        ->when($request->search, function($q, $search){
            return $q->where('ref', 'LIKE', '%' . $search . '%')
            ->orWhere('b.name', 'LIKE', '%' . $search . '%')
            ->orWhere('date', 'LIKE', '%' . $search . '%');
        })
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereDate("date", ">=", $startDate)
                ->orWhereDate("date", "=", $endDate);
        });
        
        $dataList = $query->orderBy($sort, $sortDir)->paginate($limit);
 
        return Inertia::render('Backend/Sales/Index', [
            'dataList' => $dataList,
        ]);
    }

    public function web(Request $request)
    {
        
        $startDate = !empty($request->startDate) ? Carbon::parse($request->startDate)->format('Y-m-d') : Carbon::now()->startofmonth()->format('Y-m-d');
        $endDate = !empty($request->endDate) ? Carbon::parse($request->endDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $sort = !empty($request->sort) ? $request->sort : 'date';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $status = ($request->status) ? $request->status : 'pending';

        $overview = [
            'pending' => Sale::where('status', 'pending')->where('is_web', 1)->count(),
            'confirmed' => Sale::where('status', 'confirmed')->where('is_web', 1)->count(),
            'dikirim' => Sale::where('status', 'delivery')->where('is_web', 1)->count(),
            'selesai' => Sale::where('status', 'done')->where('is_web', 1)->count(),
            'cancel' => Sale::where('status', 'cancel')->where('is_web', 1)->count(),
        ];

        $query = Sale::select("sales.*", "b.id as customer_id", 'b.name as customer')
        ->join('users as b', 'b.id', '=', 'sales.customer_id')
        ->Where('is_web', 1)
        ->when($status != 'cancel', function ($q) use ($status) {
            return $q->where('status', $status)->whereIn('payment_status', ['paid', 'pending']);
        });

        $query->when($status == 'cancel', function ($q) {
            return $q->where('status', 'cancel');
        });
        
        $dataList = $query->orderBy('id', 'desc')->paginate($limit);
 
        return Inertia::render('Backend/Sales/IndexWeb', [
            'dataList' => $dataList,
            'overview' => $overview
        ]);
    }

    public function create(){
        
        return Inertia::render('Backend/Sales/Form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'date' => 'required',
        ];

        $pesan = [
            'customer_id.required' => 'Customer Must Be Filled!',
            'date.required' => 'Supplier Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Sale();
                $data->date = Carbon::now();
                $data->ref = GeneralHelp::generate_ref_sale();
                $data->customer_id = $request->customer_id;
                $data->discount_type = $request->discount_type;
                $data->discount_amount = $request->discount_amount;
                $data->discount_value = $request->discount_value;
                $data->total = $request->total;
                $data->grand_total = $request->grand_total;
                $data->staff_id = auth()->guard('admin')->user()->id;
                $data->status = 'pending';
                $data->payment_status = 'unpaid';
                $data->tax_id = $request->tax_id;
                $data->tax_amount = $request->tax_amount;
                $data->save();

                foreach($request->lines as $i){
                    $line = new SaleLine();
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->unit_price = $i['price'];
                    $line->net_price = $i['price'];
                    $line->qty = $i['qty'];
                    $line->discount_type = $i['discount_type'];
                    $line->discount_value = $i['discount_value'];
                    $line->discount_amount = $i['discount_amount'] != NULL ? $i['discount_amount'] : 0 ;
                    $line->tax_id = $i['tax_id'];
                    $line->tax_amount = $i['tax_amount'] != NULL ? $i['tax_amount'] : 0 ;
                    $line->sub_total = $i['subtotal'];
                    $data->line()->save($line);
                }

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.sale.order.show', $data->id);
        }
    }

    /**
     * Show Sale Detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Sale::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'payment' => function($q){
            $q->with(['payment_method:id,name,image']);
        }, 'customer', 'staff', 'delivery'])
        ->where('id', $id)->first();

        return Inertia::render('Backend/Sales/Show',[
            'data' => $data
        ]);
    }

    
    public function edit($id, Request $request){
        
        $data = Sale::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'payment' => function($q){
            $q->with(['payment_method:id,name,image']);
        }, 'customer', 'staff'])
        ->where('id', $id)->first();

        return Inertia::render('Backend/Sales/Form', [
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
                    $data->type = 'outbound';
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
            return redirect()->route('admin.sale.order.show', $return->id);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'customer_id' => 'required',
            'date' => 'required',
            'status' => 'required',
        ];

        $pesan = [
            'customer_id.required' => 'Customer Must Be Filled!',
            'date.required' => 'Supplier Must Be Filled!',
            'status.required' => 'Supplier Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Sale::where('id', $request->id)->first();
                $data->date = Carbon::now();
                $data->ref = $request->ref;
                $data->customer_id = $request->customer_id;
                $data->discount_type = $request->discount_type;
                $data->discount_amount = $request->discount_amount;
                $data->discount_value = $request->discount_value;
                $data->total = $request->total;
                $data->grand_total = $request->grand_total;
                $data->staff_id = auth()->guard('admin')->user()->id;
                $data->status = $request->status;
                $data->payment_status = $request->payment_status;
                $data->tax_id = $request->tax_id;
                $data->tax_amount = $request->tax_amount;
                $data->save();

                foreach($request->lines as $i){

                    if(array_key_exists("id", $i)){
                        $line_id = $i['id'];
                    }else{
                        $line_id = null;
                    }

                    $line = SaleLine::firstOrNew(['id' =>  $line_id]);
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->unit_price = $i['price'];
                    $line->net_price = $i['price'];
                    $line->qty = $i['qty'];
                    $line->discount_type = $i['discount_type'];
                    $line->discount_value = $i['discount_value'];
                    $line->discount_amount = $i['discount_amount'] != NULL ? $i['discount_amount'] : 0 ;
                    $line->tax_id = $i['tax_id'];
                    $line->tax_amount = $i['tax_amount'] != NULL ? $i['tax_amount'] : 0 ;
                    $line->sub_total = $i['subtotal'];
                    $data->line()->save($line);
                }

                $remove = SaleLine::where('sale_id', $data->id)->whereIn('id', $request->removed)->delete();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.sale.order.show', $data->id);
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
            $sale = Sale::where('id', $request->id)->first();
            $sale->status = $request->status;
            $sale->save();

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
    public function pdf($id)
    {

        $data = Sale::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'payment' => function($q){
            $q->with(['payment_method:id,name,image']);
        }, 'customer', 'staff', 'delivery'])
        ->where('id', $id)->first();
        
        // return view("report.invoice", compact([
        //     'data'
        // ]));
        $pdf = PDF::loadView('report.invoice', compact([
            'data'
        ]));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
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
            $data = Sale::where('id', $id)->delete();
            $line = SaleLine::where('sale_id', $id)->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.order.index');
    }

}
