<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{


    public function index()
    {
        $title = 'SMPN 1 Karangkancana';
        $data = [
            'title' => $title
        ];
        return view('Auth/Login', $data);
    }
    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'     => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $userModel = new UserModel();
        $userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }

    public function attemptLogin()
    {
        $auth = service('authentication');

        $credentials = [
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        if ($auth->attempt($credentials)) {
            // Ambil data user setelah login berhasil
            $user = $auth->user();

            // Simpan role user ke session
            session()->set([
                'user_id' => $user['id'],  // Simpan ID user
                'role'    => $user['role'], // Simpan role untuk filter akses
                'user'    => $user['nama'], // Simpan role untuk filter akses
                'email'   => $user['email'],
            ]);

            // Redirect sesuai role
            switch ($user['role']) {
                case 'operator':
                    return redirect()->to('/operator')->with('success', 'Login successful.');
                case 'guru':
                    return redirect()->to('/guru')->with('success', 'Login successful.');
                case 'siswa':
                    return redirect()->to('/siswa')->with('success', 'Login successful.');
                default:
                    return redirect()->to('/dashboard')->with('success', 'Login successful.');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
    }


    public function logout()
    {
        service('authentication')->logout();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}
