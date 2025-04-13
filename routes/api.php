<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SalesController;



Route::get('/invoices', [InvoiceController::class, 'index']);
Route::post('/invoices', [InvoiceController::class, 'store']);

Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);

Route::post('sales', [SalesController::class, 'store']);
Route::get('/getLatestInvoiceNumber', [SalesController::class, 'getLatestInvoiceNumber']);
Route::get('GetCustomer_sales','SalesController@index');  // Store sale

Route::get('GetProduct_sales','SalesController@getproductsales');  // Store sale

Route::get('/search-invoices', [SalesController::class, 'searchInvoices']);
Route::get('/print-invoice/{invoiceNumber}', [SalesController::class, 'printInvoice']);