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

    $trans = DB::table('ltm_translations')->where('parent_id','<>',0)->select('locale')->distinct()->get();
    return view('welcome')->with('trans', $trans);
});

Route::get('keyword_manage', 'LanguageController@keyword_manage')->name('keyword_manage');
Route::post('update', 'LanguageController@postUpdate')->name('postUpdate');
Route::post('updateKey', 'LanguageController@updateKey')->name('updateKey');
Route::post('delete', 'LanguageController@delete')->name('delete');
Route::get('update/{locale}', 'LanguageController@update')->name('update');
Route::post('insert', 'LanguageController@postInsert')->name('postInsert');
Route::post('addKeyword', 'LanguageController@addKeyword')->name('addKeyword');
Route::post('search', 'LanguageController@postSearch')->name('postSearch');
Route::post('postSearchKey', 'LanguageController@postSearchKey')->name('postSearchKey');
Route::get('search', 'LanguageController@search')->name('search');

Route::post('/autocomplete/fetch', 'LanguageController@fetch')->name('autocomplete.fetch');
