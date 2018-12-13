<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', 'UserController');
    $router->resource('topics', 'TopicsController');
    $router->resource('categories', 'CategoriesController');
    $router->resource('replies', 'RepliesController');
    $router->resource('links', 'LinkController');

});
