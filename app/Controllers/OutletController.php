<?php

namespace App\Controllers;

use App\Models\OutletModel;
use App\Models\LocationModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class OutletController extends BaseController
{
    public function index()
    {
        $outletModel   = new OutletModel();
        $locationModel = new LocationModel();

        $data = [
            'outlets'   => $outletModel->orderBy('id', 'ASC')->findAll(),
            'locations' => $locationModel->orderBy('kelurahan', 'ASC')->findAll(),
        ];

        return view('dashboard/outlets', $data);
    }

    public function store()
    {
        $outletModel = new OutletModel();

        $data = [
            'code'        => $this->request->getPost('code'),
            'name'        => $this->request->getPost('name'),
            'type'        => $this->request->getPost('type'),
            'location_id' => $this->request->getPost('location_id') ?: null,
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $outletModel->insert($data);

        if ($outletModel->errors()) {
            return redirect()->to('/outlet')->with('error', $outletModel->errors());
        }

        return redirect()->to('/outlet')->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $outletModel   = new OutletModel();
        $locationModel = new LocationModel();

        $data = [
            'outlet'    => $outletModel->find($id),
            'locations' => $locationModel->orderBy('kelurahan', 'ASC')->findAll(),
        ];

        return view('dashboard/outlet_edit', $data);
    }

    public function update($id)
    {
        $outletModel = new OutletModel();

        $data = [
            'code'        => $this->request->getPost('code'),
            'name'        => $this->request->getPost('name'),
            'type'        => $this->request->getPost('type'),
            'location_id' => $this->request->getPost('location_id') ?: null,
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $outletModel->update($id, $data);

        return redirect()->to('/outlet')->with('success', 'Outlet berhasil diupdate.');
    }

    public function delete($id)
    {
        $outletModel = new OutletModel();

        try {
            $outletModel->delete($id);
            return redirect()->to('/outlet')->with('success', 'Outlet berhasil dihapus.');
        } catch (DatabaseException $e) {
            // Outlet ini masih dipakai di manifest atau shipment, jadi tidak bisa dihapus langsung.
            // Kasih tahu user supaya mereka tahu kenapa gagal.
            return redirect()->to('/outlet')->with('error', 'Outlet tidak bisa dihapus karena masih digunakan oleh data manifest atau shipment yang ada. Hapus atau pindahkan data tersebut terlebih dahulu.');
        }
    }
}