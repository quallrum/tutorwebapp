<?php

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


Auth::routes(['verify' => true]);

Route::group([
    'middleware'    => ['auth', 'verified']
], function(){
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/journal/{group}/{subject}', 'JournalController@show')->name('journal');
    // Route::name('group.')->prefix('/group')->group(function(){
        // Route::get('/{id}', 'GroupController@show')->name('show');
        // Route::get('/{id}/edit', 'GroupController@edit')->name('edit');
        // Route::post('/{id}/edit', 'GroupController@save');
        Route::resource('group', 'GroupController');
    // });
});

