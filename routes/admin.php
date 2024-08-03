<?php 

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){

    // Route::get('/', function () {
    //     dd(request()->route()->getPrefix());
    // });

    // Route::get('/coba/admin', function () {
    //     dd(request()->route()->getPrefix());
    // });

    Route::namespace('Auth')->group(function(){
        Route::get('/','LoginController@showLoginForm');
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('loginPost');
        Route::post('/logout','LoginController@logout')->name('logout');
    });

    // Route::group(['middleware' => 'auth:admin'], function () {

    // });


});