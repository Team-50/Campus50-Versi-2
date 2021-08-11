<?php
$router->get('/', function () use ($router) {
    return 'Campus50v2 API';
});

$router->group(['prefix'=>'v2'], function () use ($router)
{
    //auth login
    $router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);

    //untuk uifront
	$router->get('/system/setting/uifront',['uses'=>'System\UIController@frontend','as'=>'uifront.frontend']);
});

$router->group(['prefix'=>'v2','middleware'=>'auth:api'], function () use ($router)
{
    //authentication
    $router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
    $router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
    $router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

    //ui admin
	$router->get('/system/setting/uiadmin',['uses'=>'System\UIController@admin','as'=>'ui.admin']);
});
