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
$routes->post('manage-users/create', 'Auth::createUser');
$routes->get('manage-users/delete/(:num)', 'Auth::deleteUser/$1');
$routes->get('manage-users/restore/(:num)', 'Auth::restoreUser/$1');
$routes->get('manage-users/deactivate/(:num)', 'Auth::deactivateUser/$1');
$routes->get('manage-users/activate/(:num)', 'Auth::activateUser/$1');
$routes->post('manage-users/change-role', 'Auth::changeRole');
$routes->match(['get', 'post'], 'change-password', 'Auth::changePassword');
$routes->get('manage-courses', 'Auth::manageCourses');

// Aliases
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');

// ✅ Unified Dashboard (view inside views/auth/dashboard.php)
$routes->get('dashboard', 'Auth::dashboard');

$routes->post('/course/enroll', 'Course::enroll');

// Courses routes
$routes->get('/courses', 'Course::index');
$routes->match(['get', 'post'], '/courses/search', 'Course::search');

// Materials routes
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->get('/materials', 'Materials::index');

// Notifications routes
$routes->get('notifications', 'Notifications::get');
$routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
