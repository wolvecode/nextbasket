<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('/health', function() {
    return "System OK";
});

$router->post('/users', 'UserController@store');
