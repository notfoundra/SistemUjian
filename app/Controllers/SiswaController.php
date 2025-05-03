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
        $expired = $this->nilai->ujianDone($uid);
        $nilai = $this->nilai->getAllNilai($uid);
        $listUjian = $this->ujian->getUjianSiswa($uid);
        $groupedData = [];
        $listKelas = $this->kelas->getKelas();

        $totalNilai = 0;
        $jumlahMapel = count($listUjian);

        foreach ($nilai as $n) {
            $totalNilai += (int)$n['nilai'];
        }

        $ipk = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel : 0;

        foreach ($listUjian as $row) {
            $kelas = $row['kelas'];
            $tanggal = date('D, d M Y', strtotime($row['tanggal']));
            $jam = date('H:i', strtotime($row['tanggal'])); // Format Jam:Menit

            // Masukkan ke struktur yang diinginkan
            $groupedData[$kelas][$tanggal][] = [
                'id_ujian' => $row['id'],
                'mapel' => $row['mapel'],
                'jam' => $jam,
                'durasi' => $row['durasi'],
                'nilai' => $row['nilai']
            ];
        }
        $siswaSekelas = $this->user->getSiswaSekelas($uid);
        $rank = [];

        foreach ($siswaSekelas as $siswa) {
            $totalNilai = $this->nilai->sumNilai($siswa['id']); // Asumsinya return angka total nilai
            $rank[] = [
                'id' => $siswa['id'],
                'total_nilai' => $totalNilai
            ];
        }

        // Urutkan berdasarkan total_nilai, paling besar ke kecil
        usort($rank, function ($a, $b) {
            return $b['total_nilai'] <=> $a['total_nilai'];
        });

        // Cari ranking si UID
        $urutan = 1;
        foreach ($rank as $data) {
            if ($data['id'] == $uid) {
                break;
            }
            $urutan++;
        }
        $data = [
            'title' => 'Sistem Ujian',
            'role' => $this->role,
            'user' => $this->uname,
            'ujian' => $groupedData,
            'scheduled' => $scheduled,
            'expired' => $expired,
            'ipk' => round($ipk, 2),
            'rank' => $urutan,
            'jumsiswa' => count($siswaSekelas)
        ];

        return view($this->role . '/index', $data);
    }
}
