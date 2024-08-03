<?php

namespace App\Models\Inventory;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;
class Product extends Model
{
    use HasSlug;
    
    // Cache Duration
    public $cacheFor = 3600;

    protected $table = 'produk';


    protected $fillable = [
        'name', 'slug', 'kategori_id', 'has_variasi', 'var1_nama', 'var2_nama', 'var1_value', 'var2_value', 'harga',
        'berat',
    ];

    protected $appends = [
        'main_image', 
        // 'sell_price', 'sell_max_price', 'purchase_price', 'purchase_max_price'
    ];

    
    public $sortable = ['id', 'nama', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'kategori_id');
    }

    public function image()
    {
        return $this->hasMany(ProductImage::class, 'produk_id');
    }

    public function sale()
    {
        return $this->hasOne(SaleLine::class, 'product_id');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'product_id');
    }

    

    public function getPurchasePriceAttribute()
    {
        $variant = $this->hasMany(ProductVariant::class, 'product_id');
        if($variant->count() > 1)
        {
            return (int)$variant->min('purchase_price');

        }else{
            return (int)$variant->first()->purchase_price;
        }
    }

    public function getSellPriceAttribute()
    {
        $variant = $this->hasMany(ProductVariant::class, 'product_id');
        if($variant->count() > 1)
        {
            return (int)$variant->min('sell_price');

        }else{
            return (int)$variant->first()->sell_price;
        }
    }

    public function getSellMaxPriceAttribute()
    {
        $variant = $this->hasMany(ProductVariant::class, 'product_id');
        if($variant->count() > 1)
        {
            return (int)$variant->max('sell_price');
        }else{
            return (int)$variant->first()->sell_price;
        }
    }
    
    public function getPurchaseMaxPriceAttribute()
    {
        $variant = $this->hasMany(ProductVariant::class, 'product_id');
        if($variant->count() > 1)
        {
            return (int)$variant->max('purchase_price');
        }else{
            return (int)$variant->first()->purchase_price;
        }
    }
    public function getMainImageAttribute() {
        $get = $this->image()->first();
        if($get && file_exists( public_path() . '/' . $get->path) && $get->path)
        {
            return $get->path;
        }else{
            return '/media/placeholder/product.png';
        }
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama')
            ->saveSlugsTo('slug');
    }
    

}
