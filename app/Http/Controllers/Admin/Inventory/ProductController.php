<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;


use App\Models\Inventory\Product;
use App\Models\Inventory\ProductImage;
use Image;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Inventory/Product/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Inventory/Product/Form');
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
            'name' => 'required',
            'category_id' => 'required',
        ];
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
                // dd($request->all());
                $product = new Product();
                $product->nama = $request->name;
                $product->deskripsi = $request->description;
                $product->kategori_id = $request->category_id;
                $product->merk_id = $request->brand_id;
                $product->harga_jual = $request->sell_price;
                $product->harga_beli = $request->purchase_price;
                $product->sku = $request->sku;
                $product->save();


                if(count($request->image)){
                    foreach($request->image as $d){
                        $img = new ProductImage();
                        $img->produk_id = $product->id;
                        $img->path = $this->uploadImage($d['raw'], Str::slug($request->name, '-'), $product->id);
                        $img->save();
                    }
                }
            }catch(\QueryException $e){
                dd($e);
                DB::rollback();
            }
            DB::commit();
            return redirect()->route('admin.inventory.product.index');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Product::with(['image'])->where('id', $id)->first();

        
        return Inertia::render('Inventory/Product/Form',[
            'data' => $data
        ]);
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
        $rules = [
            'name' => 'required',
            'category_id' => 'required',
        ];
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
                $product = Product::where('id', $id)->first();
                $product->nama = $request->name;
                $product->deskripsi = $request->description;
                $product->kategori_id = $request->category_id;
                $product->merk_id = $request->brand_id;
                $product->harga_jual = $request->sell_price;
                $product->harga_beli = $request->purchase_price;
                $product->sku = $request->sku;
                $product->save();

                if(count($request->image)){
                    foreach($request->image as $d){
                        if($d['status'] != "success"){
                            $img = new ProductImage();
                            $img->produk_id = $product->id;
                            $img->path = $this->uploadImage($d['raw'], Str::slug($request->name, '-'), $product->id);
                            $img->save();
                        }
                    }

                    foreach($request->imageDeleted as $d){
                        $img = ProductImage::where('path', $d['url'])->delete();
                    }
                }
            }catch(\QueryException $e){
                dd($e);
                DB::rollback();
            }
            DB::commit();
            return redirect()->route('admin.inventory.product.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $product = Product::where('id', $id)->first();
            $variant = ProductImage::where('produk_id', $id)->latest()->get();

            foreach($variant as $v)
            {
                if(Storage::disk('public')->exists($v->path)){
                    Storage::disk('public')->delete($v->path);
                }
                $v->delete();
            }
            $product->delete();

        }catch(\QueryException $e){
            dd($e);
            DB::rollback();
        }
        DB::commit();
        return redirect()->route('admin.inventory.product.index');
    }

    private function removeImage($path)
    {

    }

    private function uploadImage($file, $name, $id){
        $file_name = $name. '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $imgFile = Image::make($file->getRealPath());
        $destinationPath = 'public/product/'.$id;

        $imgFile->resize(800, 800, function ($constraint) {
		    $constraint->aspectRatio();
		})->encode('jpg', 80);

        
        Storage::disk('public')->putFileAs(
            'product/'.$id,
            $file,
            $file_name
        );
        
        return '/uploads/product/'.$id.'/'.$file_name;
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $query = Product::with(['category:id,nama'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->orWhere('nama', 'LIKE', '%' . $search . '%');
        })
        ->orderBy($sort, $sortDir);

        if($page){
            $data = $query->paginate($limit);
        }else{
            $data = $query->get();
        }
        return response()->json($data);
    }

    
    public function search(Request $request)
    {
        $search = $request->q;

        $data = Product::where(function($query) use ($search) {
            return $query->where('nama', 'LIKE', '%' . $search . '%');
        })
        
        ->get();

        return response()->json($data);
    }

    public function stock()
    {

        $data = Product::orderBy('stok', 'ASC')->limit(10)
        ->get();
        // dd($data);
        return response()->json($data);
    }

}
