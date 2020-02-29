<?php

Route::get('/', function() {
	return view('pages.user.home');
});
Route::get('/', 'FrontController@index');
Route::get('/allvicrow', 'FrontController@allvicrow');
Route::get('/allnews', 'FrontController@allnews');
Route::get('/news/{id}', 'FrontController@news');
Route::get('/vview/{id}', 'FrontController@vview');
Route::get('/tdstep', 'FrontController@fullpage');
Route::get('/tdstep2', 'FrontController@fullpage2');
Route::post('/line-notify', 'FrontController@lineNotify');
Route::get('/live', 'FrontController@liveball');
Route::get('/lotto', 'FrontController@lotto');
Route::post('/lotto', 'FrontController@check_lotto');

// Route::get('/tded', function () {
//     return view('pages.user.tded');
// });
// Route::get('/step', function () {
//     return view('pages.user.step');
// });
// Route::get('/admin', function () {
//     return redirect()->guest('login');
// });
// Auth::routes();
// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('table-list', function () {
// 		return view('pages.table_list');
// 	})->name('table');
// 	Route::get('typography', function () {
// 		return view('pages.typography');
// 	})->name('typography');
// 	Route::get('icons', function () {
// 		return view('pages.icons');
// 	})->name('icons');
// 	Route::get('map', function () {
// 		return view('pages.map');
// 	})->name('map');
// 	Route::get('notifications', function () {
// 		return view('pages.notifications');
// 	})->name('notifications');
// 	Route::get('rtl-support', function () {
// 		return view('pages.language');
// 	})->name('language');
// 	Route::get('upgrade', function () {
// 		return view('pages.upgrade');
// 	})->name('upgrade');
// });
// Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
// Route::get('/zuser', function () {
//     return 'zuser';
// });
// Route::resource('tstep', 'TstepController', ['except' => ['show']]);
// Route::group(['middleware' => ['admin']], function(){
//     Route::resource('ztstep', 'ZeanTstepContrller', ['except' => ['show']]);
//     Route::resource('blogs', 'BlogController', ['except' => ['show']]);
//     Route::resource('youtube', 'YoutubeController', ['except' => ['show']]);
//     Route::resource('analyze', 'AnalyzeController', ['except' => ['show']]);
//     Route::resource('zean', 'ZeanController', ['except' => ['show']]);
//     Route::resource('user', 'UserController', ['except' => ['show']]);
//     Route::resource('setup', 'SetupController', ['except' => ['show']]);
// 	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
// 	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
//     Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
//     Route::get('ckeditor', 'CkeditorController@index');
//     Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');
// });
// Route::group(['prefix' => 'admin'], function(){
//     Route::group(['middleware' => ['admin']], function(){
//         Route::get('/dashboard', 'admin\AdminController@index');
//     });
// });
