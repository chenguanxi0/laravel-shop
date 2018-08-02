<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'PagesController@root')->name('root');

Auth::routes();
Route::get('/test',function (){

});
Route::group(['middleware' => 'auth'],function (){
    //邮箱验证提示
    Route::get('/email_verify_notice','PagesController@emailVerifyNotice')->name('email_verify_notice');
    //验证 邮箱链接
    Route::get('/email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify');
    //发送邮件
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send');

    //邮箱验证通过的路由组
    Route::group(['middleware' => 'email_verified'],function (){
        //地址相关
        Route::get('user_addresses','UserAddressesController@index')->name('user_addresses.index');
        Route::get('user_addresses/create','UserAddressesController@create')->name('user_addresses.create');
        Route::post('user_addresses','UserAddressesController@store')->name('user_addresses.store');
        Route::get('user_addresses/{user_address}','UserAddressesController@edit')->name('user_addresses.edit');
        Route::put('user_addresses/{user_address}','UserAddressesController@update')->name('user_addresses.update');
        Route::delete('user_addresses/{user_address}','UserAddressesController@destroy')->name('user_addresses.destroy');
        //产品相关
        Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
        Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
        //购物车相关
        Route::post('cart','CartController@add')->name('cart.add');
        Route::get('cart','CartController@index')->name('cart.index');
        Route::delete('cart/{productSku}','CartController@remove')->name('cart.remove');
        //订单相关
        Route::post('orders', 'OrdersController@store')->name('orders.store');
        Route::get('orders', 'OrdersController@index')->name('orders.index');
        Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    });
});
Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');
