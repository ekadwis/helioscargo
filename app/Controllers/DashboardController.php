<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\ShipmentTrackingModel;
use App\Models\ShipmentTrackingLogModel;
use App\Models\ShipmentModel;
use App\Models\ServiceModel;
use App\Models\LocationModel;
use App\Models\OutletModel;
use CodeIgniter\HTTP\ResponseInterface;


class DashboardController extends BaseController
{
    public function dashboard()
    {
        $db            = \Config\Database::connect();
        $shipmentModel = new ShipmentModel();
        $customerModel = new CustomerModel();
        $outletModel   = new OutletModel();

        // ---- Summary Cards ----
        $totalShipment  = $shipmentModel->countAll();
        $totalPelanggan = $customerModel->countAll();
        $totalOutlet    = $outletModel->where('is_active', 1)->countAllResults();

        $totalPendapatan = $db->table('shipments')
            ->selectSum('total_amount')
            ->get()->getRow()->total_amount ?? 0;

        $pendapatanBulanIni = $db->table('shipments')
            ->selectSum('total_amount')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->get()->getRow()->total_amount ?? 0;

        // ---- Status Breakdown ----
        $statusList = ['draft', 'booked', 'picked_up', 'in_transit', 'delivered', 'cancelled'];
        $statusCount = [];
        foreach ($statusList as $s) {
            $statusCount[$s] = $shipmentModel->where('current_status', $s)->countAllResults();
        }

        // ---- 7 Shipment Terbaru ----
        $recentShipments = $db->table('shipments s')
            ->select('s.awb, s.item_name, s.current_status, s.total_amount, s.created_at,
                  c.name AS sender_name, l.kabupaten AS tujuan')
            ->join('customers c', 'c.id = s.sender_customer_id', 'left')
            ->join('locations l', 'l.id = s.destination_location_id', 'left')
            ->orderBy('s.created_at', 'DESC')
            ->limit(7)
            ->get()->getResultArray();

        // ---- Tracking Terbaru ----
        $recentTracking = $db->table('shipment_tracking st')
            ->select('st.status, st.description, st.created_at, s.awb')
            ->join('shipments s', 's.id = st.shipment_id', 'left')
            ->orderBy('st.created_at', 'DESC')
            ->limit(6)
            ->get()->getResultArray();

        // ---- Grafik: shipment per hari 7 hari terakhir ----
        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('d M', strtotime($date));
            $chartData[]   = $db->table('shipments')
                ->where('DATE(created_at)', $date)
                ->countAllResults();
        }

        // ---- Grafik: pendapatan per hari 7 hari terakhir ----
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = date('Y-m-d', strtotime("-$i days"));
            $revenueData[] = (float)($db->table('shipments')
                ->selectSum('total_amount')
                ->where('DATE(created_at)', $date)
                ->get()->getRow()->total_amount ?? 0);
        }

        $data = [
            'totalShipment'       => $totalShipment,
            'totalPelanggan'      => $totalPelanggan,
            'totalOutlet'         => $totalOutlet,
            'totalPendapatan'     => $totalPendapatan,
            'pendapatanBulanIni'  => $pendapatanBulanIni,
            'statusCount'         => $statusCount,
            'recentShipments'     => $recentShipments,
            'recentTracking'      => $recentTracking,
            'chartLabels'         => json_encode($chartLabels),
            'chartData'           => json_encode($chartData),
            'revenueData'         => json_encode($revenueData),
        ];

