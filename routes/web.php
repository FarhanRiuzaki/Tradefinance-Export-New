<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => false]);
Route::get('/', 'PagesController@index');

// Login Device
Route::get('/logouts/all', 'Auth\LoggedInDeviceManager@logoutAllDevices')
		->name('logged-in-devices.logoutAll')
		->middleware('auth');
Route::get('/logouts/{device_id}', 'Auth\LoggedInDeviceManager@logoutDevice')
		->name('logged-in-devices.logoutSpecific')
        ->middleware('auth');

// Demo routes
Route::get('/datatables', 'PagesController@datatables');
Route::get('/ktdatatables', 'PagesController@ktDatatables');
Route::get('/select2', 'PagesController@select2');
Route::get('/jquerymask', 'PagesController@jQueryMask');
Route::get('/icons/custom-icons', 'PagesController@customIcons');
Route::get('/icons/flaticon', 'PagesController@flaticon');
Route::get('/icons/fontawesome', 'PagesController@fontawesome');
Route::get('/icons/lineawesome', 'PagesController@lineawesome');
Route::get('/icons/socicons', 'PagesController@socicons');
Route::get('/icons/svg', 'PagesController@svg');

// Quick search dummy route to display html elements in search dropdown (header search)
Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');

// API Controller, for custom data
Route::post('/upload-media'  ,'ApiController@uploadMedia')->name('apis.uploadMedia');
Route::get('/dt-user'    ,'ApiController@DTUser')->name('apis.DTUser');
Route::get('/data-cif'    ,'ApiController@dataCif')->name('apis.dataCif');
Route::get('/document-parameter'    ,'ApiController@documentParameter')->name('apis.documentParameter');

Route::get('/pdf-notification-letter/{id}'    ,'ApiController@generateNotificationLetter')->name('apis.notificationLetter');
Route::get('/pdf-nota-debet/{id}'    ,'ApiController@generateNotaDebet')->name('apis.notaDebet');

// Custom Route [GET, POST, PATCH] taro disini
Route::get('permissions/datatable','PermissionController@datatable')->name('permissions.datatable');
Route::get('users/update-status/{id}','UserController@updateStatus')->name('users.updateStatus'); //update status user
Route::get('advice-makers/index-summary'    ,'Transaction\AdviceMakerController@indexSummary')->name('advice-makers.indexSummary');
Route::get('advice-makers/edit-summary/{id}'    ,'Transaction\AdviceMakerController@editSummary')->name('advice-makers.editSummary');

// Master Application Route [Resource]
Route::resource('users','UserController');
Route::resource('roles','RoleController');
Route::resource('permissions','PermissionController');
Route::resource('audits','AuditTrailController');
Route::resource('bugs','BugController');

// Custom
Route::resource('products','ProductController');

// Master Data Tradefinance Export
Route::resource('flags','Master\MasterFlagController');
Route::resource('branchs','Master\MasterBranchController');
Route::resource('currencies','Master\MasterCurrencyController');
Route::resource('parameter-documents','Master\MasterParameterDocumentController');
Route::resource('master-products','Master\MasterProductController');

// Custom Route [GET, POST, PATCH] taro disini
Route::get('advice-makers/datatable','Transaction\AdviceMakerController@resource')->name('advice-makers.datatable');
Route::get('upload-documents/sor/{id}','Transaction\UploadDocumentController@indexSor')->name('upload-documents.indexSor');

// Route Transaksi
Route::resource('advice-makers','Transaction\AdviceMakerController');
Route::resource('advice-approvers','Transaction\AdviceApproverController');
Route::resource('upload-documents','Transaction\UploadDocumentController');
