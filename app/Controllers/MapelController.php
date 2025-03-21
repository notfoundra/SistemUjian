<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class MapelController extends BaseController
{
    public function index()
    {
        $kelas = $this->kelas->getKelas();
        $guru = $this->user->getGuru();
        $data = [
            'title' => 'Sistem Ujian',
            'kelas' => $kelas,
            'role' => $this->role,
            'guru' => $guru
        ];

        return view($this->role . '/mapel/index', $data);
    }
    public function tambahMapel()
    {
        $input = [
            'nama' => $this->request->getPost('nama'),
            'kelas_id' => $this->request->getPost('kelas_id'),
            'guru' => $this->request->getPost('guru')
        ];
        $proccess = $this->mapel->insert($input);

        if ($proccess) {
            return redirect()->to(base_url($this->role . '/datamapel/'))->withInput()->with('success', 'Data Berhasil Di Input');
        } else {
            return redirect()->to(base_url($this->role . '/datamapel/'))->withInput()->with('error', 'Data Gagal Di Input');
        }
    }
    public function mapelKelas()
    {
        $id = $this->request->getGet('id');
        $mapel = $this->mapel->getMapelPerkelas($id);
        return $this->response->setJSON($mapel);
    }
}
