<?php

// llamamos a la clase que se encarga de registrarlas y hacer toda la logica detras
$route = new Route();
// 1er parametro = la ruta | 2do parametro = controlador@metodo que se utilizará | 3er parametro = apodo de la ruta | 4to = middlewares
$route->get('/', 'PagesController@index', 'pages.index');
$route->get('/recipe/{recipe}', 'PagesController@show', 'pages.show');

$route->get('/types', 'TypesController@index', 'types.index');
$route->get('/types/{url}', 'TypesController@show', 'types.show');

$route->get('/search/{recipe}', 'PagesController@search', 'pages.search');
$route->post('/search', 'PagesController@searching', 'pages.searching');


$route->get('/login', 'AuthController@showLogin', 'auth.show-login');
$route->post('/login', 'AuthController@login', 'auth.login');
$route->get('/register', 'AuthController@showRegister', 'auth.show-register');
$route->post('/register', 'AuthController@register', 'auth.register');
$route->post('/logout', 'AuthController@logout', 'auth.logout');


$route->get('/my-recipes', 'RecipesController@index', 'recipes.index', ['auth']);
$route->get('/my-recipes/create', 'RecipesController@create', 'recipes.create', ['auth']);
$route->post('/my-recipes', 'RecipesController@store', 'recipes.store', ['auth']);
$route->get('/my-recipes/{recipe}', 'RecipesController@show', 'recipes.show', ['auth']);
$route->get('/my-recipes/{recipe}/edit', 'RecipesController@edit', 'recipes.edit', ['auth']);
$route->post('/my-recipes/{recipe}', 'RecipesController@update', 'recipes.update', ['auth']);
$route->post('/my-recipes/{recipe}/destroy', 'RecipesController@destroy', 'recipes.destroy', ['auth']);


$route->redirect();
