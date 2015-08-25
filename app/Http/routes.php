<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$id_pattern = '[0-9]+';
$response_format_pattern = 'json|xml|csv';

$app->get('/', function() use ($app) {
    return 'BEST RESTful API';
});

$app->group(['namespace' => 'App\Http\Controllers', 'prefix' => 'api/v1'], function ($app) use ($id_pattern,$response_format_pattern) {
	$app->get("/destination_points/{id:$id_pattern}.{format:$response_format_pattern}", 'DestinationPointController@getPoint');
	$app->get("/destination_points/city/{id:$id_pattern}.{format:$response_format_pattern}", 'DestinationPointController@getPointsInCity');
	$app->get("/destination_points/coordinates.{format:$response_format_pattern}?{params}", 'DestinationPointController@getPointsInRectangle');
	$app->get("/destination_points/city/{id:$id_pattern}/coordinates.{format:$response_format_pattern}?{params}", 'DestinationPointController@getPointsInCityInRectangle');
});