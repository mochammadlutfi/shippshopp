<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $data = Product::with(['category:id,nama'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->orWhere('nama', 'LIKE', '%' . $search . '%');
        })
        ->orderBy($sort, $sortDir)->paginate(12);


        $category = ProductCategory::withCount('product')->orderBy('nama', 'ASC')->get();

        return Inertia::render('Product/Index',[
            'data' => $data,
            'category' => $category
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Request $request)
    {
        $data = Product::with(['image'])
        ->where('slug', $slug)->first();

        return Inertia::render('Product/Show', [
            'data' => $data,
            'grosir' => $request->grosir,
        ]);
    }

    
    public function category($category)
    {
        $data = ProductCategory::where('slug', $category)->first();

        $product = Product::with(['variant', 'category:id,name'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->orWhere('name', 'LIKE', '%' . $search . '%');
        })
        ->where('category_id', $data->id)
        ->orderBy('id', 'DESC')->paginate(12);

        $category = ProductCategory::withCount('product')->orderBy('name', 'ASC')->get();

        return Inertia::render('Product/Category', [
            'data' => $data,
            'product' => $product,
            'category' => $category,
        ]);
    }
    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $page = $request->page;

        $query = Product::with(['variant', 'category:id,name'])
        ->when(!empty($request->search), function ($q, $search) {
            return $q->orWhere('name', 'LIKE', '%' . $search . '%');
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
