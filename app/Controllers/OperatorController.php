<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class OperatorController extends BaseController
{

    public function index()
    {

        $data = [
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/index', $data);
    }
    public function kelas()
    {
        $kelas = $this->kelas->getKelas();
        $guru = $this->user->getGuru();

        $data = [
            'title' => 'Sistem Ujian',
            'kelas' => $kelas,
            'role' => $this->role,
            'guru' => $guru
        ];

        return view($this->role . '/kelas/index', $data);
    }
    public function deleteKelas()
    {
        $id = $this->request->getPost('id');
        $delete = $this->kelas->delete($id);
        if ($delete) {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('success', 'Data Berhasil Di Hapus');
        } else {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('error', 'Data Gagal Di Hapus');
        }
    }
    public function tambahKelas()
    {
        $input = [
            'nama' => $this->request->getPost('nama'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ];

        $proccess = $this->kelas->insert($input);

        if ($proccess) {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('success', 'Data Berhasil Di Input');
        } else {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('error', 'Data Gagal Di Input');
        }
    }
    public function editKelas()
    {
        $id = $this->request->getPost('id');
        $input = [
            'nama' => $this->request->getPost('nama'),
            'wali_kelas' => $this->request->getPost('wali_kelas')
        ];

        $proccess = $this->kelas->update($id, $input);

        if ($proccess) {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('success', 'Data Berhasil Di Edit');
        } else {
            return redirect()->to(base_url($this->role . '/datakelas/'))->withInput()->with('error', 'Data Gagal Di Edit');
        }
    }
    public function siswa()
    {
        $kelas = $this->kelas->getKelas();

        $data = [
            'kelas' => $kelas,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/siswa/index', $data);
    }
    public function siswaKelas($id)
    {
        $siswa = $this->user->getSiswaPerkelas($id);

        $kelas = $this->kelas->find($id);
        $listkelas = $this->kelas->getKelas();


        $data = [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'listkelas' => $listkelas,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/siswa/perkelas', $data);
    }
    public function tambahSiswa()
    {
        $nama = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $kelas = $this->request->getPost('kelas');

        if (!$nama || !$email || !$password || !$kelas) {
            return redirect()->back()->with('error', 'Semua field harus diisi!');
        }
        $input = [
            'nama'     => trim($nama),
            'email'    => trim($email),
            'password' => password_hash(trim($password), PASSWORD_BCRYPT),
            'kelas_id'    => $kelas,
        ];

        $prosses = $this->user->insert($input);

        if ($prosses) {
            return redirect()->to(base_url($this->role . '/datasiswa/'))->withInput()->with('success', 'Data Berhasil Di input');
        } else {
            return redirect()->to(base_url($this->role . '/datasiswa/'))->withInput()->with('error', 'Data Gagal Di input');
        }
    }
    public function deleteSiswa()
    {
        $id = $this->request->getPost('id');
        $delete = $this->user->delete($id);
        if ($delete) {
            return redirect()->back()->withInput()->with('success', 'Data Berhasil Di Hapus');
        } else {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Di Hapus');
        }
    }
    public function editSiswa()
    {
        $id = $this->request->getPost('id');
        $input = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'kelas_id' => $this->request->getPost('kelas_id'),
        ];

        $proccess = $this->user->update($id, $input);

        if ($proccess) {
            return redirect()->back()->withInput()->with('success', 'Data Berhasil Di Edit');
        } else {
            return redirect()->back()->withInput()->with('error', 'Data Gagal Di Edit');
        }
    }
}
