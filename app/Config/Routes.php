<?php

use CodeIgniter\Router\RouteCollection;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/**
 * @var RouteCollection $routes
 */

// Rute publik (bisa diakses tanpa login)
$routes->get('/', 'HomeController::index');
$routes->post('/track', 'HomeController::track');
$routes->post('/cek-tarif', 'HomeController::cekTarif');
$routes->get('/locations/search', 'HomeController::getLocations');
$routes->post('/contact', 'HomeController::contact');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/tracking/(:any)', 'HomeController::trackByAwb/$1');

// Rute yang wajib login (dilindungi middleware auth)
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('/dashboard', 'DashboardController::dashboard');

    // Fitur scanner
    $routes->get('/scan', 'ScanController::index');
    $routes->post('/scan/process', 'ScanController::process');

    // Cetak resi PDF
    $routes->get('/shipment/resi/(:num)', 'DashboardController::cetakResi/$1');

    // Manajemen pelanggan
    $routes->get('/pelanggan', 'DashboardController::dataPelanggan');
    $routes->get('/pelanggan/delete/(:num)', 'DashboardController::deleteCustomer/$1');
    $routes->post('/pelanggan/create', 'DashboardController::createCustomer');
    $routes->post('/pelanggan/update', 'DashboardController::updateCustomer');

    // Manajemen pengiriman (shipment)
    $routes->get('/shipment', 'DashboardController::shipment');
    $routes->post('/shipment/store', 'DashboardController::storeShipment');
    $routes->get('/shipment/detail/(:num)', 'DashboardController::detailShipment/$1');
    $routes->get('/shipment/edit/(:num)', 'DashboardController::editShipment/$1');
    $routes->post('/shipment/update/(:num)', 'DashboardController::updateShipment/$1');
    $routes->post('/shipment/delete/(:num)', 'DashboardController::deleteShipment/$1');
    $routes->get('/shipment-tracking', 'DashboardController::shipmentTracking');
    $routes->post('/shipment/updateTracking', 'DashboardController::updateTracking');
    $routes->post('/cek_ongkir', 'DashboardController::cek_ongkir');

    // Manajemen outlet
    $routes->get('/outlet', 'OutletController::index');
    $routes->post('/outlet/store', 'OutletController::store');
    $routes->get('/outlet/edit/(:num)', 'OutletController::edit/$1');
    $routes->post('/outlet/update/(:num)', 'OutletController::update/$1');
    $routes->post('/outlet/delete/(:num)', 'OutletController::delete/$1');

    // Manajemen manifest
    $routes->get('/manifest', 'DashboardController::manifest');
    $routes->post('/manifest/store', 'DashboardController::storeManifest');
    $routes->get('/manifest/detail/(:num)', 'DashboardController::detailManifest/$1');
    $routes->post('/manifest/updateStatus/(:num)', 'DashboardController::updateManifestStatus/$1');
    $routes->get('/manifest/getShipments', 'DashboardController::getShipmentsForManifest');

    // Manajemen tagihan (invoice)
    $routes->get('/invoice', 'DashboardController::invoice');

    // Pengaturan akun
    $routes->get('/settings', 'DashboardController::settings');
    $routes->post('/settings/profile', 'DashboardController::updateProfile');

    // Rute khusus untuk akses Super Admin
    $routes->group('', ['filter' => 'superadmin'], function ($routes) {

        // Manajemen pengguna sistem
        $routes->get('/users', 'UserController::index');
        $routes->post('/users/store', 'UserController::store');
        $routes->get('/users/edit/(:num)', 'UserController::edit/$1');
        $routes->post('/users/update/(:num)', 'UserController::update/$1');
        $routes->post('/users/delete/(:num)', 'UserController::delete/$1');

        // Fitur laporan
        $routes->get('/laporan', 'DashboardController::laporan');
        $routes->get('/laporan/export', 'DashboardController::exportLaporan');

        // Pengaturan informasi perusahaan
        $routes->post('/settings/company', 'DashboardController::updateCompanySettings');

        // Manajemen promo
        $routes->get('/promo', 'ContentController::promoIndex');
        $routes->post('/promo/store', 'ContentController::promoStore');
        $routes->post('/promo/update/(:num)', 'ContentController::promoUpdate/$1');
        $routes->post('/promo/delete/(:num)', 'ContentController::promoDelete/$1');

        // Manajemen berita
        $routes->get('/news', 'ContentController::newsIndex');
        $routes->post('/news/store', 'ContentController::newsStore');
        $routes->post('/news/update/(:num)', 'ContentController::newsUpdate/$1');
        $routes->post('/news/delete/(:num)', 'ContentController::newsDelete/$1');
    });
});
