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
    return view('welcome');
});


Auth::routes();

/******* articles ********/
Route::get('/articles', 'ArticleController@index')->name('articles');
Route::get('/getall', 'ArticleController@getall')->name('getall');
Route::get('/getImage/{image_id}/{edit}', 'ArticleController@getImage')->name('getImage');
Route::post('/updatePost/{id}/{id_image}/{name_image}', 'ArticleController@updatePost')->name('updatePost');

Route::resource('article', 'ArticleController');
/******* articles ********/
Route::get('/home', 'HomeController@index')->name('home');


/******* wizard ********/
Route::get('/wizard', 'WidgetController@index')->name('wizard');
/******* wizard ********/


/******* widget ********/
Route::get('/widget/{snippets}/{quantity}/{categories?}', 'WidgetController@getWidget')->name('widget');

Route::get('/widgetAll', 'WidgetController@widgetAll')->name('widgetAll');
/******* widget ********/

//hay que cambiar el controller
//Route::post('/guardar{request}', 'ArticleController@guardar')->name('guardar');
//Route::post('/store', 'ArticleController@store')->name('store');


/******* users ********/
Route::get('/users', 'UserController@index')->name('users');
/******* users ********/



