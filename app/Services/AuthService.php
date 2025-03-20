<?php

namespace App\Services;

use App\Models\UserModel;

class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function attempt($credentials)
    {
        $user = $this->userModel->where('email', $credentials['email'])->first();

        if (!$user || !password_verify($credentials['password'], $user['password'])) {
            return false;
        }

        // Simpan session
        session()->set([
            'user_id'    => $user['id'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        return $user;
    }

    public function user()
    {
        if (!session()->has('user_id')) {
            return null;
        }

        return $this->userModel->find(session()->get('user_id'));
    }

    public function logout()
    {
        session()->destroy();
    }
}
