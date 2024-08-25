<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('clear_cache', function () {

    \Artisan::call('optimize:clear');

    dd("Cache is cleared");

});


Route::namespace('Frontend')->group(function(){
    Route::post('/payment-callback','CheckoutController@callbackPayment');
    Route::middleware('guest')->group(function () {

        Route::get('/','HomeController@index')->name('home');
        Route::get('/tentang','HomeController@about')->name('about');
        Route::get('/panduan','HomeController@tutorial')->name('tutorial');
        Route::get('/profil','HomeController@profil')->name('profil');
        Route::get('/sitemap.xml','HomeController@sitemap')->name('sitemap');

        Route::prefix('/produk')->name('product.')->group(function () {
            Route::get('/', 'ProductController@index')->name('index');
            Route::get('/data', 'ProductController@data')->name('data');
            Route::get('/{slug}', 'ProductController@show')->name('show');
        });
        
        Route::get('kategori/{category}', 'ProductController@category')->name('category');

        Route::namespace('Auth')->group(function () {
            Route::get('/login','LoginController@index')->name('login');
            Route::post('/login','LoginController@login');
            
            Route::get('/register','RegisterController@index')->name('register');
            Route::post('/register','RegisterController@register');
        });
    });

    Route::middleware('auth')->group(function () {
        Route::namespace('Auth')->group(function () {
            Route::get('/verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');
            
            Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
            
            Route::post('email/verification-notification','EmailVerificationNotificationController@store')
            ->middleware('throttle:6,1')
            ->name('verification.send');

            // Route::get('/register','RegisterController@index')->name('register');
            // Route::post('/register','RegisterController@register');
            
            Route::post('/logout','LoginController@logout')->name('logout');
        });

        
        Route::prefix('/cart')->name('cart.')->group(function () {
            Route::get('/', 'CartController@index')->name('index');
            Route::get('/data', 'CartController@data')->name('data');
            Route::post('/store','CartController@store')->name('store');
            Route::post('/update','CartController@update')->name('update');
            Route::delete('/{id}/delete','CartController@destroy')->name('delete');
        });

        
        Route::name('checkout.')->prefix('checkout')->group(function () {
            Route::match(['get', 'post'], '/shipment','CheckoutController@index')->name('shipping');
            Route::match(['get', 'post'], '/payment','CheckoutController@payment')->name('payment');
            Route::post('/ongkir','CheckoutController@ongkir')->name('ongkir');
            Route::post('/store','CheckoutController@store')->name('store');
            Route::post('/{id}/state','CheckoutController@state')->name('state');
        });
        
        Route::prefix('/user')->name('user.')->group(function () {
            Route::get('/', 'UserController@index')->name('profil');
            Route::post('/update','UserController@updateProfil')->name('update');
            Route::get('/password', 'UserController@password')->name('password');
            Route::post('/password','UserController@updatePassword');

            
            Route::prefix('/alamat')->name('address.')->group(function () {
                Route::get('/', 'UserAddressController@index')->name('index');
                Route::get('/create', 'UserAddressController@create')->name('create');
                Route::post('/store','UserAddressController@store')->name('store');
                Route::post('/json-store','UserAddressController@jsonStore')->name('json-store');
                Route::post('/geocode','UserAddressController@geocode')->name('geocode');
                Route::get('/data', 'UserAddressController@data')->name('data');
                Route::get('/main', 'UserAddressController@main')->name('main');
                Route::get('/{id}', 'UserAddressController@show')->name('show');
                Route::get('/{id}/edit','UserAddressController@edit')->name('edit');
                Route::post('/{id}/update','UserAddressController@update')->name('update');
                Route::delete('/{id}/delete','UserAddressController@destroy')->name('delete');
            });
            
            Route::prefix('/order')->name('order.')->group(function () {
                Route::get('/', 'UserOrderController@index')->name('index');
                Route::get('/payment', 'UserOrderController@payment')->name('payment');
                Route::post('/test', 'UserOrderController@test')->name('test');
                Route::get('/menunggu-pembayaran', 'UserOrderController@unpaid')->name('unpaid');
                Route::get('/data', 'UserOrderController@data')->name('data');
                Route::get('/{id}', 'UserOrderController@show')->name('show');
                Route::post('/{id}/state','UserOrderController@state')->name('state');
                Route::post('/{id}/confirm','UserOrderController@confirm')->name('confirm');
            });

            
            Route::prefix('/retur')->name('return.')->group(function () {
                Route::get('/', 'ReturnController@index')->name('index');
                Route::get('/create', 'ReturnController@create')->name('create');
                Route::post('/store','ReturnController@store')->name('store');
                Route::get('/data', 'ReturnController@data')->name('data');
                Route::get('/{id}', 'ReturnController@show')->name('show');
                Route::post('/{id}/edit','ReturnController@edit')->name('edit');
                Route::post('/{id}/delete','ReturnController@destroy')->name('delete');
            });

        });
    });

    
    Route::group(['prefix' => 'base', 'as'=>'base.'], function () {
        Route::get('/', 'BaseController@index')->name('index');
        Route::get('/level', 'BaseController@dukunganLevel')->name('level');
        Route::get('/wilayah', 'BaseController@wilayah')->name('wilayah');
        Route::get('/dapil', 'BaseController@dapil')->name('dapil');
    });

});


