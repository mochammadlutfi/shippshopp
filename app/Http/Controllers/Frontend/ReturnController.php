<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Sale\SaleReturn;
use App\Models\Sale\SaleReturnLine;
class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('UserReturn/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('UserReturn/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'order_id' => 'required',
        ];

        $pesan = [
            'order_id.required' => 'Pesanan Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new SaleReturn();
                $data->nomor = $this->getNumber();
                $data->order_id = $request->order_id;
                $data->date = Carbon::today();
                $data->customer_id = auth()->guard('web')->user()->id;
                $data->status = 'pending';
                $data->save();

                foreach($request->lines as $i){
                    $line = new SaleReturnLine();
                    $line->order_line_id = $i['line_id'];
                    $line->product_id = $i['product_id'];
                    $line->variant_id = $i['variant_id'];
                    $line->qty_order = $i['qty_order'];
                    $line->qty = $i['qty'];
                    $line->reason = $i['reason'];
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = auth()->guard('web')->user();

        $data = SaleReturn::with([
            'lines' => function($q){
                return $q->with(['product', 'variant']);
            }, 'customer'
        ])
        ->where('id', $id)->first();

        return Inertia::render('UserReturn/Show',[
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function data(Request $request)
    {
        
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;
        $id = $request->id;

        $query = SaleReturn::with(['lines' => function($q){
            return $q->with(['product', 'variant']);
        }])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->where('nomor', 'LIKE', '%' . $search . '%');
        })
        ->when($request->status == 'pending', function ($q) {
            return $q->where('status', 'pending');
        })
        ->when($request->status == 'confirm', function ($q) {
            return $q->where('status', '=', 'confirm');
        })
        ->when($request->status == 'reject', function ($q) {
            return $q->where('status', '=', 'reject');
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
}
