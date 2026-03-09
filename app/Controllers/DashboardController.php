<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\ShipmentTrackingModel;
use App\Models\ShipmentTrackingLogModel;
use App\Models\ShipmentModel;
use App\Models\ServiceModel;
use App\Models\LocationModel;
use CodeIgniter\HTTP\ResponseInterface;


class DashboardController extends BaseController
{
    public function dashboard()
    {
        return view('dashboard/dashboard.php');
    }

    // Customer Section
    public function dataPelanggan()
    {
        $customerModel = new CustomerModel();

        $keyword = $this->request->getGet('q');

        if ($keyword) {
            $customers = $customerModel->search($keyword)->findAll();
        } else {
            $customers = $customerModel
                ->select('customers.*, locations.kabupaten')
                ->join('locations', 'locations.id = customers.location_id', 'left')
                ->findAll();
        }

        $data = [
            'customers' => $customers
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

        if (!$this->validate([
            'name' => 'required|min_length[3]',
            'phone' => 'required|numeric',
            'email' => 'permit_empty|valid_email',
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
            'location_id' => $this->request->getPost('location_id'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $customerModel->insert($data);

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan');
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

        return view('dashboard/shipments', $data);
    }

    public function storeShipment()
    {
        $shipmentModel = new ShipmentModel();
        $trackingLogModel = new ShipmentTrackingLogModel();

        $db = \Config\Database::connect();
        $db->transStart();

        $lastShipment = $shipmentModel->orderBy('id', 'DESC')->first();
        $nextId = $lastShipment ? ((int)$lastShipment['id'] + 1) : 1;
        $awb = 'AWB' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $shippingFee  = (float) $this->request->getPost('shipping_fee');
        $insuranceFee = (float) $this->request->getPost('insurance_fee');
        $totalAmount  = $shippingFee + $insuranceFee;

        $now = date('Y-m-d H:i:s');

        $data = [
            'awb'                     => $awb,
            'created_by_user_id'      => 1,
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
            'current_status'          => 'CREATED',
            'created_at'              => $now,
            'updated_at'              => $now,
        ];

        $shipmentModel->insert($data);
        $shipmentId = $shipmentModel->getInsertID();

        $trackingData = [
            'shipment_id'         => $shipmentId,
            'location_id'         => $this->request->getPost('origin_location_id'),
            'status'              => 'CREATED',
            'description'         => 'Shipment berhasil dibuat dan menunggu proses pengiriman. AWB: ' . $awb,
            'created_by_user_id'  => 1,
            'created_at'          => $now,
        ];

        $trackingLogModel->insert($trackingData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/shipment')->with('error', 'Gagal menambahkan shipment.');
        }

        return redirect()->to('/shipment')->with('success', 'Shipment berhasil ditambahkan.');
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
        $trackingModel = new ShipmentTrackingLogModel();
        $db = \Config\Database::connect();

        $shipment_id = $this->request->getPost('shipment_id');
        $status = $this->request->getPost('status');
        $description = $this->request->getPost('description');

        // insert ke shipment_tracking
        $trackingModel->insert([
            'shipment_id' => $shipment_id,
            'location_id' => 1,
            'status' => $status,
            'description' => $description,
            'created_by_user_id' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // update status di tabel shipments
        $db->table('shipments')
            ->where('id', $shipment_id)
            ->update(['current_status' => $status]);

        return redirect()->back()->with('success', 'Tracking berhasil diupdate');
    }

    // Laporan Section
    public function laporan()
    {
        return view('dashboard/laporan');
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
        return view('dashboard/settings');
    }
}
