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

use App\Events\StatusLiked;
use App\Events\VerLoja;
use App\Models\Config;
use App\Models\User;

Route::get('/', 'AuthenticateController@index')->name('login');
Route::post('/auth', 'AuthenticateController@authenticate')->name('auth');
Route::get('/logout', 'AuthenticateController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'AuthenticateController@home')->name('home');

    //Rotas de estabelecimentos
    Route::resource('estabilishment', 'EstabilishmentController');
    Route::get('table', 'EstabilishmentController@table');
    Route::get('table-estabilishment-called', 'EstabilishmentController@tableEstablilishmentCalled');
    Route::get('terminal', 'EstabilishmentController@restartTerminal');
    Route::get('check-service-terminal', 'EstabilishmentController@checkActiveProcessTerminal');
    Route::get('ping-test', 'EstabilishmentController@pingTest');
    Route::post('holyday/{id}', 'EstabilishmentController@holyday');
    Route::post('notesEstablishment', 'EstabilishmentController@notesEstablishment')->name('establishment.note');

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
    Route::get('get-links-establishment', 'CalledController@getLinks');
    Route::get('verify-open-called', 'CalledController@verifyOpenCalled');
    Route::get('insert-notes', 'CalledController@storeNote');
    Route::get('get-notes', 'CalledController@getNote');
    Route::get('new-sub-caller/{id}', 'CalledController@newSubCaller');
    Route::get('called/{called}/{subcalled?}/edit', 'CalledController@edit')->name('called.edit');
    Route::post('subCaller', 'CalledController@storeSubcalled')->name('called.storeSubcalled');

    //Rotas para Configurações
    Route::get('config', 'ConfigController@index')->name('config.index');
    Route::post('config/update', 'ConfigController@update')->name('config.update');
    Route::resource('category-problem', 'CategoryController');
    Route::get('category-table', 'CategoryController@table');
    Route::resource('cause-problem', 'CauseProblemController');
    Route::get('cause-problem-table', 'CauseProblemController@table');
    Route::resource('type-problem', 'TypeProblemController');
    Route::get('type-problem-table', 'TypeProblemController@table');
    Route::resource('action-take', 'ActionTakeController');
    Route::get('action-take-table', 'ActionTakeController@table');
    Route::resource('notes-establishment', 'NotesEstablishmentController');
    Route::get('notes-establishment-table', 'NotesEstablishmentController@table');
    Route::get('holyday-manager', 'ConfigController@holyDayManager')->name('config.holyday');
    Route::get('holyday-manager-table', 'ConfigController@holyDayTable');
    Route::delete('holyday-manager-delete/{id}', 'ConfigController@removeHolyday');
    Route::get('update-system', 'ConfigController@updateSystem')->name('config.systemUpdate');

    //Rotas para usuários
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/create', 'UsersController@create')->name('users.create');
    Route::post('users', 'UsersController@store')->name('users.store');
    Route::get('users/{id}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('users/{id}', 'UsersController@update')->name('users.update');
    Route::get('table-users', 'UsersController@table');

    //Rotas para Busca
    Route::post('search', 'SearchController@search')->name('search');

    //Rotas para relatórios
    Route::get('reports', 'ReportsController@index')->name('reports');
    Route::post('reports/disponibility', 'ReportsController@disponibility')->name('reports.disponibility');
    Route::post('reports/callers-teleCompany', 'ReportsController@callersTeleCompany')->name('reports.callersTeleCompany');
    Route::post('reports/callers-otrs', 'ReportsController@callersOtrs')->name('reports.callersOtrs');
    Route::post('reports/callers-semep', 'ReportsController@semep')->name('reports.semep');
    Route::post('reports/links', 'ReportsController@links')->name('reports.links');

});