Route::prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::middleware('guest')->namespace('Auth')->group(function () {
        Route::get('/','LoginController@showLoginForm')->name('login');
        Route::post('/','LoginController@login');
    });
    

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout','Auth\LoginController@logout')->name('logout');

        Route::get('/dashboard','DashboardController@index')->name('dashboard');

        Route::group(['prefix' => 'base', 'as'=>'base.'], function () {
            Route::get('/', 'BaseController@index')->name('index');
            Route::get('/level', 'BaseController@dukunganLevel')->name('level');
            Route::get('/wilayah', 'BaseController@wilayah')->name('wilayah');
            Route::get('/dapil', 'BaseController@dapil')->name('dapil');
        });
        

        
        Route::namespace('Inventory')->name('inventory.')->group(function(){

            Route::prefix('/produk')->name('product.')->group(function () {
                Route::get('/', 'ProductController@index')->name('index');
                Route::get('/create', 'ProductController@create')->name('create');
                Route::post('/store','ProductController@store')->name('store');
                Route::get('/data', 'ProductController@data')->name('data');
                Route::get('/search', 'ProductController@search')->name('search');
                Route::get('/stock', 'ProductController@stock')->name('stock');
                Route::get('/{id}', 'ProductController@show')->name('show');
                Route::get('/{id}/edit','ProductController@edit')->name('edit');
                Route::post('/{id}/update','ProductController@update')->name('update');
                Route::delete('/{id}/delete','ProductController@destroy')->name('delete');
            });
            
            Route::prefix('/unit')->name('unit.')->group(function () {
                Route::get('/', 'UnitController@index')->name('index');
                Route::get('/create', 'UnitController@create')->name('create');
                Route::post('/store','UnitController@store')->name('store');
                Route::get('/data', 'UnitController@data')->name('data');
                Route::get('/{id}', 'UnitController@show')->name('show');
                Route::get('/{id}/edit','UnitController@edit')->name('edit');
                Route::post('/{id}/update','UnitController@update')->name('update');
                Route::delete('/{id}/delete','UnitController@destroy')->name('delete');
            });
            
            Route::prefix('/kategori')->name('category.')->group(function () {
                Route::get('/', 'CategoryController@index')->name('index');
                Route::get('/create', 'CategoryController@create')->name('create');
                Route::post('/store','CategoryController@store')->name('store');
                Route::get('/data', 'CategoryController@data')->name('data');
                Route::get('/{id}', 'CategoryController@show')->name('show');
                Route::get('/{id}/edit','CategoryController@edit')->name('edit');
                Route::post('/{id}/update','CategoryController@update')->name('update');
                Route::delete('/{id}/delete','CategoryController@destroy')->name('delete');
            });
            
            Route::prefix('/merk')->name('brand.')->group(function () {
                Route::get('/', 'BrandController@index')->name('index');
                Route::get('/create', 'BrandController@create')->name('create');
                Route::post('/store','BrandController@store')->name('store');
                Route::get('/data', 'BrandController@data')->name('data');
                Route::get('/{id}', 'BrandController@show')->name('show');
                Route::get('/{id}/edit','BrandController@edit')->name('edit');
                Route::post('/{id}/update','BrandController@update')->name('update');
                Route::delete('/{id}/delete','BrandController@destroy')->name('delete');
            });

        });

        Route::namespace('Purchase')->name('purchase.')->group(function(){

            Route::prefix('/supplier')->name('supplier.')->group(function () {
                Route::get('/', 'SupplierController@index')->name('index');
                Route::get('/create', 'SupplierController@create')->name('create');
                Route::post('/store','SupplierController@store')->name('store');
                Route::get('/data', 'SupplierController@data')->name('data');
                Route::get('/produk', 'SupplierController@product')->name('product');
                Route::get('/{id}', 'SupplierController@show')->name('show');
                Route::get('/{id}/edit','SupplierController@edit')->name('edit');
                Route::post('/{id}/update','SupplierController@update')->name('update');
                Route::delete('/{id}/delete','SupplierController@destroy')->name('delete');
            });

            Route::prefix('/pembelian')->name('order.')->group(function () {
                Route::get('/', 'OrderController@index')->name('index');
                Route::get('/create', 'OrderController@create')->name('create');
                Route::post('/store','OrderController@store')->name('store');
                Route::get('/data', 'OrderController@data')->name('data');
                Route::get('/report', 'OrderController@report')->name('report');
                Route::get('/{id}', 'OrderController@show')->name('show');
                Route::get('/{id}/edit','OrderController@edit')->name('edit');
                Route::get('/{id}/print', 'OrderController@print')->name('print');
                Route::post('/{id}/update','OrderController@update')->name('update');
                Route::post('/{id}/state','OrderController@state')->name('state');
                Route::delete('/{id}/delete','OrderController@destroy')->name('delete');
            });

        });

        
        Route::namespace('Sale')->name('sale.')->group(function(){

            Route::prefix('/pelanggan')->name('customer.')->group(function () {
                Route::get('/', 'CustomerController@index')->name('index');
                Route::get('/create', 'CustomerController@create')->name('create');
                Route::post('/store','CustomerController@store')->name('store');
                Route::get('/data', 'CustomerController@data')->name('data');
                Route::get('/{id}', 'CustomerController@show')->name('show');
                Route::get('/{id}/edit','CustomerController@edit')->name('edit');
                Route::post('/{id}/update','CustomerController@update')->name('update');
                Route::delete('/{id}/delete','CustomerController@destroy')->name('delete');
            });

            Route::prefix('/penjualan')->name('order.')->group(function () {
                Route::get('/', 'OrderController@index')->name('index');
                Route::get('/create', 'OrderController@create')->name('create');
                Route::post('/store','OrderController@store')->name('store');
                Route::get('/data', 'OrderController@data')->name('data');
                Route::get('/report', 'OrderController@report')->name('report');
                Route::get('/{id}', 'OrderController@show')->name('show');
                Route::get('/{id}/edit','OrderController@edit')->name('edit');
                Route::post('/{id}/update','OrderController@update')->name('update');
                Route::post('/{id}/state','OrderController@state')->name('state');
                Route::delete('/{id}/delete','OrderController@destroy')->name('delete');
            });

            
            Route::prefix('/retur')->name('return.')->group(function () {
                Route::get('/', 'ReturnController@index')->name('index');
                Route::get('/create', 'ReturnController@create')->name('create');
                Route::post('/store','ReturnController@store')->name('store');
                Route::get('/data', 'ReturnController@data')->name('data');
                Route::get('/report', 'ReturnController@report')->name('report');
                Route::get('/{id}', 'ReturnController@show')->name('show');
                Route::get('/{id}/edit','ReturnController@edit')->name('edit');
                Route::post('/{id}/update','ReturnController@update')->name('update');
                Route::post('/{id}/state','ReturnController@state')->name('state');
                Route::delete('/{id}/delete','ReturnController@destroy')->name('delete');
            });

        });

        
        Route::prefix('/pengguna')->name('user.')->group(function () {
            Route::get('/', 'UserController@index')->name('index');
            Route::get('/create', 'UserController@create')->name('create');
            Route::post('/store','UserController@store')->name('store');
            Route::get('/data', 'UserController@data')->name('data');
            Route::get('/{id}', 'UserController@show')->name('show');
            Route::get('/{id}/edit','UserController@edit')->name('edit');
            Route::post('/{id}/update','UserController@update')->name('update');
            Route::delete('/{id}/delete','UserController@destroy')->name('delete');
        });

    });
});