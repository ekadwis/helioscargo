<?php

use CodeIgniter\Router\RouteCollection;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/**
 * @var RouteCollection $routes
 */

// PUBLIK URL
$routes->get('/', 'HomeController::index');
$routes->post('/track', 'HomeController::track');
$routes->post('/cek-tarif', 'HomeController::cekTarif');
$routes->get('/locations/search', 'HomeController::getLocations');
$routes->post('/contact', 'HomeController::contact');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/tracking/(:any)', 'HomeController::trackByAwb/$1');

// PROTECTED URL - LOGIN REQUIRED
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('/dashboard', 'DashboardController::dashboard');

    // Scanner
    $routes->get('/scan', 'ScanController::index');
    $routes->post('/scan/process', 'ScanController::process');

    // Resi PDF
    $routes->get('/shipment/resi/(:num)', 'DashboardController::cetakResi/$1');

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

    // Invoice Section
    $routes->get('/invoice', 'DashboardController::invoice');

    // Settings Section
    $routes->get('/settings', 'DashboardController::settings');
    $routes->post('/settings/profile', 'DashboardController::updateProfile');

    // SUPER ADMIN SECTION
    $routes->group('', ['filter' => 'superadmin'], function ($routes) {

        // Users Management
        $routes->get('/users', 'UserController::index');
        $routes->post('/users/store', 'UserController::store');
        $routes->get('/users/edit/(:num)', 'UserController::edit/$1');
        $routes->post('/users/update/(:num)', 'UserController::update/$1');
        $routes->post('/users/delete/(:num)', 'UserController::delete/$1');

        // Laporan
        $routes->get('/laporan', 'DashboardController::laporan');
        $routes->get('/laporan/export', 'DashboardController::exportLaporan');

        // Settings perusahaan
        $routes->post('/settings/company', 'DashboardController::updateCompanySettings');

        // Promo
        $routes->get('/promo', 'ContentController::promoIndex');
        $routes->post('/promo/store', 'ContentController::promoStore');
        $routes->post('/promo/update/(:num)', 'ContentController::promoUpdate/$1');
        $routes->post('/promo/delete/(:num)', 'ContentController::promoDelete/$1');

        // News
        $routes->get('/news', 'ContentController::newsIndex');
        $routes->post('/news/store', 'ContentController::newsStore');
        $routes->post('/news/update/(:num)', 'ContentController::newsUpdate/$1');
        $routes->post('/news/delete/(:num)', 'ContentController::newsDelete/$1');
    });
});
