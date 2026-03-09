<?php

use CodeIgniter\Router\RouteCollection;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'DashboardController::dashboard');

// Customer Section
$routes->get('/pelanggan', 'DashboardController::dataPelanggan');
$routes->get('/pelanggan/delete/(:num)', 'DashboardController::deleteCustomer/$1');
$routes->post('/pelanggan/create', 'DashboardController::createCustomer');
$routes->post('/pelanggan/update', 'DashboardController::updateCustomer');

// Shipment Section
$routes->get('/shipment', 'DashboardController::shipment');
$routes->post('/shipment/store', 'DashboardController::storeShipment');
$routes->post('/shipment/delete/(:num)', 'DashboardController::deleteShipment/$1');
$routes->get('/shipment-tracking', 'DashboardController::shipmentTracking');
$routes->post('/shipment/updateTracking', 'DashboardController::updateTracking');

// Laporan Section 
$routes->get('/laporan', 'DashboardController::laporan');

// Invoice Section 
$routes->get('/invoice', 'DashboardController::invoice');

// User Section 
$routes->get('/users', 'DashboardController::users');

// Setting Section 
$routes->get('/settings', 'DashboardController::settings');
