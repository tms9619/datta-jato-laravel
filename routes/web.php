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

Auth::routes();

Route::get('logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->middleware('auth.role');

Route::get('/','RedirecionadorRoleController@index')->middleware('auth.role');

Route::resource('redirecionador','RedirecionadorRoleController')->middleware('auth.role');

Route::resource('user_cliente', 'DattaJatoClienteController')->middleware('auth.role.cliente');

Route::get('horarios','DattaJatoClienteController@horarios_disponiveis'); // Ajax horarios disponiveis cliente

Route::resource('user_operador', 'DattaJatoOperadorController')->middleware('auth.role.operador');

Route::get('user_operador_agendamento_andamento', 'DattaJatoOperadorController@agendamento_andamento')->name('agendamento_andamento');

Route::get('user_operador_agendamento_finalizado', 'DattaJatoOperadorController@agendamento_finalizado')->name('agendamento_finalizado');