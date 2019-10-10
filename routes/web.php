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

    $trans = DB::table('ltm_translations')->select('locale')->distinct()->get();
    return view('welcome')->with('trans', $trans);
});

Route::post('update', 'LanguageController@postUpdate')->name('postUpdate');
Route::get('update/{locale}', 'LanguageController@update')->name('update');
Route::post('insert', 'LanguageController@postInsert')->name('postInsert');
