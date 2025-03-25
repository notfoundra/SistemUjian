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
    public function ujian($idInduk)
    {
        $listUjian = $this->ujian->getListUjian($idInduk);
        $groupedData = [];
        $listKelas = $this->kelas->getKelas();

        foreach ($listUjian as $row) {
            $kelas = $row['kelas'];
            $tanggal = date('D, d M Y', strtotime($row['tanggal']));
            $jam = date('H:i', strtotime($row['tanggal'])); // Format Jam:Menit

            // Masukkan ke struktur yang diinginkan
            $groupedData[$kelas][$tanggal][] = [
                'id_ujian' => $row['id'],
                'mapel' => $row['mapel'],
                'jam' => $jam,
                'durasi' => $row['durasi']
            ];
        }
        // return response()->setJSON($groupedData);
        // dd($groupedData);
        $data = [
            'idInduk' => $idInduk,
            'listKelas' => $listKelas,
            'title' => 'Sistem Ujian',
            'ujian' => $groupedData,
            'role' => $this->role,
        ];

        return view($this->role . '/ujian/child', $data);
    }
    public function tambahUjian()
    {

        // Ambil data dari form
        $data = [
            'id_ujian_induk' => $this->request->getPost('id_induk'),
            'kelas_id' => $this->request->getPost('kelas'),
            'mapel_id' => $this->request->getPost('mapel'),
            'tanggal'    => $this->request->getPost('waktu'), // Format input datetime-local sudah sesuai dengan MySQL
            'durasi'   => $this->request->getPost('durasi'),
        ];

        if ($this->ujian->insert($data)) {
            return redirect()->back()->with('success', 'Ujian berhasil ditambahkan');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan ujian');
        }
    }
    public function deleteujian($id)
    {
        if ($this->ujian->delete($id)) {
            session()->setFlashdata('success', 'Data berhasil dihapus.');
            return $this->response->setJSON(['status' => 'deleted']);
        } else {
            session()->setFlashdata('error', 'Gagal menghapus data.');
            return $this->response->setJSON(['status' => 'error']);
        }
    }
    public function getUjian($id)
    {
        $ujian = $this->ujian->getUjianById($id);
        if ($ujian) {
            return $this->response->setJSON($ujian);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }
    public function updateUjian()
    {
        $id = $this->request->getPost('id');
        $data = [
            'kelas_id' => $this->request->getPost('kelas'),
            'mapel_id' => $this->request->getPost('mapel'),
            'tanggal' => $this->request->getPost('tanggal'),
            'durasi' => $this->request->getPost('durasi'),
        ];

        if ($this->ujian->update($id, $data)) {
            session()->setFlashdata('success', 'Data berhasil diperbarui.');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui data.');
            return $this->response->setJSON(['status' => 'error']);
        }
    }
    public function hasilujian()
    {
        $kelas = $this->kelas->getKelas();

        $data = [
            'kelas' => $kelas,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/ujian/hasil', $data);
    }
    public function hasilujianPerkelas($kelas)
    {
        $siswa = $this->user->getNilaiPerkelas($kelas);
        $data = [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'listkelas' => $listkelas,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/siswa/perkelas', $data);
    }
}
