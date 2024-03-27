<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
// $routes->get('/mood-tracker', 'MoodTrackerControl::index');
// $routes->post('/simpan-hasil-mood', 'MoodTrackerControl::simpanHasilMood');
// $routes->get('/grafik-mood', 'GrafikControl::index');
// $routes->get('/login', 'DataIbuControl::login');
// $routes->post('/login', 'DataIbuControl::login');
// $routes->get('/signup', 'DataIbuControl::signup');
// $routes->post('/signup', 'DataIbuControl::signup');
// $routes->get('/dashboard', 'DataIbuControl::dashboard');
// $routes->get('/logout', 'DataIbuControl::logout');
// $routes->get('/edit', 'DataIbuControl::edit');
// $routes->post('/update', 'DataIbuControl::update');

$routes->get('data-admin', 'DataAdminControl::index', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'data-admin', 'DataAdminControl::create', ['filter' => 'cors']);
$routes->match(['put', 'options'], 'update/data-admin/(:segment)', 'DataAdminControl::update/$1');
$routes->match(['delete', 'options'], 'delete/data-admin/(:segment)', 'DataAdminControl::delete/$1');

$routes->get('data-ibu', 'DataIbuControl::index', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'data-ibu', 'DataIbuControl::create', ['filter' => 'cors']);
$routes->match(['put', 'options'], 'update/data-ibu/(:segment)', 'DataIbuControl::update/$1');
$routes->match(['delete', 'options'], 'delete/data-ibu/(:segment)', 'DataIbuControl::delete/$1');

$routes->get('data-psikolog', 'DataPsikologControl::index', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'data-psikolog', 'DataPsikologControl::create', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'update/data-psikolog/(:segment)', 'DataPsikologControl::update/$1');
$routes->match(['put', 'options'], 'data-psikolog/(:segment)', 'DataPsikologControl::update/$1');
$routes->match(['delete', 'options'], 'delete/data-psikolog/(:segment)', 'DataPsikologControl::delete/$1');

$routes->get('artikel', 'ArtikelControl::index', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'artikel', 'ArtikelControl::create', ['filter' => 'cors']);
$routes->match(['post', 'options'], 'update/artikel/(:segment)', 'ArtikelControl::update/$1');
$routes->match(['put', 'options'], 'artikel/(:segment)', 'ArtikelControl::update/$1');
$routes->match(['delete', 'options'], 'delete/artikel/(:segment)', 'ArtikelControl::delete/$1');



