<?php

use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ViewCerticateController;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
Route::get('/', 'App\Http\Controllers\MainController@userView');
Route::get('pdf', [PdfController::class, 'index']);
Route::get('/admin/dashboard', 'App\Http\Controllers\MainController@dashboard');
Route::post('/addtraining', 'App\Http\Controllers\MainController@addtraining');
Route::get('/admin/delete/{id}', 'App\Http\Controllers\MainController@deletetraining');
Route::get('/view/{id}', 'App\Http\Controllers\MainController@viewtraining');
Route::get('pdf', 'App\Http\Controllers\PdfController@index');
Route::get('/admin/view/certificate/{id}', 'App\Http\Controllers\PdfController@view');
Route::get('admin/edit/{id}', 'App\Http\Controllers\MainController@edittraining');
Route::post('/updatetraining/{id}', 'App\Http\Controllers\MainController@updatetraining')->name('update.training');

Route::get('/admin/login', 'App\Http\Controllers\LoginController@index');
Route::post('/admin/check', 'App\Http\Controllers\LoginController@checkLogin');
Route::get('/admin/dashboard', 'App\Http\Controllers\LoginController@successLogin');
Route::get('/admin/logout', 'App\Http\Controllers\LoginController@logout');
Route::post('/admin/searchCert', 'App\Http\Controllers\MainController@searchCert');
Route::post('/user/searchCert', 'App\Http\Controllers\MainController@searchUserCert');
Route::get('/view/certificate/{id}', 'App\Http\Controllers\PdfController@view');

Route::post('/view/{id}/generate/create', 'App\Http\Controllers\PdfController@store')->name('generate');
Route::get('/view/certificate/{id}', [ViewCerticateController::class, 'view']);

Route::get('/download/certificate/{id}', [DownloadPdfController::class, 'view']);

Route::get('/admin/forgot_password', [ForgotPasswordController::class, 'index']);
Route::post('/admin/forgot_password/reset', [ForgotPasswordController::class, 'password']);
Route::get('/admin/forgot_password/reset/form', [ForgotPasswordController::class, 'resetPass']);
Route::post('/admin/forgot_password/reset/change', [ForgotPasswordController::class, 'resetPassword']);

Route::post('/view/resend', [PdfController::class, 'resend'])->name('resend');