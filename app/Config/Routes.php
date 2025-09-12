<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');


//Lab 4
$routes->get('login', 'Auth::login');         
$routes->post('login/submit', 'Auth::loginSubmit');   
$routes->get('register', 'Auth::register');   
$routes->post('register/submit', 'Auth::registerSubmit'); 
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Auth::dashboard');
