<?php

use Illuminate\Http\Request;

/***Buyer***/
Route::resource('buyers','Buyer\BuyerController',['only'=>['index','show']]);
Route::resource('buyers.transactions','Transaction\BuyerTransactionController',['only'=>'index']);
Route::resource('buyers.products','Buyer\BuyerProductController',['only'=>'index']);
Route::resource('buyers.sellers','Buyer\BuyerSellerController',['only'=>'index']);
Route::resource('buyers.categories','Buyer\BuyerCategoryController',['only'=>'index']);


/***Seller***/
Route::resource('sellers','Seller\SellerController',['only'=>['index','show']]);
Route::resource('sellers.transactions','Seller\SellerTransactionController',['only'=>'index']);
Route::resource('sellers.categories','Seller\SellerCategoryController',['only'=>'index']);
Route::resource('sellers.buyers','Seller\SellerBuyerController',['only'=>'index']);
Route::resource('sellers.products','Seller\SellerProductController',['except'=> ['show','edit','create']]);


/***Category***/
Route::resource('categories','Category\CategoryController',['except'=>['create','edit']]);
Route::resource('categories.products','Category\CategoryProductController',['only'=>'index']);
Route::resource('categories.sellers','Category\CategorySellerController',['only'=>'index']);
Route::resource('categories.transactions','Category\CategoryTransactionController',['only'=>'index']);
Route::resource('categories.buyers','Category\CategoryBuyerController',['only'=>'index']);

/***Product***/
Route::resource('products','Product\ProductController',['only'=>['index','show']]);
Route::resource('products.transactions','Product\ProductTransactionController',['only'=>'index']);
Route::resource('products.buyers','Product\ProductBuyerController',['only'=>'index']);
Route::resource('products.categories','Product\ProductCategoryController',['except'=>['create','edit','show']]);
Route::resource('products.buyers.transactions','Product\ProductBuyerTransactionController',['only'=>'store']);




/***Transaction***/
Route::resource('transactions','Transaction\TransactionController',['only'=>['index','show']]);
Route::resource('transactions.categories','Transaction\TransactionCategoryController',['only'=>'index']);
Route::resource('transactions.sellers','Transaction\TransactionSellerController',['only'=>'index']);



/***User***/
Route::resource('users','User\UserController',['except'=>['create','edit']]);
Route::name('verify')->get('users/verify/{token}','User\UserController@verify');
Route::name('resend')->get('users/{user}/resend','User\UserController@resend');
Route::post('oauth/token','\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken ');