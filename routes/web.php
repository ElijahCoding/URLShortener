<?php

$router->get('/', 'LinkController@show');
$router->post('/', 'LinkController@store');

$router->get('/stats', 'LinkStatsController@show');
