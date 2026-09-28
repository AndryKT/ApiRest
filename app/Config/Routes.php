<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Étape A : endpoint écrit à la main
$routes->get('api/manuel/livres/(:num)', 'Api\LivresManuel::show/$1');

// Étape B : la ressource complète (new et edit servent des formulaires HTML, inutiles pour une API)
$routes->resource('api/livres', ['except' => 'new,edit']);
