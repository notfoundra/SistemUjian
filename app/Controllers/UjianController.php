<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UjianController extends BaseController
{
    public function index()
    {
        $listInduk = $this->ujianInduk->findAll();
        $data = [
            'title' => 'Sistem Ujian',
            'ujian' => $listInduk,
            'role' => $this->role,
        ];

        return view($this->role . '/ujian/index', $data);
    }
    public function tambahIndukUjian()
    {
        $input = [
            'judul' => $this->request->getPost('judul'),
            'tahun_ajaran' => $this->request->getPost('tahun'),
            'semester' => $this->request->getPost('semester'),
        ];
        $proccess = $this->ujianInduk->insert($input);

        if ($proccess) {
            return redirect()->to(base_url($this->role . '/jadwalujian/'))->withInput()->with('success', 'Data Berhasil Di Input');
        } else {
            return redirect()->to(base_url($this->role . '/jadwalujian/'))->withInput()->with('error', 'Data Gagal Di Input');
        }
    }
}
