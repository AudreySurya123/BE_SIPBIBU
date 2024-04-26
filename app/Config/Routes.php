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

$routes->group("api", function ($routes) {
$routes->post("register", "RegisterAdmin::index");
$routes->post("login", "LoginAdmin::index");
$routes->get("data_admin", "Admin::index", ['filter' => 'authFilter']);
});

// QUIZ 1
$routes->get('/', 'Home::index');
$routes->match(['get', 'options'], '/kuisioner', 'KuisionerControl::index');
$routes->match(['post', 'options'], '/simpanHasil', 'KuisionerControl::simpanHasil');
$routes->get('simpanHasil', 'KuisionerControl::read');
$routes->get('semuaHasilKuisioner', 'KuisionerControl::readAll');
$routes->match(['delete', 'options'], 'delete/kuisioner/(:segment)', 'KuisionerControl::delete/$1');

// QUIZ 2
$routes->get('getQuestions', 'KuisionerControl_2::index');
$routes->match(['post', 'options'],'simpanHasil_2', 'KuisionerControl_2::simpanHasil_2');
$routes->get('simpanHasil_2', 'KuisionerControl_2::read');
$routes->get('semuaHasilKuisioner_2', 'KuisionerControl_2::readAll');
$routes->match(['delete', 'options'], 'delete/simpanHasil_2/(:segment)', 'KuisionerControl_2::delete/$1');

$routes->match(['get', 'options'], '/', 'Home::index');
$routes->match(['get', 'options'], 'audio', 'AudioControl::index');
$routes->match(['post', 'options'], 'audio', 'AudioControl::create');
$routes->match(['post', 'options'], 'audio/ubah/(:num)', 'AudioControl::update/$1');
$routes->match(['delete', 'options'], 'audio/(:num)', 'AudioControl::delete/$1');

$routes->match(['get', 'options'], '/', 'Home::index');
$routes->match(['get', 'options'], 'video', 'VideoControl::index');
$routes->match(['get', 'options'], 'video/(:num)', 'VideoControl::show/$1');
$routes->match(['post', 'options'], 'video', 'VideoControl::create');
$routes->match(['post', 'options'], 'video/ubah/(:num)', 'VideoControl::update/$1');
$routes->match(['delete', 'options'], 'video/(:num)', 'VideoControl::delete/$1');

