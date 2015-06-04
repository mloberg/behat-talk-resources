<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->get('api/concerts', 'App\Http\Controllers\ConcertController@index');
$app->get('api/concerts/{id}', 'App\Http\Controllers\ConcertController@getConcert');
$app->post('api/concerts', 'App\Http\Controllers\ConcertController@saveConcert');
$app->put('api/concerts/{id}', 'App\Http\Controllers\ConcertController@updateConcert');
$app->delete('api/concerts/{id}', 'App\Http\Controllers\ConcertController@deleteConcert');
