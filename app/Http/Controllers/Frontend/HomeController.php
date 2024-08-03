<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Helpers\Collection;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use App\Models\Inventory\Product;
use App\Models\Inventory\ProductCategory;

use App\Models\Landing\Video;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
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
        ->orderBy($sort, $sortDir)->limit(12)->get();


        $category = ProductCategory::withCount('product')->orderBy('nama', 'ASC')->get();

        return Inertia::render('Home',[
            'data' => $data,
            'category' => $category
        ]);
    }

    
    public function about()
    {
        return Inertia::render('Pages/About');
    }

    
    public function tutorial()
    {
        return Inertia::render('Pages/Tutorial');
    }

    
    public function sitemap()
    {

        $video = Video::orderBy('id', 'DESC')->get();

        return response()->view('sitemap', [
            'video' => $video
        ])->header('Content-Type', 'text/xml');
    }
}
