<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        // Kalau sudah login, redirect ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function doLogin()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel
                    ->where('username', $username)
                    ->where('is_active', 1)
                    ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->with('error', 'Username atau password salah.');
        }

        // Set session
        session()->set([
            'logged_in' => true,
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'full_name' => $user['full_name'],
            'role'      => $user['role'],
            'outlet_id' => $user['outlet_id'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}