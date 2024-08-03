<?php

namespace App\Http\Controllers\Admin\Purchase;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;
use Carbon\Carbon;
use PDF;

use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseOrderLine;
use App\Models\Inventory\ProductVariant;

class OrderController extends Controller
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
        return Inertia::render('Purchase/Order/Index');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Purchase/Order/Form');
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
            'supplier_id' => 'required',
            'date' => 'required',
        ];

        $pesan = [
            'supplier_id.required' => 'Supplier Must Be Filled!',
            'date.required' => 'Supplier Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new PurchaseOrder();
                $data->nomor = $this->getNumber();
                $data->date = $request->date;
                $data->supplier_id = $request->supplier_id;
                $data->total = $request->total;
                $data->state = 'draft';
                $data->save();

                foreach($request->lines as $i){
                    $line = new PurchaseOrderLine();
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
            return redirect()->route('admin.purchase.order.show', $data->id);
        }
    }
    

    /**
     * Show the form for editing a resource.
     * @return Renderable
     */
    public function edit($id)
    {
        $data = PurchaseOrder::with(['lines' => function($q){
            $q->with(['product:id,name', 'variant' => function($q){
                return $q->with('purchase_unit');
            }, 'unit:id,name,code']);
        }, 'supplier'])
        ->where('id', $id)->first();

        return Inertia::render('Purchase/Order/Form', [
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
            'supplier_id' => 'required',
            'date' => 'required',
        ];

        $pesan = [
            'supplier_id.required' => 'Supplier Must Be Filled!',
            'date.required' => 'Supplier Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = PurchaseOrder::where('id', $request->id)->first();
                $data->date = $request->date;
                $data->supplier_id = $request->supplier_id;
                $data->total = $request->total;
                $data->save();

                foreach($request->lines as $i){
                    if(array_key_exists("id", $i)){
                        $line_id = $i['id'];
                    }else{
                        $line_id = null;
                    }

                    $line = PurchaseOrderLine::firstOrNew(['id' =>  $line_id]);
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->price = $i['price'];
                    $line->unit_id = $i['unit_id'];
                    $line->qty = $i['qty'];
                    $line->subtotal = $i['subtotal'];
                    $data->lines()->save($line);
                }
                $remove = PurchaseOrderLine::where('order_id', $data->id)->whereIn('id', $request->lines_deleted)->delete();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.purchase.order.show', $data->id);
        }
    }

    /**
     * Show Sale Detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = PurchaseOrder::with(['lines' => function($q){
            $q->with(['product:id,name', 'variant', 'unit:id,name,code']);
        }, 'supplier'])
        ->where('id', $id)->first();

        return Inertia::render('Purchase/Order/Show',[
            'data' => $data
        ]);
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

            if($request->status === 'done'){
                foreach($data->line as $line){
                    $s = ProductStock::firstOrNew(['product_id' =>  $line->product_id, 'variant_id' =>  $line->variant_id]);
                    $s->stock = ($s->stock != null) ? $s->stock + $line->qty : $line->qty;
                    $s->save();
                }
            }

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.purchase.order.show', $data->id);
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
            $data = PurchaseOrder::where('id', $id)->delete();
            $line = PurchaseOrderLine::where('order_id', $id)->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.purchase.order.index');
    }

    
    private function getNumber()
    {
        $q = PurchaseOrder::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'PO/';
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
        $data = Purchase::with(['line' => function($q){
            $q->with(['product:id,name']);
        }, 'payment' => function($q){
            $q->with(['payment_method:id,name,image']);
        }, 'supplier', 'staff'])
        ->where('id', $id)->first();
        
        $pdf = PDF::loadView('report.purchase', compact([
            'data'
        ]));

        return $pdf->stream();
    }

    
    public function report(Request $request)
    {
        // dd($request->all());
        $data = PurchaseOrder::with(['supplier:id,name'])
        ->withCount(['lines'])
        ->when($request->status == 'Pending', function ($q) {
            return $q->where('state', 'draft');
        })
        ->when($request->status == 'Selesai', function ($q) {
            return $q->where('state', 'done');
        })
        ->when($request->status == 'Batal', function ($q) {
            return $q->where('state', 'cancel');
        })
        ->whereBetween('date', [$request->d, $request->a])
        ->get();

        $config = [
            'format' => 'A4-L'
        ];

        // dd($data->toArray());
        $pdf = PDF::loadView('report.purchase.list', [
            'data' => $data,
            'mulai' => $request->d,
            'selesai' => $request->a,
            'status' => $request->status,
        ], [ ], $config);

        return $pdf->stream('Laporan Pembelian '. $request->d .'-'. $request->t .'.pdf');
    }

    public function state($id, Request $request )
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $data = PurchaseOrder::with(['lines'])->where('id', $id)->first();
            $data->state = $request->state;
            $data->date_received = $request->date;
            $data->ref = $request->ref;
            $data->save();
            $total = 0;
            if($request->state == 'done')
            {
                foreach($request->lines as $line){
                    $subtotal = $line['price'] * $line['qty_receipt'];
                    $ls = PurchaseOrderLine::where('id', $line['id'])->first();
                    $ls->price = $line['price'];
                    $ls->qty_receipt =  $line['qty_receipt'];
                    $ls->subtotal = $subtotal;
                    $ls->save();

                    $total += $subtotal;

                    $s = ProductVariant::where('id', $line['variant_id'])->first();
                    $s->stock += $line['qty_receipt'];
                    $s->save();
                }
            }
            $data->total = $total;
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return back();
        }
        DB::commit();
        return redirect()->route('admin.purchase.order.show', $data->id);
    }
    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $query = PurchaseOrder::with(['supplier:id,nama'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->where('nomor', 'LIKE', '%' . $search . '%');
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
