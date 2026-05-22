<?php

namespace App\Controllers;

use App\Models\ShipmentModel;
use App\Models\OutletModel;

class ScanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $outletModel = new OutletModel();
        $data = [
            'outlets' => $outletModel->where('is_active', 1)->findAll(),
        ];
        return view('dashboard/scan', $data);
    }

    public function process()
    {
        $mode    = $this->request->getPost('mode');
        $barcode = strtoupper(trim($this->request->getPost('barcode')));
        $now     = date('Y-m-d H:i:s');

        $outletId   = session()->get('outlet_id');
        $userId     = session()->get('user_id');

        // Ambil info outlet untuk nama lokasi
        $outlet = $this->db->table('outlets o')
            ->select('o.name, l.kelurahan, l.kecamatan, l.kabupaten, l.id as location_id')
            ->join('locations l', 'l.id = o.location_id', 'left')
            ->where('o.id', $outletId)
            ->get()->getRowArray();

        $locationId  = $outlet['location_id'] ?? null;
        $outletName  = $outlet['name'] ?? 'Unknown';

        switch ($mode) {

            // ========================
            // SCAN MANIFEST IN
            // Dipakai saat manifest tiba di hub/outlet
            // ========================
            case 'manifest_in':
                $manifest = $this->db->table('manifests')
                    ->where('manifest_number', $barcode)
                    ->get()->getRowArray();

                if (!$manifest) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Manifest <strong>{$barcode}</strong> tidak ditemukan.",
                    ]);
                }

                if ($manifest['status'] === 'arrived') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Manifest <strong>{$barcode}</strong> sudah pernah di-scan masuk.",
                    ]);
                }

                // Update manifest
                $this->db->table('manifests')->where('id', $manifest['id'])->update([
                    'status'     => 'arrived',
                    'arrived_at' => $now,
                ]);

                // Ambil semua shipment dalam manifest
                $items = $this->db->table('manifest_items')
                    ->where('manifest_id', $manifest['id'])
                    ->get()->getResultArray();

                $count = count($items);

                foreach ($items as $item) {
                    // Update current_status & current_outlet shipment
                    $this->db->table('shipments')->where('id', $item['shipment_id'])->update([
                        'current_status'    => 'in_transit',
                        'current_outlet_id' => $outletId,
                        'last_scan_at'      => $now,
                    ]);

                    // Insert tracking log
                    $this->db->table('shipment_tracking')->insert([
                        'shipment_id'        => $item['shipment_id'],
                        'location_id'        => $locationId,
                        'status'             => 'arrived_at_hub',
                        'description'        => "Paket tiba di {$outletName}",
                        'created_by_user_id' => $userId,
                        'created_at'         => $now,
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "✅ Manifest <strong>{$barcode}</strong> diterima di <strong>{$outletName}</strong>. <strong>{$count} paket</strong> diupdate.",
                    'count'   => $count,
                    'mode'    => 'manifest_in',
                ]);

            // ========================
            // SCAN MANIFEST OUT
            // Dipakai saat manifest berangkat dari hub ke outlet tujuan
            // ========================
            case 'manifest_out':
                $manifest = $this->db->table('manifests')
                    ->where('manifest_number', $barcode)
                    ->get()->getRowArray();

                if (!$manifest) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "Manifest <strong>{$barcode}</strong> tidak ditemukan.",
                    ]);
                }

                // Update manifest berangkat
                $this->db->table('manifests')->where('id', $manifest['id'])->update([
                    'status'      => 'in_transit',
                    'departed_at' => $now,
                ]);

                $items = $this->db->table('manifest_items')
                    ->where('manifest_id', $manifest['id'])
                    ->get()->getResultArray();

                $count = count($items);

                foreach ($items as $item) {
                    $this->db->table('shipments')->where('id', $item['shipment_id'])->update([
                        'current_status' => 'in_transit',
                        'last_scan_at'   => $now,
                    ]);

                    $this->db->table('shipment_tracking')->insert([
                        'shipment_id'        => $item['shipment_id'],
                        'location_id'        => $locationId,
                        'status'             => 'in_transit',
                        'description'        => "Paket berangkat dari {$outletName} menuju tujuan",
                        'created_by_user_id' => $userId,
                        'created_at'         => $now,
                    ]);
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "🚚 Manifest <strong>{$barcode}</strong> berangkat dari <strong>{$outletName}</strong>. <strong>{$count} paket</strong> diupdate.",
                    'count'   => $count,
                    'mode'    => 'manifest_out',
                ]);

            // ========================
            // SCAN AWB INDIVIDUAL
            // Dipakai untuk update status per paket
            // ========================
            case 'awb':
                $shipment = $this->db->table('shipments')
                    ->where('awb', $barcode)
                    ->get()->getRowArray();

                if (!$shipment) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => "AWB <strong>{$barcode}</strong> tidak ditemukan.",
                    ]);
                }

                $newStatus  = $this->request->getPost('awb_status');
                $validStatus = ['picked_up','in_transit','arrived_at_hub','arrived_at_destination','out_for_delivery','delivered','failed_delivery','returned'];

                if (!in_array($newStatus, $validStatus)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Status tidak valid.',
                    ]);
                }

                // Map ke current_status di shipments
                $shipmentStatusMap = [
                    'picked_up'              => 'picked_up',
                    'in_transit'             => 'in_transit',
                    'arrived_at_hub'         => 'in_transit',
                    'arrived_at_destination' => 'picked_up',
                    'out_for_delivery'       => 'picked_up',
                    'delivered'              => 'delivered',
                    'failed_delivery'        => 'in_transit',
                    'returned'               => 'cancelled',
                ];

                $this->db->table('shipments')->where('id', $shipment['id'])->update([
                    'current_status'    => $shipmentStatusMap[$newStatus],
                    'current_outlet_id' => $outletId,
                    'last_scan_at'      => $now,
                ]);

                $statusLabels = [
                    'picked_up'              => 'Picked Up',
                    'in_transit'             => 'In Transit',
                    'arrived_at_hub'         => 'Tiba di Hub',
                    'arrived_at_destination' => 'Tiba di Outlet Tujuan',
                    'out_for_delivery'       => 'Dibawa Kurir',
                    'delivered'              => 'Terkirim',
                    'failed_delivery'        => 'Gagal Terkirim',
                    'returned'               => 'Dikembalikan',
                ];

                $this->db->table('shipment_tracking')->insert([
                    'shipment_id'        => $shipment['id'],
                    'location_id'        => $locationId,
                    'status'             => $newStatus,
                    'description'        => ($statusLabels[$newStatus] ?? $newStatus) . " di {$outletName}",
                    'created_by_user_id' => $userId,
                    'created_at'         => $now,
                ]);

                return $this->response->setJSON([
                    'success'  => true,
                    'message'  => "📦 AWB <strong>{$barcode}</strong> — <strong>" . ($statusLabels[$newStatus] ?? $newStatus) . "</strong> di <strong>{$outletName}</strong>",
                    'mode'     => 'awb',
                    'shipment' => [
                        'awb'       => $shipment['awb'],
                        'item_name' => $shipment['item_name'],
                        'status'    => $statusLabels[$newStatus] ?? $newStatus,
                    ],
                ]);

            default:
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Mode tidak dikenali.',
                ]);
        }
    }
}