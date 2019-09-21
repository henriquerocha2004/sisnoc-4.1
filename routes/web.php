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

Route::get('/', 'AuthenticateController@index')->name('login');
Route::post('/auth', 'AuthenticateController@authenticate')->name('auth');
Route::get('/logout', 'AuthenticateController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'AuthenticateController@home')->name('home');

    //Rotas de estabelecimentos
    Route::resource('estabilishment', 'EstabilishmentController');
    Route::get('table', 'EstabilishmentController@table');

    //Rotas de Gerente Regional
    Route::resource('regionalManager', 'RegionalManagerController');
    Route::get('table-regional-manager', 'RegionalManagerController@table');

    //Rotas de Responsável Técnico
    Route::resource('technicalManager', 'TechnicalManagerController');
    Route::get('table-technical-manager', 'TechnicalManagerController@table');

    //Rotas para os links
    Route::resource('links', 'LinksController');
    Route::get('table-links', 'LinksController@table');

    //Rotas para Chamados
    Route::resource('called', 'CalledController');
    Route::get('table-called', 'CalledController@table');

});
