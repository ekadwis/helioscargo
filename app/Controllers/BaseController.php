<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Ini adalah base controller untuk semua controller lain di aplikasi.
 * Di sini kita bisa memuat komponen atau menjalankan fungsi yang dibutuhkan secara global.
 *
 * Kalau bikin controller baru, pastikan extend dari sini ya:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * Penting: Pastikan semua method baru dideklarasikan sebagai `protected` atau `private` untuk keamanan.
 */
abstract class BaseController extends Controller
{
    /**
     * Pastikan semua properti yang diinisialisasi dideklarasikan di sini.
     * PHP 8.2 sudah tidak mendukung properti dinamis.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
         // Muat semua helper yang ingin tersedia di controller turunan BaseController di sini.
         // Penting: Jangan letakkan ini di bawah panggilan `parent::initController()` ya.
         // Contoh: $this->helpers = ['form', 'url'];

         // Jangan ubah baris ini.
        parent::initController($request, $response, $logger);

         // Bisa preload model, library, dll. di sini.
         // Contoh: $this->session = service('session');
    }
}
