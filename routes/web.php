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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oldwelcome', function () {
    return view('oldwelcome');
});

Auth::routes();

// Home Route
Route::get('/businesses/{business}/home', 'HomeController@index')->name('home');

// Docs Route
Route::get('/businesses/{business}/docs', 'DocsController@index');

// Logout Routes
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Businesses Routes
Route::get('businesses/{business}/docsnumberings', 'BusinessesController@docsNumbering');
Route::post('businesses/{business}/docsnumberings', 'BusinessesController@storeDocsNumbering');
Route::resource('businesses', 'BusinessesController');

// Customers Routes
Route::resource('businesses/{business}/customers', 'CustomersController');

// Products Routes
Route::resource('businesses/{business}/products', 'ProductsController');

// MixedInvoices Routes
Route::resource('mixedinvoices', 'MixedInvoicesController');

// Invoices Routes
Route::get('businesses/{business}/invoices/create/{invoiceType}', 'InvoicesController@create');
Route::post('businesses/{business}/invoices/{invoiceType}', 'InvoicesController@store');
Route::resource('businesses/{business}/invoices', 'InvoicesController');

// Receipts Routes
Route::resource('businesses/{business}/receipts', 'ReceiptsController');

// Reports Routes
Route::get('businesses/{business}/reports', 'ReportsController@index');

Route::get('businesses/{business}/reports/VAT', 'ReportsController@VAT');
Route::post('businesses/{business}/reports/VAT', 'ReportsController@VATsortbyDate');

Route::get('businesses/{business}/reports/income', 'ReportsController@income');
Route::post('businesses/{business}/reports/income', 'ReportsController@incomeSortByDate');





// Test Routes
Route::resource('tests', 'TestsController');

// SetUp
Route::get('setup/currencies', 'SetupController@currencies');

