<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ✅ Public routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// ✅ Auth routes
$routes->match(['get', 'post'], 'auth/login', 'Auth::login');
$routes->match(['get', 'post'], 'auth/register', 'Auth::register');
$routes->get('auth/logout', 'Auth::logout');

$routes->get('dashboard', 'Auth::dashboard');
$routes->get('manage-users', 'Auth::manageUsers');
$routes->get('manage-courses', 'Auth::manageCourses');

// Aliases
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');

// ✅ Unified Dashboard (view inside views/auth/dashboard.php)
$routes->get('dashboard', 'Auth::dashboard');

$routes->get('/course', 'Course::index');
$routes->post('/course/enroll', 'Course::enroll');

// Materials routes
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->get('/materials', 'Materials::index');




