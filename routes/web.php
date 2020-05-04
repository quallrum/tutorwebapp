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
	Route::post('/editPassword', 'HomeController@editPassword')->name('edit.password');
	Route::post('/editFullname', 'HomeController@editFullname')->name('edit.fullname');
	Route::post('/editTelegram', 'HomeController@editTelegram')->name('edit.telegram');
	Route::name('journal.')->prefix('/journal')->group(function(){
		Route::get('/', 'JournalController@group')->name('group');
		Route::get('/{group}', 'JournalController@subject')->name('subject');
		Route::get('/{group}/{subject}', 'JournalController@show')->name('show');
		Route::post('/{group}/{subject}', 'JournalController@update');
		Route::get('/{group}/{subject}/file', 'JournalController@file')->name('file');
	});
	Route::name('mark.')->prefix('/mark')->group(function(){
		Route::get('/', 'MarkController@group')->name('group');
		Route::get('/{group}', 'MarkController@subject')->name('subject');
		Route::get('/{group}/{subject}', 'MarkController@show')->name('show');
		Route::post('/{group}/{subject}', 'MarkController@update');
	});
	Route::resource('group', 'GroupController')->except(['show']);
	Route::prefix('/group/{group}')->name('group.')->group(function(){
		Route::post('/tutors', 'GroupController@subjectTutors')->name('tutors');
		Route::put('/subject', 'GroupController@attachSubject')->name('subject');
		Route::delete('/subject', 'GroupController@detachSubject');
		Route::post('/email', 'GroupController@updateEmail')->name('email');
		Route::post('/password', 'GroupController@updatePassword')->name('password');
		Route::post('/accounts', 'GroupController@updateAccounts')->name('accounts');
	});
	Route::prefix('/role')->name('role.')->group(function(){
		Route::get('/', 'RoleController@index')->name('index');
		Route::post('/', 'RoleController@role')->name('role');
	});
	Route::resource('subject', 'SubjectController')->except(['show']);
	Route::prefix('/subject/{subject}')->name('subject.')->group(function(){
		Route::put('/tutor', 'SubjectController@attachTutor')->name('tutor');
		Route::delete('/tutor', 'SubjectController@detachTutor');
	});
});

