<?php

namespace App\Controllers;

use App\Models\LocationModel;
use App\Models\ServiceModel;

class HomeController extends BaseController
{
    public function index()
    {
        $db           = \Config\Database::connect();
        $serviceModel = new ServiceModel();

        $data = [
            'services' => $serviceModel->where('is_active', 1)->findAll(),
            'promos'   => $db->table('promos')->where('is_active', 1)->orderBy('id', 'DESC')->get()->getResultArray(),
            'news'     => $db->table('news')->where('is_published', 1)->orderBy('published_at', 'DESC')->limit(3)->get()->getResultArray(),
        ];

        return view('home', $data);
    }

    public function track()
    {
        $awb = $this->request->getPost('awb');

        if (empty($awb)) {
            return redirect()->to('/')->with('track_error', 'Masukkan nomor resi terlebih dahulu.');
        }

        $db = \Config\Database::connect();

        // Cari shipment
        $shipment = $db->table('shipments s')
            ->select('
                s.id, s.awb, s.item_name, s.qty, s.weight_kg,
                s.current_status, s.estimated_delivery_date,
                s.created_at, s.total_amount, s.payment_status,
                c_sender.name   AS sender_name,
                c_receiver.name AS receiver_name,
                l_origin.kelurahan  AS origin_kel,
                l_origin.kecamatan  AS origin_kec,
                l_origin.kabupaten  AS origin_kab,
                l_origin.provinsi   AS origin_prov,
                l_dest.kelurahan    AS dest_kel,
                l_dest.kecamatan    AS dest_kec,
                l_dest.kabupaten    AS dest_kab,
                l_dest.provinsi     AS dest_prov,
                svc.name AS service_name,
                o.name   AS current_outlet_name
            ')
            ->join('customers c_sender',   'c_sender.id = s.sender_customer_id',    'left')
            ->join('customers c_receiver', 'c_receiver.id = s.receiver_customer_id','left')
            ->join('locations l_origin',   'l_origin.id = s.origin_location_id',    'left')
            ->join('locations l_dest',     'l_dest.id = s.destination_location_id', 'left')
            ->join('services svc',         'svc.id = s.service_id',                 'left')
            ->join('outlets o',            'o.id = s.current_outlet_id',            'left')
            ->where('s.awb', strtoupper($awb))
            ->get()->getRowArray();

        if (!$shipment) {
            return redirect()->to('/')->with('track_error', 'Nomor resi <strong>' . esc($awb) . '</strong> tidak ditemukan.');
        }

        // Ambil semua history tracking
        $trackings = $db->table('shipment_tracking st')
            ->select('st.status, st.description, st.created_at, l.kelurahan, l.kecamatan, l.kabupaten')
            ->join('locations l', 'l.id = st.location_id', 'left')
            ->where('st.shipment_id', $shipment['id'])
            ->orderBy('st.created_at', 'ASC')
            ->get()->getResultArray();

        return view('tracking_result', [
            'shipment'  => $shipment,
            'trackings' => $trackings,
            'awb'       => $awb,
        ]);
    }

    public function cekTarif()
    {
        $db = \Config\Database::connect();

        $originId   = $this->request->getPost('origin_id');
        $destId     = $this->request->getPost('dest_id');
        $weight     = (float) $this->request->getPost('weight');
        $serviceId  = $this->request->getPost('service_id');

        if (!$originId || !$destId || !$weight || !$serviceId) {
            return $this->response->setJSON(['error' => 'Semua field wajib diisi.']);
        }

        // Ambil provinsi asal & tujuan
        $origin = $db->table('locations')->where('id', $originId)->get()->getRowArray();
        $dest   = $db->table('locations')->where('id', $destId)->get()->getRowArray();

        if (!$origin || !$dest) {
            return $this->response->setJSON(['error' => 'Lokasi tidak valid.']);
        }

        // Tentukan zona
        $zonaJawa = ['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten'];

        $originProv = $origin['provinsi'];
        $destProv   = $dest['provinsi'];
        $originKab  = $origin['kabupaten'];
        $destKab    = $dest['kabupaten'];

        if ($originKab === $destKab) {
            $zona = 'lokal';
        } elseif ($originProv === $destProv) {
            $zona = 'antar_kota';
        } elseif (in_array($originProv, $zonaJawa) && in_array($destProv, $zonaJawa)) {
            $zona = 'antar_provinsi';
        } else {
            $zona = 'luar_jawa';
        }

        // Ambil tarif
        $tarif = $db->table('tarif')
                    ->where('zona', $zona)
                    ->where('service_id', $serviceId)
                    ->get()->getRowArray();

        if (!$tarif) {
            return $this->response->setJSON(['error' => 'Tarif tidak ditemukan.']);
        }

        $beratAktual  = ceil($weight);
        $hargaPerKg   = (float) $tarif['harga_per_kg'];
        $total        = $hargaPerKg * $beratAktual;

        // Ambil info service
        $service = $db->table('services')->where('id', $serviceId)->get()->getRowArray();

        return $this->response->setJSON([
            'zona'         => str_replace('_', ' ', $zona),
            'harga_per_kg' => $hargaPerKg,
            'berat'        => $beratAktual,
            'total'        => $total,
            'service'      => $service['name'],
            'sla'          => $service['sla_days_min'] . '-' . $service['sla_days_max'] . ' hari',
            'origin'       => $origin['kelurahan'] . ', ' . $origin['kabupaten'],
            'dest'         => $dest['kelurahan'] . ', ' . $dest['kabupaten'],
        ]);
    }

    public function getLocations()
    {
        $db      = \Config\Database::connect();
        $keyword = $this->request->getGet('q');

        $locations = $db->table('locations')
            ->like('kelurahan', $keyword)
            ->orLike('kecamatan', $keyword)
            ->orLike('kabupaten', $keyword)
            ->orLike('provinsi', $keyword)
            ->limit(10)
            ->get()->getResultArray();

        $result = array_map(fn($l) => [
            'id'   => $l['id'],
            'text' => $l['kelurahan'] . ', ' . $l['kecamatan'] . ', ' . $l['kabupaten'] . ', ' . $l['provinsi'],
        ], $locations);

        return $this->response->setJSON($result);
    }

    public function contact()
    {
        $name    = $this->request->getPost('name');
        $email   = $this->request->getPost('email');
        $message = $this->request->getPost('message');

        if (empty($name) || empty($email) || empty($message)) {
            return redirect()->to('/')->with('contact_error', 'Semua field wajib diisi.');
        }

        $emailService = \Config\Services::email();
        $emailService->setTo('zippystore08@gmail.com');
        $emailService->setFrom($email, $name);
        $emailService->setSubject('Pesan Kontak dari ' . $name . ' — HELIOSCARGO');
        $emailService->setMessage("
            <h3>Pesan dari Website HELIOSCARGO</h3>
            <p><strong>Nama:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Pesan:</strong><br>{$message}</p>
        ");

        if ($emailService->send()) {
            return redirect()->to('/#contact')->with('contact_success', 'Pesan berhasil dikirim! Kami akan menghubungi Anda segera.');
        } else {
            return redirect()->to('/#contact')->with('contact_error', 'Gagal mengirim pesan. Coba lagi nanti.');
        }
    }
}