<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OutletModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel   = new UserModel();
        $outletModel = new OutletModel();

        $users = $userModel
            ->select('users.*, outlets.name as outlet_name')
            ->join('outlets', 'outlets.id = users.outlet_id', 'left')
            ->orderBy('users.id', 'ASC')
            ->findAll();

        $data = [
            'users'           => $users,
            'outlets'         => $outletModel->where('is_active', 1)->findAll(),
            'totalUsers'      => count($users),
            'totalSuperadmin' => count(array_filter($users, fn($u) => $u['role'] === 'superadmin')),
            'totalAdmin'      => count(array_filter($users, fn($u) => $u['role'] === 'admin')),
        ];

        return view('dashboard/users', $data);
    }

    public function store()
    {
        $userModel = new UserModel();

        if (! $this->validate([
            'username' => 'required|min_length[3]|alpha_numeric_punct',
            'password' => 'required|min_length[6]',
            'full_name' => 'required|min_length[3]',
            'role'     => 'required|in_list[admin,superadmin]',
        ])) {
            return redirect()->to('/users')->with('error', $this->validator->getErrors());
        }

        $data = [
            'outlet_id'     => $this->request->getPost('outlet_id'),
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'full_name'     => $this->request->getPost('full_name'),
            'role'          => $this->request->getPost('role'),
            'is_active'     => 1,
        ];

        // Pastikan username yang mau didaftarkan belum dipakai orang lain
        $existing = $userModel->where('username', $data['username'])->first();
        if ($existing) {
            return redirect()->to('/users')->with('error', 'Username sudah digunakan.');
        }

        $userModel->insert($data);

        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userModel   = new UserModel();
        $outletModel = new OutletModel();

        $data = [
            'user'    => $userModel->find($id),
            'outlets' => $outletModel->where('is_active', 1)->findAll(),
        ];

        return view('dashboard/user_edit', $data);
    }

    public function update($id)
    {
        $userModel = new UserModel();

        $data = [
            'outlet_id' => $this->request->getPost('outlet_id'),
            'full_name' => $this->request->getPost('full_name'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Update password cuma kalau inputan password diisi, kalau kosong biarkan yang lama
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $userModel->update($id, $data);

        return redirect()->to('/users')->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        $userModel = new UserModel();

        // Cegah user menghapus akunnya sendiri yang sedang aktif
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $userModel->delete($id);

        return redirect()->to('/users')->with('success', 'User berhasil dihapus.');
    }
}
