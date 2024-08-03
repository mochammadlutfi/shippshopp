<?php

namespace App\Http\Controllers\Admin\Product;


use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\PurchaseLine;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;


use Intervention\Image\Facades\Image;

use App\Helpers\Collection;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($type = null, Request $request)
    {
        $search = $request->search;
        $sort = ($request->sort) ? $request->sort : 'name';
        $sort_dir = ($request->sortDir) ? $request->sortDir : 'asc';
        $limit = ($request->limit) ? $request->limit : 25;

        $res = Product::select('id', 'name', 'show_web', 'slug', 'category_id', 'brand_id')
        ->withCount([
            'stock as stock' => function ($query) {
                $query->select(DB::raw("SUM(stock)"));
            },
            'sale as sold'
        ])
        ->with([
            'variant', 'category:id,name'
        ])
        ->when($search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->get();

        $counts = [
            'all' => $res->count(),
            'soldout' => $res->where('stock', 0)->count(),
            'ready' => $res->where('stock', '>', 0)->count(),
        ];

        if($type == 'soldout'){
            $res = $res->where('stock', 0);
        }else if($type == 'ready'){
            $res = $res->where('stock', '>', 0);
        }

        if($sort_dir == 'asc'){
            $sorted = $res->sortBy($sort);
        }else{
            $sorted = $res->sortByDesc($sort);
        }
        
        if($sort_dir == 'asc'){
            $sorted = $res->sortBy($sort);
        }else{
            $sorted = $res->sortByDesc($sort);
        }

        
        $ProductsPaginated = (new Collection($sorted->values()))->paginate($limit);
        $items = $ProductsPaginated->items();
        
        $decodeFix = json_decode($ProductsPaginated->toJson());
        $decodeFix->data = array_values($items);
 
        return Inertia::render('Backend/Product/index', [
            'dataList' => $decodeFix,
            'type' => $type,
            'counts' => $counts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Backend/Product/form');
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
            'category_id' => 'required',
        ];

        if($request->has_variant == false){
            $rules['sell_price'] = 'required';
            $rules['sell_unit_id'] = 'required';
            $rules['purchase_price'] = 'required';
            $rules['purchase_unit_id'] = 'required';
        }

        $pesan = [
            'name.required' => 'Nama Produk Wajib Diisi!',
            'category_id.required' => 'Kategori Produk Wajib Diisi!',
            'sell_price.required' => 'Harga Jual Wajib Diisi!',
            'sell_unit_id.required' => 'Satuan Jual Wajib Diisi!',
            'purchase_price.required' => 'Harga Beli Wajib Diisi!',
            'purchase_unit_id.required' => 'Satuan Beli Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $product = new Product();
                $product->name = $request->name;
                $product->description = $request->description;
                $product->category_id = $request->category_id;
                $product->has_variant = $request->has_variant;
                $product->save();

                if(!$request->has_variant){
                    $variant = new ProductVariant();
                    $variant->sell_price = $request->variant[0]['sell_price'];
                    $variant->purchase_price = $request->variant[0]['purchase_price'];
                    $variant->sku =$request->variant[0]['sku'];
                    $variant->product_id = $product->id;
                    $variant->save();
                }else{
                    $product->var1_name = $request->var1_name;
                    $product->var1_value = json_encode($request->var1_value);

                    if(!empty($request->var2_value)){
                        $product->var2_name = $request->var2_name;
                        $product->var2_value = json_encode($request->var2_value);
                    }
                    $product->save();
                    foreach($request->variant as $v){
                        $variant = new ProductVariant();
                        $variant->variant = $v["var1"];
                        if(!empty($request->var2_value)){
                            $variant->variant2 = $v["var2"];
                        }
                        $variant->sell_price = $v["sell_price"];
                        $variant->purchase_price = $v["purchase_price"];
                        $variant->sku = $v["sku"];
                        $variant->product_id = $product->id;
                        $variant->save();
                    }
                }
                $count = 0;
                foreach($request->images as $i){
                    $image = new ProductImage();
                    $image->product_id = $product->id;
                    $image->path =  $this->uploadImage($i["image"], Str::slug($product->name, '-'), $product->id);
                    if($count == 0){
                        $image->is_utama = 1;
                    }else{
                        $image->is_utama = 0;
                    }
                    $image->save();
                    $count++;
                }
                
            }catch(\QueryException $e){
                dd($e);
                DB::rollback();
            }
            DB::commit();
            return redirect()->route('admin.product.show', $product->id);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Product::with(['images', 'category'])
        ->where('id', $id)->first();

        return Inertia::render('Backend/Product/show', [
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Product::with(['images', 'variant'])
        ->where('id', $id)->first();

        return Inertia::render('Backend/Product/form', [
            'data' => $data,
            'editMode' => true,
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
            'name' => 'required',
            // 'description' => 'required',
            'category_id' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Produk Wajib Diisi!',
            // 'description.required' => 'Deksripsi Produk Wajib Diisi!',
            // 'description.min' => 'Deksripsi Produk Terlalu Sedikit!',
            'category_id.required' => 'Kategori Produk Wajib Diisi!',
        ];

        // if(count($request->variant) > 1)
        // {
            
        //     $rules['var1_name'] = 'required';
        //     $rules['var1_value'] = 'required';

        //     $pesan['var1_nama.required'] = 'Nama Variasi Produk Wajib Diisi!';
        //     $pesan['var1_value.required'] = 'Pilihan Variasi Produk Wajib Diisi!';
        //     if($request->var2_name)
        //     {
        //         $rules['var2_name'] = 'required';
        //         $rules['var2_value'] = 'required';

        //         $pesan['var2_name.required'] = 'Nama Variasi Produk Wajib Diisi!';
        //         $pesan['var2_value.required'] = 'Pilihan Variasi Produk Wajib Diisi!';
        //     }
        // }else{
        //     $rules['sell_price'] = 'required';
        //     $rules['purchase_price'] = 'required';

        //     $pesan['sell_price.required'] = 'Harga Jual Produk Wajib Diisi!';
        //     $pesan['purchase_price.required'] = 'Harga Beli Produk Wajib Diisi!';
        // }

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            // dd($request->all());
            DB::beginTransaction();
            try{
                $product = Product::find($request->id);
                $product->name = $request->name;
                $product->description = $request->description;
                $product->category_id = $request->category_id;
                $product->has_variant = $request->has_variant;
                $product->save();


                if(!$request->has_variant){

                    $variant = ProductVariant::where("id", $request->variant[0]['id'])->first();
                    $variant->sell_price = $request->variant[0]['sell_price'];
                    $variant->purchase_price = $request->variant[0]['purchase_price'];
                    $variant->sku =$request->variant[0]['sku'];
                    $variant->save();

                }else{
                    $product->var1_name = $request->var1_name;
                    $product->var1_value = json_encode($request->var1_value);

                    if(!empty($request->var2_value)){
                        $product->var2_name = $request->var2_name;
                        $product->var2_value = json_encode($request->var2_value);
                    }

                    $product->save();

                    foreach($request->variant as $v){
                        $params = [
                            'product_id' => $product->id,
                            'variant' => $v['var1'],
                        ];

                        if(!empty($request->var2_value)){
                            $params['variant2'] = $v["var2"];
                        }

                        ProductVariant::firstOrNew($params, [
                            'sell_price' => $v["sell_price"],
                            'purchase_price' => $v["purchase_price"], 
                            'sku' => $v["sku"]
                        ]);
                    }
                }

                $count = 0;
                if($request->imageDeleted){
                    foreach($request->imageDeleted as $del){
                        // dd($del);
                        $imgDel = ProductImage::find($del);
                        Storage::disk('public')->delete($imgDel->path);
                        $imgDel->delete();
                    }
                }

                foreach($request->images as $img){
                    if(empty($img["id"]) && !empty($img["image"])){
                        $image = new ProductImage();
                        $image->product_id = $product->id;
                        $image->path =  $this->uploadImage($img["image"], Str::slug($product->name, '-'), $product->id);
                        if(ProductImage::where('product_id', $product->id)->where('is_utama', 1)->get()->count() > 0){
                            $image->is_utama = 0;
                        }else{
                            $image->is_utama = 1;
                        }
                        $image->save();
                        $count++;
                    }
                }
                
            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.product.index');
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
            $product = Product::where('id', $id)->delete();
            $produtImage = ProductImage::where('product_id', $id)->delete();
            $produtVariant = ProductVariant::where('product_id', $id)->delete();
        
        }catch(\QueryException $e){
            DB::rollback();
        }
        DB::commit();
        return redirect()->route('admin.product.index')->with('message', 'Product Deleted Sucessfully');

    }


    public function import(){
        $filepath = public_path('product_new.csv');
        // Reading file
        $file = fopen($filepath, "r");
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            if ($i == 0) {
            $i++;
            continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file);
        
        $j = 0;
        $no = 1;
        // dd($importData_arr);
        DB::beginTransaction();
        try{
            foreach ($importData_arr as $importData) {
                $sku = $importData[0];
                $name = $importData[1];
                $stock = (int)$importData[2];
                $sell_price = (int)$importData[3];
                $purchase_price = (int)$importData[4];
                $unit = $importData[5];
                // $weight = (int)$importData[6];
                $lenght = $importData[6];
                $width = $importData[7];
                $height = $importData[8];
                $category_id = (int)$importData[9];
                $j++;

                    $product = new Product();
                    $product->name = $name;
                    $product->category_id = $category_id;
                    // $product->brand_id = $brand_id;
                    $product->has_variant = 0;
                    $product->berat = 0;
                    $product->berat_satuan = $unit;
                    $product->panjang = empty($lenght) ? 0 : $lenght;
                    $product->lebar = empty($width) ? 0 : $width;
                    $product->tinggi = empty($height) ? 0 : $height;
                    $product->save();

                    $variant = new ProductVariant();
                    $variant->sell_price = $sell_price;
                    $variant->purchase_price = $purchase_price;
                    $variant->sku = $sku;
                    $res = $product->variant()->save($variant);
                    
                    $stock_fil = new ProductStock();
                    $stock_fil->product_id = $res->id;
                    $stock_fil->variant_id = $res->product_id;
                    $stock_fil->stock = $stock;
                    $stock_fil->save();

                    // if($stock > 0){
                    //     $line = new PurchaseLine();
                    //     $line->purchase_id = 1;
                    //     $line->product_id = $product->id;
                    //     $line->variant_id = $res->id;
                    //     $line->unit_price = $purchase_price;
                    //     $line->net_price = $purchase_price;
                    //     $line->qty = $stock;
                    //     $line->discount_type = 'fixed';
                    //     $line->discount_value = 0;
                    //     $line->discount_amount = 0;
                    //     $line->sub_total = $purchase_price * $stock;
                    //     $line->save();
                    // }
            }

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }
        DB::commit();
        echo 'done';
    }

    
    
    private function uploadImage($file, $name, $id){
        $file_name = $name. '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $imgFile = Image::make($file->getRealPath());
        $destinationPath = 'public/uploads/product/images/'.$id;

        $imgFile->resize(800, 800, function ($constraint) {
		    $constraint->aspectRatio();
		})->encode('jpg', 80);

        
        Storage::disk('public')->putFileAs(
            'uploads/product/images/'.$id,
            $file,
            $file_name
        );
        
        return 'uploads/product/images/'.$id.'/'.$file_name;
    }

    private function deleteImage($files){
        $cek = Storage::disk('public')->exists($files);
        if($cek)
        {
            Storage::disk('public')->delete($files);
            return true;
        }
        return false;
    }
    
    public function data(Request $request)
    {
        $search = $request->search;
        $has_stock = $request->has_stock;
        $query = Product::with('variant')
        ->withCount([
            'stock' => function ($query) {
                $query->select(DB::raw("IFNULL(SUM(stock), 0)"));
            }
        ])
        ->whereHas('variant', function ($query) use ($search) {
            $query->where('sku', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('name', 'asc');

        $query->when(!empty($search), function ($q) use ($search) {
            return $q->orWhere('name', 'LIKE', '%' . $search . '%');
        }); 
        $query->when(request('has_stock') == 'true', function ($q) {
            return $q->having('stock_count', '>', 0);
        });

        // if($has_stock == true){
        //     $query->having('stock_count', '>', 0);
        // }
        // $query->when(($has_stock == true), function ($q) use ($has_stock) {
        //     return $q->having('stock_count', '>', 0);
        // }); 
            // dd('sad');
        $data = $query->paginate(8);
        if($data){
            return response()->json([
                'data' => $data,
                'fail' => false,
            ], 200);
        }else{
            return response()->json([
                'message' => "Product Not Found",
                'fail' => false,
            ], 400);

        }
    }
    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function updateStock(Request $request)
    {
        DB::beginTransaction();
        try{
            // dd($request->all());
            foreach($request->line as $line){
                $data = ProductStock::firstOrNew(['product_id' =>  $line['product_id'], 'variant_id' => $line['variant_id']]);
                $data->stock = (int)$line['stock'];
                $data->save();
                // dd($line);
            }
            
        }catch(\QueryException $e){
            DB::rollback();
            // return back();
            dd($e);
        }
        DB::commit();
        return redirect()->route('admin.product.index');
    }

}
