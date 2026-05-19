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
$routes->get('/shipment/detail/(:num)', 'DashboardController::detailShipment/$1');
$routes->get('/shipment/edit/(:num)', 'DashboardController::editShipment/$1');
$routes->post('/shipment/update/(:num)', 'DashboardController::updateShipment/$1');
$routes->post('/shipment/delete/(:num)', 'DashboardController::deleteShipment/$1');
$routes->get('/shipment-tracking', 'DashboardController::shipmentTracking');
$routes->post('/shipment/updateTracking', 'DashboardController::updateTracking');
$routes->post('/cek_ongkir', 'DashboardController::cek_ongkir');

// Outlet Section
$routes->get('/outlet', 'OutletController::index');
$routes->post('/outlet/store', 'OutletController::store');
$routes->get('/outlet/edit/(:num)', 'OutletController::edit/$1');
$routes->post('/outlet/update/(:num)', 'OutletController::update/$1');
$routes->post('/outlet/delete/(:num)', 'OutletController::delete/$1');

// Manifest Section
$routes->get('/manifest', 'DashboardController::manifest');
$routes->post('/manifest/store', 'DashboardController::storeManifest');
$routes->get('/manifest/detail/(:num)', 'DashboardController::detailManifest/$1');
$routes->post('/manifest/updateStatus/(:num)', 'DashboardController::updateManifestStatus/$1');
$routes->get('/manifest/getShipments', 'DashboardController::getShipmentsForManifest');

// Laporan Section 
$routes->get('/laporan', 'DashboardController::laporan');

// Invoice Section 
$routes->get('/invoice', 'DashboardController::invoice');

// User Section 
$routes->get('/users', 'DashboardController::users');

// Setting Section 
$routes->get('/settings', 'DashboardController::settings');
