<?php

namespace App\Http\Controllers\Admin\Sale;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;
use Carbon\Carbon;
use App\Models\Sale\SaleReturn;
use App\Models\Sale\SaleReturnLine;
use PDF;
use App\Models\Inventory\ProductVariant;
class ReturnController extends Controller
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
        $counter = collect([
            'pending' => SaleReturn::where('status', 'pending')->get()->count(), 
            'confirm' => SaleReturn::where('status', 'confirm')->get()->count(),
            'reject' => SaleReturn::where('status', 'reject')->get()->count(),
        ]);

        return Inertia::render('Sale/Return/Index',[
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Sale/Return/Form');
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
            'customer_id.required' => 'Pelanggan Must Be Filled!',
            'date.required' => 'Pelanggan Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new SaleOrder();
                $data->nomor = $this->getNumber();
                $data->date = $request->date;
                $data->customer_id = $request->customer_id;
                $data->total = $request->total;
                $data->state = 'draft';
                $data->save();

                foreach($request->lines as $i){
                    $line = new SaleOrderLine();
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->price = $i['price'];
                    $line->unit_id = $i['unit_id'];
                    $line->qty = $i['qty'];
                    $line->subtotal = $i['subtotal'];
                    $data->lines()->save($line);
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
     * Show the form for editing a resource.
     * @return Renderable
     */
    public function edit($id)
    {
        $data = SaleOrder::with(['lines' => function($q){
            $q->with(['product:id,name', 'variant:id,name', 'unit:id,name,code']);
        }, 'customer'])
        ->where('id', $id)->first();

        return Inertia::render('Sale/Return/Form', [
            'data' => $data
        ]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'date' => 'required',
        ];

        $pesan = [
            'customer_id.required' => 'Pelanggan Must Be Filled!',
            'date.required' => 'Pelanggan Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = SaleOrder::where('id', $request->id)->first();
                $data->date = $request->date;
                $data->customer_id = $request->customer_id;
                $data->total = $request->total;
                $data->save();

                foreach($request->lines as $i){
                    if(array_key_exists("id", $i)){
                        $line_id = $i['id'];
                    }else{
                        $line_id = null;
                    }

                    $line = SaleOrderLine::firstOrNew(['id' =>  $line_id]);
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->price = $i['price'];
                    $line->unit_id = $i['unit_id'];
                    $line->qty = $i['qty'];
                    $line->subtotal = $i['subtotal'];
                    $data->lines()->save($line);
                }
                $remove = SaleOrderLine::where('order_id', $data->id)->whereIn('id', $request->lines_deleted)->delete();

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
        $data = SaleReturn::with(['lines' => function($q){
            $q->with(['product:id,name', 'variant:id,name', 'unit:id,name,code']);
        }, 'customer'])
        ->where('id', $id)->first();

        return Inertia::render('Sale/Return/Show',[
            'data' => $data
        ]);
        // dd($data);
    }
    
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateStatus(Request $request)
    {
        
        DB::beginTransaction();
        try{

            $data = Purchase::where('id', $request->id)->first();
            $data->status = $request->status;
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.order.show', $data->id);
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
            $data = SaleOrder::where('id', $id)->delete();
            $line = SaleOrderLine::where('order_id', $id)->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.order.index');
    }

    
    public function state($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SaleReturn::with(['lines'])->where('id', $id)->first();
            $data->status = $request->state;
            $data->save();

            if($request->state === 'confirm'){
                foreach($data->lines as $line){
                    $s = ProductVariant::where('id', $line->variant_id)->first();
                    $s->stock -= $line->qty;
                    $s->save();
                }
            }

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.sale.return.show', $id);
    }
    
    private function getNumber()
    {
        $q = SaleReturn::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'SR/';
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
        }, 'customer', 'staff'])
        ->where('id', $id)->first();
        
        $pdf = PDF::loadView('report.purchase', compact([
            'data'
        ]));

        return $pdf->stream();
    }


    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function report(Request $request)
    {
        // dd($request->all());
        $data = SaleReturn::with(['customer:id,name', 'order'])
        ->withCount(['lines'])
        ->when($request->status == 'Pending', function ($q) {
            return $q->where('status', 'pending');
        })
        ->when($request->status == 'Disetujui', function ($q) {
            return $q->where('status', 'confirm');
        })
        ->when($request->status == 'Ditolak', function ($q) {
            return $q->where('status', 'reject');
        })
        ->whereBetween('date', [$request->d, $request->a])
        ->get();

        $config = [
            'format' => 'A4-L'
        ];

        // dd($data->toArray());
        $pdf = PDF::loadView('report.sale.return', [
            'data' => $data,
            'mulai' => $request->d,
            'selesai' => $request->a,
            'status' => $request->status,
        ], [ ], $config);

        return $pdf->stream('Laporan Retur '. $request->d .'-'. $request->t .'.pdf');
    }
    
    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $query = SaleReturn::with(['customer:id,name', 'order'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->where('nomor', 'LIKE', '%' . $search . '%');
        })
        ->when($request->status == 'pending', function ($q) {
            return $q->where('status', 'pending');
        })
        ->when($request->status == 'confirm', function ($q) {
            return $q->where('status', 'confirm');
        })
        ->when($request->status == 'reject', function ($q) {
            return $q->where('status', 'reject');
        })
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            $data = $query->get();
        }
        return response()->json($data);
    }
}
