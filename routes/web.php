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


Auth::routes([
	'verify'    => true,
	'confirm'   => false,
]);

Route::group([
	'middleware'    => ['auth', 'verified']
], function(){
	Route::get('/', 'HomeController@index')->name('home');
	Route::post('/editEmail', 'HomeController@editEmail')->name('edit.email');
	Route::name('journal.')->prefix('/journal')->group(function(){
		Route::get('/', 'JournalController@group')->name('group');
		Route::get('/{group}', 'JournalController@subject')->name('subject');
		Route::get('/{group}/{subject}', 'JournalController@show')->name('show');
		Route::post('/{group}/{subject}', 'JournalController@update');
	});
	Route::resource('group', 'GroupController');
});

