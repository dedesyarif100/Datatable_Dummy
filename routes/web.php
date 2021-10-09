<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\CountriesController;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/home', function () {
//     return view('home');
// })->middleware(['auth'])->name('home');

// require __DIR__.'/auth.php';

Route::get('data.index', [DataController::class, 'index'])->name('data');

Route::get('/countries-list', [CountriesController::class,'index'])->name('countries.list');
Route::post('/add-country', [CountriesController::class,'addCountry'])->name('add.country');
Route::get('/getCountriesList', [CountriesController::class, 'getCountriesList'])->name('get.countries.list');
Route::post('/getCountryDetails',[CountriesController::class, 'getCountryDetails'])->name('get.country.details');
Route::post('/updateCountryDetails',[CountriesController::class, 'updateCountryDetails'])->name('update.country.details');
Route::post('/deleteCountry',[CountriesController::class, 'deleteCountry'])->name('delete.country');
