<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SiswaController extends BaseController
{
    public function index()
    {

        $uid = $this->uid;
        $scheduled = $this->ujian->getScheduledPersiswa($uid);
        $expired = $this->ujian->getExpiredPersiswa($uid);
        $listUjian = $this->ujian->getUjianSiswa($uid);
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
            'ujian' => $groupedData,
            'scheduled' => $scheduled,
            'expired' => $expired,
        ];

        return view($this->role . '/index', $data);
    }
}
