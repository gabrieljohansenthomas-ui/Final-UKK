<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ============================================================
// PUBLIC ROUTES (tidak perlu login)
// ============================================================
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::doRegister');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('/forgot-password', 'AuthController::doForgotPassword');
$routes->get('/logout', 'AuthController::logout');

// ============================================================
// ADMIN ROUTES (perlu login + role admin)
// ============================================================
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Admin\DashboardController::index');
    $routes->get('/', 'Admin\DashboardController::index');

    // User Management
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->post('users/toggle/(:num)', 'Admin\UserController::toggle/$1');
    $routes->post('users/delete/(:num)', 'Admin\UserController::delete/$1');

    // Buku Management
    $routes->get('books', 'Admin\BookController::index');
    $routes->get('books/create', 'Admin\BookController::create');
    $routes->post('books/store', 'Admin\BookController::store');
    $routes->get('books/edit/(:num)', 'Admin\BookController::edit/$1');
    $routes->post('books/update/(:num)', 'Admin\BookController::update/$1');
    $routes->post('books/delete/(:num)', 'Admin\BookController::delete/$1');

    // Kategori Management
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->post('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');

    // Anggota Management
    $routes->get('members', 'Admin\MemberController::index');
    $routes->get('members/detail/(:num)', 'Admin\MemberController::detail/$1');

    // Peminjaman Management
    $routes->get('loans', 'Admin\LoanController::index');
    $routes->get('loans/detail/(:num)', 'Admin\LoanController::detail/$1');
    $routes->post('loans/approve/(:num)', 'Admin\LoanController::approve/$1');
    $routes->post('loans/reject/(:num)', 'Admin\LoanController::reject/$1');
    $routes->post('loans/return/(:num)', 'Admin\LoanController::returnBook/$1');
    $routes->post('loans/delete/(:num)', 'Admin\LoanController::delete/$1');

    // Laporan
    $routes->get('reports', 'Admin\ReportController::index');
    $routes->get('reports/export-pdf', 'Admin\ReportController::exportPdf');
    $routes->get('reports/export-excel', 'Admin\ReportController::exportExcel');

    // Profil Admin
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/update', 'Admin\ProfileController::update');
});

// ============================================================
// USER ROUTES (perlu login + role user)
// ============================================================
$routes->group('user', ['filter' => 'auth:user'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'User\DashboardController::index');
    $routes->get('/', 'User\DashboardController::index');

    // Katalog
    $routes->get('catalog', 'User\CatalogController::index');
    $routes->get('catalog/detail/(:num)', 'User\CatalogController::detail/$1');
    $routes->post('catalog/review/(:num)', 'User\CatalogController::submitReview/$1');

    // Peminjaman
    $routes->get('loans', 'User\LoanController::index');
    $routes->get('loans/create/(:num)', 'User\LoanController::create/$1');
    $routes->post('loans/store', 'User\LoanController::store');

    // Notifikasi
    $routes->get('notifications', 'User\NotificationController::index');
    $routes->get('notifications/read/(:num)', 'User\NotificationController::markRead/$1');
    $routes->post('notifications/read-all', 'User\NotificationController::markAllRead');

    // Profil
    $routes->get('profile', 'User\ProfileController::index');
    $routes->post('profile/update', 'User\ProfileController::update');
});
