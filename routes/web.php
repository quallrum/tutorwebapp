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
	Route::post('/editFullname', 'HomeController@editFullname')->name('edit.fullname');
	Route::post('/editEmail', 'HomeController@editEmail')->name('edit.email');
	Route::post('/editPassword', 'HomeController@editPassword')->name('edit.password');
	Route::name('journal.')->prefix('/journal')->group(function(){
		Route::get('/', 'JournalController@group')->name('group');
		Route::get('/{group}', 'JournalController@subject')->name('subject');
		Route::get('/{group}/{subject}', 'JournalController@show')->name('show');
		Route::post('/{group}/{subject}', 'JournalController@update');
	});
	Route::resource('group', 'GroupController')->except(['show']);
	Route::prefix('/group/{group}')->name('group.')->group(function(){
		Route::post('/subjectTutors', 'GroupController@subjectTutors')->name('subjectTutors');
		Route::post('/addSubject', 'GroupController@addSubject')->name('addSubject');
		Route::post('/deleteSubject', 'GroupController@deleteSubject')->name('deleteSubject');
		Route::post('/email', 'GroupController@updateEmail')->name('email');
		Route::post('/password', 'GroupController@updatePassword')->name('password');
		Route::post('/accounts', 'GroupController@updateAccounts')->name('accounts');
	});
	Route::prefix('/roles')->name('roles.')->group(function(){
		Route::get('/', 'RolesController@index')->name('index');
		Route::post('/role', 'RolesController@role')->name('role');
	});
	Route::resource('subject', 'SubjectController')->except(['show']);
	Route::prefix('/subject/{subject}')->name('subject.')->group(function(){
		Route::post('/addTutor', 'SubjectController@addTutor')->name('addTutor');
		Route::post('/removeTutor', 'SubjectController@removeTutor')->name('removeTutor');
	});
});

