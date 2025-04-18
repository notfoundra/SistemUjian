<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GuruController extends BaseController
{
    public function index()
    {
        $uid = $this->uid;
        $listUjian = $this->ujian->getUjianGuru($uid);
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
        $data = [
            'title' => 'Sistem Ujian',
            'role' => $this->role,
            'user' => $this->uname,
            'ujian' => $groupedData
        ];

        return view($this->role . '/index', $data);
    }
    public function kelolaSoal($idUjian)
    {
        $soal = $this->soal->getSoal($idUjian);
        $ujian = $this->ujian->getUjianById($idUjian);
        $data = [
            'title' => 'Sistem Ujian',
            'role' => $this->role,
            'user' => $this->uname,
            'idUjian' => $idUjian,
            'soal' => $soal,
            'ujian' => $ujian,
        ];

        return view($this->role . '/ujian/kelolasoal', $data);
    }
}
