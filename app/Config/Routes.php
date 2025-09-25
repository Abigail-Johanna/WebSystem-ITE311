<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

<<<<<<< HEAD

$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

//LAB 4
$routes->match(['get', 'post'], 'auth/login', 'Auth::login');
$routes->match(['get', 'post'], 'auth/register', 'Auth::register');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('login', 'Auth::login');              
$routes->post('login', 'Auth::login');              
$routes->get('register', 'Auth::register');        
$routes->post('register', 'Auth::register');      
$routes->get('logout', 'Auth::logout');           


$routes->get('dashboard', 'Auth::dashboard');
=======
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
>>>>>>> d39136d55d0825ccb5c04d182acb375fd90c4e5d