        return view('dashboard/dashboard', $data);
    }

    // Customer Section
    public function dataPelanggan()
    {
        $customerModel = new CustomerModel();
        $locationModel = new LocationModel();

        $keyword = $this->request->getGet('q');
        $locations = $locationModel->findAll();

        if ($keyword) {
            $customers = $customerModel->search($keyword)->findAll();
        } else {
            $customers = $customerModel
                ->select('customers.*, locations.kabupaten')
                ->join('locations', 'locations.id = customers.location_id', 'left')
                ->findAll();
        }

        $data = [
            'customers' => $customers,
            'locations' => $locations
        ];

        return view('dashboard/data_pelanggan', $data);
    }

    public function deleteCustomer($id)
    {
        $customerModel = new \App\Models\CustomerModel();

        $customer = $customerModel->find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan');
        }

        $customerModel->delete($id);

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus');
    }

    public function createCustomer()
    {
        $customerModel = new \App\Models\CustomerModel();

        $rules = [
            'sender_name'        => 'required|min_length[3]',
            'sender_phone'       => 'required',
            'sender_email'       => 'permit_empty|valid_email',
            'sender_location_id' => 'required|integer',

            'receiver_name'      => 'required|min_length[3]',
            'receiver_phone'     => 'required',
            'receiver_email'     => 'permit_empty|valid_email',
            'receiver_location_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        // Insert Sender
        $senderData = [
            'type'        => 'sender',
            'name'        => $this->request->getPost('sender_name'),
            'phone'       => $this->request->getPost('sender_phone'),
            'email'       => $this->request->getPost('sender_email'),
            'address'     => $this->request->getPost('sender_address'),
            'location_id' => $this->request->getPost('sender_location_id'),
        ];
        $customerModel->insert($senderData);

        // Insert Receiver
        $receiverData = [
            'type'        => 'receiver',
            'name'        => $this->request->getPost('receiver_name'),
            'phone'       => $this->request->getPost('receiver_phone'),
            'email'       => $this->request->getPost('receiver_email'),
            'address'     => $this->request->getPost('receiver_address'),
            'location_id' => $this->request->getPost('receiver_location_id'),
        ];
        $customerModel->insert($receiverData);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', ['Gagal menyimpan data pelanggan.']);
        }

        $db->transCommit();

        return redirect()->back()->with('success', 'Pengirim dan Penerima berhasil ditambahkan.');
    }

    public function updateCustomer()
    {
        $customerModel = new \App\Models\CustomerModel();

        $id = $this->request->getPost('id');

        if (!$this->validate([
            'name' => 'required|min_length[3]',
            'phone' => 'required',
            'location_id' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $data = [
            'type' => $this->request->getPost('type'),
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'location_id' => $this->request->getPost('location_id')
        ];

        $customerModel->update($id, $data);

        return redirect()->back()->with('success', 'Pelanggan berhasil diupdate');
    }


    // Shipment Section
    public function shipment()
    {
        $shipmentModel = new ShipmentModel();
        $serviceModel  = new ServiceModel();
        $locationModel = new LocationModel();
        $customerModel = new CustomerModel();
        $outletModel = new OutletModel();

        $data['shipments'] = $shipmentModel
            ->orderBy('id', 'DESC')
            ->findAll();

        $data['services'] = $serviceModel
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();

        $data['locations'] = $locationModel
            ->orderBy('provinsi', 'ASC')
            ->orderBy('kabupaten', 'ASC')
            ->orderBy('kecamatan', 'ASC')
            ->orderBy('kelurahan', 'ASC')
            ->findAll();

        $data['customers'] = $customerModel
            ->orderBy('name', 'ASC')
            ->findAll();

        $data['outlets'] = $outletModel->where('is_active', 1)->findAll();

        return view('dashboard/shipments', $data);
    }

    public function storeShipment()
    {
        $shipmentModel = new ShipmentModel();
        $trackingLogModel = new ShipmentTrackingLogModel();

        $db = \Config\Database::connect();
        $db->transStart();

        $originId      = $this->request->getPost('origin_location_id');
        $destinationId = $this->request->getPost('destination_location_id');

        if (empty($originId) || empty($destinationId)) {
            return redirect()->to('/shipment')
                ->with('error', 'Lokasi asal dan tujuan harus dipilih dari daftar yang tersedia.');
        }

        $lastShipment = $shipmentModel->orderBy('id', 'DESC')->first();
        $nextId = $lastShipment ? ((int)$lastShipment['id'] + 1) : 1;
        $awb = 'AWB' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $shippingFee  = (float) $this->request->getPost('shipping_fee');
        $insuranceFee = (float) $this->request->getPost('insurance_fee');
        $totalAmount  = $shippingFee + $insuranceFee;

        $now = date('Y-m-d H:i:s');

        $pickupOutletId = session()->get('role') === 'superadmin'
            ? $this->request->getPost('pickup_outlet_id')
            : session()->get('outlet_id');

        $data = [
            'awb'                     => $awb,
            'created_by_user_id'      => session()->get('user_id'),
            'sender_customer_id'      => $this->request->getPost('sender_customer_id'),
            'receiver_customer_id'    => $this->request->getPost('receiver_customer_id'),
            'origin_location_id'      => $this->request->getPost('origin_location_id'),
            'destination_location_id' => $this->request->getPost('destination_location_id'),
            'service_id'              => $this->request->getPost('service_id'),
            'item_name'               => $this->request->getPost('item_name'),
            'item_desc'               => $this->request->getPost('item_desc'),
            'qty'                     => $this->request->getPost('qty'),
            'weight_kg'               => $this->request->getPost('weight_kg'),
            'length_cm'               => $this->request->getPost('length_cm'),
            'width_cm'                => $this->request->getPost('width_cm'),
            'height_cm'               => $this->request->getPost('height_cm'),
            'is_fragile'              => $this->request->getPost('is_fragile') ? 1 : 0,
            'declared_value'          => $this->request->getPost('declared_value'),
            'shipping_fee'            => $shippingFee,
            'insurance_fee'           => $insuranceFee,
            'total_amount'            => $totalAmount,
            'current_status'          => 'draft',
            'created_at'              => $now,
            'updated_at'              => $now,
            'pickup_outlet_id'        => $pickupOutletId,
            'delivery_outlet_id'      => $this->request->getPost('delivery_outlet_id'),
            'current_outlet_id'       => $pickupOutletId,
            'manifest_id'             => null,
            'estimated_delivery_date' => $this->request->getPost('estimated_delivery_date'),
            'payment_status'          => $this->request->getPost('payment_status'),
            'cod_amount'              => $this->request->getPost('cod_amount'),
        ];

        $shipmentModel->insert($data);
        $shipmentId = $shipmentModel->getInsertID();

        $trackingData = [
            'shipment_id'         => $shipmentId,
            'location_id'         => $this->request->getPost('origin_location_id'),
            'status'              => 'CREATED',
            'description'         => 'Shipment berhasil dibuat dan menunggu proses pengiriman. AWB: ' . $awb,
            'created_by_user_id'  => session()->get('user_id'),
            'created_at'          => $now,
        ];

        $trackingLogModel->insert($trackingData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/shipment')->with('error', 'Gagal menambahkan shipment.');
        }

        return redirect()->to('/shipment')->with('success', 'Shipment berhasil ditambahkan.');
    }

    public function detailShipment($id)
    {
        $shipmentModel = new ShipmentModel();
        $trackingLogModel = new ShipmentTrackingLogModel();
        $locationModel = new LocationModel();
        $customerModel = new CustomerModel();
        $serviceModel  = new ServiceModel();
        $outletModel   = new OutletModel();

        $shipment = $shipmentModel->find($id);

        if (!$shipment) {
            return redirect()->to('/shipment')->with('error', 'Shipment tidak ditemukan.');
        }

        $data = [
            'shipment'  => $shipment,
            'trackings' => $trackingLogModel
                ->where('shipment_id', $id)
                ->orderBy('created_at', 'ASC')
                ->findAll(),
            'locations' => $locationModel->findAll(),
            'customers' => $customerModel->findAll(),
            'services'  => $serviceModel->findAll(),
            'outlets'   => $outletModel->findAll(),
        ];

        return view('dashboard/shipment_detail', $data);
    }

    public function editShipment($id)
    {
        $shipmentModel = new ShipmentModel();
        $locationModel = new LocationModel();
        $customerModel = new CustomerModel();
        $serviceModel  = new ServiceModel();
        $outletModel   = new OutletModel();

        $shipment = $shipmentModel->find($id);

        if (!$shipment) {
            return redirect()->to('/shipment')->with('error', 'Shipment tidak ditemukan.');
        }

        $data = [
            'shipment'  => $shipment,
            'locations' => $locationModel->orderBy('kelurahan', 'ASC')->findAll(),
            'customers' => $customerModel->orderBy('name', 'ASC')->findAll(),
            'services'  => $serviceModel->where('is_active', 1)->findAll(),
            'outlets'   => $outletModel->where('is_active', 1)->findAll(),
        ];

        return view('dashboard/shipment_edit', $data);
    }

    public function updateShipment($id)
    {
        $shipmentModel = new ShipmentModel();

        $shipment = $shipmentModel->find($id);
        if (!$shipment) {
            return redirect()->to('/shipment')->with('error', 'Shipment tidak ditemukan.');
        }

        $shippingFee  = (float) $this->request->getPost('shipping_fee');
        $insuranceFee = (float) $this->request->getPost('insurance_fee');

        $data = [
            'sender_customer_id'      => $this->request->getPost('sender_customer_id'),
            'receiver_customer_id'    => $this->request->getPost('receiver_customer_id'),
            'origin_location_id'      => $this->request->getPost('origin_location_id'),
            'destination_location_id' => $this->request->getPost('destination_location_id'),
            'service_id'              => $this->request->getPost('service_id'),
            'item_name'               => $this->request->getPost('item_name'),
            'item_desc'               => $this->request->getPost('item_desc'),
            'qty'                     => $this->request->getPost('qty'),
            'weight_kg'               => $this->request->getPost('weight_kg'),
            'length_cm'               => $this->request->getPost('length_cm'),
            'width_cm'                => $this->request->getPost('width_cm'),
            'height_cm'               => $this->request->getPost('height_cm'),
            'is_fragile'              => $this->request->getPost('is_fragile') ? 1 : 0,
            'shipping_fee'            => $shippingFee,
            'insurance_fee'           => $insuranceFee,
            'total_amount'            => $shippingFee + $insuranceFee,
            'pickup_outlet_id'        => $this->request->getPost('pickup_outlet_id'),
            'delivery_outlet_id'      => $this->request->getPost('delivery_outlet_id'),
            'current_status'          => $this->request->getPost('current_status'),
            'estimated_delivery_date' => $this->request->getPost('estimated_delivery_date'),
            'payment_status'          => $this->request->getPost('payment_status'),
            'cod_amount'              => $this->request->getPost('cod_amount'),
            'updated_at'              => date('Y-m-d H:i:s'),
        ];

        $shipmentModel->update($id, $data);

        return redirect()->to('/shipment')->with('success', 'Shipment berhasil diupdate.');
    }

    public function deleteShipment($id)
    {
        $shipmentModel = new ShipmentModel();
        $trackingLogModel = new ShipmentTrackingLogModel();

        $shipment = $shipmentModel->find($id);

        if (!$shipment) {
            return redirect()->to('/shipment')->with('error', 'Data shipment tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // hapus tracking dulu
        $trackingLogModel->where('shipment_id', $id)->delete();

        // hapus shipment
        $shipmentModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/shipment')->with('error', 'Gagal menghapus shipment.');
        }

        return redirect()->to('/shipment')->with('success', 'Shipment berhasil dihapus.');
    }

    // Shipment Tracking Section
    public function shipmentTracking()
    {
        $trackingModel = new ShipmentTrackingModel();

        $data['shipments'] = $trackingModel
            ->orderBy('shipment_id', 'DESC')
            ->findAll();

        return view('dashboard/shipment_tracking', $data);
    }



    public function updateTracking()
    {
        $trackingLogModel = new ShipmentTrackingLogModel();
        $db = \Config\Database::connect();

        $shipment_id = $this->request->getPost('shipment_id');
        $status      = $this->request->getPost('status');
        $description = $this->request->getPost('description');

        // Map status tracking ke status shipment (enum di tabel shipments)
        $statusMap = [
            'picked_up'        => 'picked_up',
            'manifested'       => 'in_transit',
            'in_transit'       => 'in_transit',
            'arrived_at_hub'   => 'in_transit',
            'out_for_delivery' => 'in_transit',
            'delivered'        => 'delivered',
            'failed_delivery'  => 'in_transit',
            'returned'         => 'cancelled',
        ];

        // Insert ke shipment_tracking
        $trackingLogModel->insert([
            'shipment_id'        => $shipment_id,
            'location_id'        => null,
            'status'             => $status,
            'description'        => $description,
            'created_by_user_id' => session()->get('user_id'),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);



        if (empty($shipment_id) || !is_numeric($shipment_id)) {
            return redirect()->back()->with('error', 'Shipment ID tidak valid.');
        }

        // Update current_status di shipments
        $shipmentStatus = $statusMap[$status] ?? null;
        if ($shipmentStatus) {
            $db->table('shipments')
                ->where('id', $shipment_id)
                ->update(['current_status' => $shipmentStatus]);
        }

        return redirect()->back()->with('success', 'Tracking berhasil diupdate.');
    }

    public function cek_ongkir()
    {
        $service = $this->request->getPost('service');
        $berat   = (float) $this->request->getPost('berat');

        if (!$service || !$berat) {
            return $this->response->setJSON([
                'error' => 'Service dan berat wajib diisi'
            ]);
        }

        $berat = ceil($berat);

        // Range harga berdasarkan service
        switch ($service) {
            case 'economy':
                $min = 10000;
                $max = 20000;
                break;
            case 'regular':
                $min = 15000;
                $max = 25000;
                break;
            case 'express':
                $min = 20000;
                $max = 35000;
                break;
            default:
                $min = 10000;
                $max = 15000;
        }

        // Random + pembulatan
        $harga = rand($min, $max);
        $harga = round($harga / 500) * 500;

        $total = $harga * $berat;

        return $this->response->setJSON([
            'harga_per_kg' => $harga,
            'berat'        => $berat,
            'total'        => $total
        ]);
    }

    // Manifest Section

    public function manifest()
    {
        $manifestModel = new \App\Models\ManifestModel();
        $outletModel   = new OutletModel();

        $data = [
            'manifests' => $manifestModel
                ->orderBy('id', 'DESC')
                ->findAll(),
            'outlets'   => $outletModel->where('is_active', 1)->findAll(),
        ];

        return view('dashboard/manifests', $data);
    }

    public function storeManifest()
    {
        $manifestModel     = new \App\Models\ManifestModel();
        $manifestItemModel = new \App\Models\ManifestItemModel();
        $shipmentModel     = new ShipmentModel();

        $db = \Config\Database::connect();
        $db->transStart();

        // Generate manifest number: MNF-YYYYMMDD-XXX
        $today      = date('Ymd');
        $countToday = $manifestModel
            ->where("manifest_number LIKE 'MNF-{$today}-%'")
            ->countAllResults();
        $manifestNumber = 'MNF-' . $today . '-' . str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);

        $originOutletId = $this->request->getPost('origin_outlet_id');
        $shipmentIds    = $this->request->getPost('shipment_ids'); // array

        if (empty($shipmentIds)) {
            return redirect()->to('/manifest')->with('error', 'Pilih minimal 1 shipment.');
        }

        // Hitung total berat
        $totalWeight = 0;
        foreach ($shipmentIds as $sid) {
            $s = $shipmentModel->find($sid);
            if ($s) $totalWeight += (float) $s['weight_kg'];
        }

        // Insert manifest
        $manifestData = [
            'manifest_number'    => $manifestNumber,
            'origin_outlet_id'   => $originOutletId,
            'destination_hub_id' => $this->request->getPost('destination_hub_id'),
            'vehicle_number'     => $this->request->getPost('vehicle_number'),
            'driver_name'        => $this->request->getPost('driver_name'),
            'total_shipments'    => count($shipmentIds),
            'total_weight'       => $totalWeight,
            'status'             => 'draft',
            'created_by'         => session()->get('user_id'),
            'created_at'         => date('Y-m-d H:i:s'),
        ];

        $manifestModel->insert($manifestData);
        $manifestId = $manifestModel->getInsertID();

        // Insert manifest items + update shipment status & manifest_id
        foreach ($shipmentIds as $sid) {
            $manifestItemModel->insert([
                'manifest_id'  => $manifestId,
                'shipment_id'  => $sid,
                'scanned_at'   => date('Y-m-d H:i:s'),
                'scanned_by'   => 1,
            ]);

            $shipmentModel->update($sid, [
                'manifest_id'    => $manifestId,
                'current_status' => 'booked',
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/manifest')->with('error', 'Gagal membuat manifest.');
        }

        return redirect()->to('/manifest')->with('success', "Manifest {$manifestNumber} berhasil dibuat.");
    }

    public function updateManifestStatus($id)
    {
        $manifestModel = new \App\Models\ManifestModel();
        $shipmentModel = new ShipmentModel();
        $manifestItemModel = new \App\Models\ManifestItemModel();

        $status = $this->request->getPost('status');
        $now    = date('Y-m-d H:i:s');

        $updateData = ['status' => $status];

        if ($status === 'in_transit') {
            $updateData['departed_at'] = $now;
            // Update semua shipment dalam manifest jadi in_transit
            $items = $manifestItemModel->where('manifest_id', $id)->findAll();
            foreach ($items as $item) {
                $shipmentModel->update($item['shipment_id'], ['current_status' => 'in_transit']);
            }
        }

        if ($status === 'arrived') {
            $updateData['arrived_at'] = $now;
            // Update semua shipment jadi picked_up
            $items = $manifestItemModel->where('manifest_id', $id)->findAll();
            foreach ($items as $item) {
                $shipmentModel->update($item['shipment_id'], ['current_status' => 'picked_up']);
            }
        }

        $manifestModel->update($id, $updateData);

        return redirect()->back()->with('success', 'Status manifest berhasil diupdate.');
    }

    public function detailManifest($id)
    {
        $manifestModel     = new \App\Models\ManifestModel();
        $manifestItemModel = new \App\Models\ManifestItemModel();
        $shipmentModel     = new ShipmentModel();
        $outletModel       = new OutletModel();
        $customerModel     = new CustomerModel();

        $manifest = $manifestModel->find($id);
        if (!$manifest) {
            return redirect()->to('/manifest')->with('error', 'Manifest tidak ditemukan.');
        }

        // Ambil shipments dalam manifest ini
        $items = $manifestItemModel->where('manifest_id', $id)->findAll();
        $shipments = [];
        foreach ($items as $item) {
            $s = $shipmentModel->find($item['shipment_id']);
            if ($s) $shipments[] = $s;
        }

        $data = [
            'manifest'  => $manifest,
            'shipments' => $shipments,
            'outlets'   => $outletModel->findAll(),
            'customers' => $customerModel->findAll(),
        ];

        return view('dashboard/manifest_detail', $data);
    }

    public function getShipmentsForManifest()
    {
        $shipmentModel = new ShipmentModel();
        $customerModel = new CustomerModel();

        $outletId = $this->request->getGet('outlet_id');

        $shipments = $shipmentModel
            ->whereIn('current_status', ['draft', 'booked'])
            ->where('pickup_outlet_id', $outletId)
            ->where('manifest_id', null)
            ->findAll();

        // Tambahkan nama pengirim
        foreach ($shipments as &$s) {
            $customer = $customerModel->find($s['sender_customer_id']);
            $s['sender_name'] = $customer['name'] ?? '-';
        }

        return $this->response->setJSON($shipments);
    }

    // Laporan Section
    public function laporan()
    {
        $db = \Config\Database::connect();

        // Default range: bulan ini
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date')   ?? date('Y-m-d');
        $status    = $this->request->getGet('status')     ?? '';
        $outletId  = $this->request->getGet('outlet_id')  ?? '';

        $builder = $db->table('shipments s')
            ->select('
            s.id, s.awb, s.item_name, s.qty, s.weight_kg,
            s.shipping_fee, s.insurance_fee, s.total_amount,
            s.current_status, s.payment_status, s.created_at,
            s.estimated_delivery_date,
            c_sender.name   AS sender_name,
            c_receiver.name AS receiver_name,
            l_dest.kelurahan AS dest_kelurahan,
            l_dest.kecamatan AS dest_kecamatan,
            l_dest.kabupaten AS dest_kabupaten,
            svc.name AS service_name,
            o.name   AS outlet_name
        ')
            ->join('customers c_sender',   'c_sender.id = s.sender_customer_id',   'left')
            ->join('customers c_receiver', 'c_receiver.id = s.receiver_customer_id', 'left')
            ->join('locations l_dest',     'l_dest.id = s.destination_location_id', 'left')
            ->join('services svc',         'svc.id = s.service_id',                 'left')
            ->join('outlets o',            'o.id = s.pickup_outlet_id',             'left')
            ->where('DATE(s.created_at) >=', $startDate)
            ->where('DATE(s.created_at) <=', $endDate);

        if (!empty($status)) {
            $builder->where('s.current_status', $status);
        }

        if (!empty($outletId)) {
            $builder->where('s.pickup_outlet_id', $outletId);
        }

        $shipments = $builder->orderBy('s.created_at', 'DESC')->get()->getResultArray();

        // Summary
        $totalShipment   = count($shipments);
        $totalPendapatan = array_sum(array_column($shipments, 'total_amount'));
        $totalDelivered  = count(array_filter($shipments, fn($s) => $s['current_status'] === 'delivered'));
        $totalBerat      = array_sum(array_column($shipments, 'weight_kg'));

        $outletModel = new OutletModel();

        $data = [
            'shipments'       => $shipments,
            'totalShipment'   => $totalShipment,
            'totalPendapatan' => $totalPendapatan,
            'totalDelivered'  => $totalDelivered,
            'totalBerat'      => $totalBerat,
            'outlets'         => $outletModel->where('is_active', 1)->findAll(),
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'filterStatus'    => $status,
            'filterOutlet'    => $outletId,
        ];

        return view('dashboard/laporan', $data);
    }

    public function exportLaporan()
    {
        $db = \Config\Database::connect();

        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date')   ?? date('Y-m-d');
        $status    = $this->request->getGet('status')     ?? '';
        $outletId  = $this->request->getGet('outlet_id')  ?? '';

        $builder = $db->table('shipments s')
            ->select('
            s.awb, s.item_name, s.qty, s.weight_kg,
            s.shipping_fee, s.insurance_fee, s.total_amount,
            s.current_status, s.payment_status, s.created_at,
            c_sender.name   AS sender_name,
            c_receiver.name AS receiver_name,
            l_dest.kelurahan AS dest_kelurahan,
            l_dest.kecamatan AS dest_kecamatan,
            l_dest.kabupaten AS dest_kabupaten,
            svc.name AS service_name,
            o.name   AS outlet_name
        ')
            ->join('customers c_sender',   'c_sender.id = s.sender_customer_id',    'left')
            ->join('customers c_receiver', 'c_receiver.id = s.receiver_customer_id', 'left')
            ->join('locations l_dest',     'l_dest.id = s.destination_location_id', 'left')
            ->join('services svc',         'svc.id = s.service_id',                 'left')
            ->join('outlets o',            'o.id = s.pickup_outlet_id',             'left')
            ->where('DATE(s.created_at) >=', $startDate)
            ->where('DATE(s.created_at) <=', $endDate);

        if (!empty($status))   $builder->where('s.current_status', $status);
        if (!empty($outletId)) $builder->where('s.pickup_outlet_id', $outletId);

        $shipments = $builder->orderBy('s.created_at', 'DESC')->get()->getResultArray();

        // =====================
        // PHPSPREADSHEET
        // =====================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Pengiriman');

        // ---- Styling helpers ----
        $headerFill = [
            'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '1E3A5F'],
        ];
        $headerFont = [
            'bold'  => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size'  => 11,
        ];
        $borderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['rgb' => 'CCCCCC'],
                ],
            ],
        ];

        // ---- Title ----
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'LAPORAN PENGIRIMAN — HELIOSCARGO');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // ---- Subtitle periode ----
        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A2', 'Periode: ' . date('d M Y', strtotime($startDate)) . ' s/d ' . date('d M Y', strtotime($endDate)));
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // ---- Summary row ----
        $totalPendapatan = array_sum(array_column($shipments, 'total_amount'));
        $totalDelivered  = count(array_filter($shipments, fn($s) => $s['current_status'] === 'delivered'));

        $sheet->mergeCells('A3:M3');
        $sheet->setCellValue(
            'A3',
            'Total Shipment: ' . count($shipments) .
                '   |   Delivered: ' . $totalDelivered .
                '   |   Total Pendapatan: Rp ' . number_format($totalPendapatan, 0, ',', '.')
        );
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF3FB']],
        ]);

        $sheet->getRowDimension(4)->setRowHeight(5); // spacer

        // ---- Header kolom ----
        $headers = [
            'A' => 'No',
            'B' => 'AWB',
            'C' => 'Barang',
            'D' => 'Pengirim',
            'E' => 'Penerima',
            'F' => 'Tujuan',
            'G' => 'Service',
            'H' => 'Outlet',
            'I' => 'Berat (kg)',
            'J' => 'Ongkir (Rp)',
            'K' => 'Total (Rp)',
            'L' => 'Status',
            'M' => 'Tanggal',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '5', $label);
            $sheet->getStyle($col . '5')->applyFromArray([
                'fill' => $headerFill,
                'font' => $headerFont,
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);
        }
        $sheet->getRowDimension(5)->setRowHeight(20);

        // ---- Data rows ----
        $row = 6;
        $no  = 1;

        // Warna status
        $statusColors = [
            'delivered'  => 'D4EDDA',
            'in_transit' => 'CCE5FF',
            'picked_up'  => 'D4EDDA',
            'booked'     => 'FFF3CD',
            'draft'      => 'FFF3CD',
            'cancelled'  => 'F8D7DA',
        ];

        foreach ($shipments as $s) {
            $tujuan = implode(', ', array_filter([
                $s['dest_kelurahan'],
                $s['dest_kecamatan'],
                $s['dest_kabupaten'],
            ]));

            $rowColor = $statusColors[$s['current_status']] ?? 'FFFFFF';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $s['awb']);
            $sheet->setCellValue('C' . $row, $s['item_name']);
            $sheet->setCellValue('D' . $row, $s['sender_name'] ?? '-');
            $sheet->setCellValue('E' . $row, $s['receiver_name'] ?? '-');
            $sheet->setCellValue('F' . $row, $tujuan);
            $sheet->setCellValue('G' . $row, $s['service_name'] ?? '-');
            $sheet->setCellValue('H' . $row, $s['outlet_name'] ?? '-');
            $sheet->setCellValue('I' . $row, (float)$s['weight_kg']);
            $sheet->setCellValue('J' . $row, (float)$s['shipping_fee']);
            $sheet->setCellValue('K' . $row, (float)$s['total_amount']);
            $sheet->setCellValue('L' . $row, strtoupper(str_replace('_', ' ', $s['current_status'])));
            $sheet->setCellValue('M' . $row, date('d-m-Y H:i', strtotime($s['created_at'])));

            // Warna baris berdasarkan status
            $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $rowColor],
                ],
            ]);

            // Format angka
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('0.00');
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0');

            $row++;
        }

        // ---- Total row ----
        $sheet->setCellValue('J' . $row, array_sum(array_column($shipments, 'shipping_fee')));
        $sheet->setCellValue('K' . $row, $totalPendapatan);
        $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF3FB']],
        ]);
        $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('K' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->setCellValue('A' . $row, 'TOTAL');

        // ---- Border seluruh tabel ----
        $sheet->getStyle('A5:M' . $row)->applyFromArray($borderAll);

        // ---- Auto width kolom ----
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ---- Freeze header ----
        $sheet->freezePane('A6');

        // ---- Output ----
        $filename = 'Laporan_Pengiriman_' . $startDate . '_sd_' . $endDate . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Invoice Section
    public function invoice()
    {
        return view('dashboard/invoice');
    }

    // Users Section
    public function users()
    {
        return view('dashboard/users');
    }

    // Settings Section
    public function settings()
    {
        $settingModel = new \App\Models\SettingModel();
        $userModel    = new \App\Models\UserModel();

        $data = [
            'settings' => $settingModel->getAllSettings(),
            'user'     => $userModel->find(session()->get('user_id')),
        ];

        return view('dashboard/settings', $data);
    }

    public function updateCompanySettings()
    {
        $settingModel = new \App\Models\SettingModel();

        $settingModel->setSetting('company_name',    $this->request->getPost('company_name'));
        $settingModel->setSetting('company_address', $this->request->getPost('company_address'));
        $settingModel->setSetting('company_phone',   $this->request->getPost('company_phone'));
        $settingModel->setSetting('company_email',   $this->request->getPost('company_email'));

        return redirect()->to('/settings')->with('success', 'Informasi perusahaan berhasil disimpan.');
    }

    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();
        $userId    = session()->get('user_id');

        $fullName = $this->request->getPost('full_name');
        $password = $this->request->getPost('password');

        $data = ['full_name' => $fullName];

        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->to('/settings')->with('error', 'Password minimal 6 karakter.');
            }
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($userId, $data);

        // Update session full_name
        session()->set('full_name', $fullName);

        return redirect()->to('/settings')->with('success', 'Profil berhasil diupdate.');
    }
}
