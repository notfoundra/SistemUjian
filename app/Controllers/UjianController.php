<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;

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
    public function resultTest()
    {
        $listInduk = $this->ujianInduk->findAll();
        $data = [
            'ujian' => $listInduk,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
        ];

        return view($this->role . '/ujian/hasil', $data);
    }
    public function individualResult($idInduk)
    {
        $uid = $this->uid;
        $iden = $this->user->idenSiswa($uid);
        $nilai = $this->ujian->getNilaiSiswa($uid, $idInduk);

        $totalNilai = 0;
        $jumlahMapel = count($nilai);

        foreach ($nilai as $n) {
            $totalNilai += (int)$n['nilai'];
        }

        $ipk = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel / 25 : 0;
        $data = [
            'nilai' => $nilai,
            'title' => 'Sistem Ujian',
            'role' => $this->role,
            'iden' => $iden,
            'ipk' => round($ipk, 2) // misalnya 3.25
        ];
        return view($this->role . '/ujian/nilai', $data);
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
        // Ambil semua siswa di kelas
        $siswa = $this->user->select('id,nama')->where('kelas_id', $kelas)->findAll();

        // Ambil semua ujian untuk kelas ini
        $ujian = $this->ujian->where('kelas_id', $kelas)->findAll();
        $namaKelas = $this->kelas->find($kelas);
        $listNilai = [];
        $mapel = [];
        foreach ($ujian as $uji) {
            $ujianId = $uji['id'];
            $mapel[$ujianId] = $this->ujian->getMapel($ujianId);
        }
        foreach ($siswa as $sis) {
            $siswaID = $sis['id'];
            $nilaiSiswa = [
                'nama' => $sis['nama'],
                'nilai' => []
            ];


            foreach ($ujian as $uji) {
                $ujianId = $uji['id'];
                $nilai = $this->nilai->getNilai($ujianId, $siswaID); // Asumsikan return angka atau null
                $nilaiSiswa['nilai'][$ujianId] = $nilai ?? '-';
            }
            $listNilai[] = $nilaiSiswa;
        }

        $data = [
            'kelas_id' => $kelas,
            'listNilai' => $listNilai,
            'ujian' => $ujian,
            'kelas' => $namaKelas['nama'],
            'title' => 'Sistem Ujian',
            'role' => $this->role,
            'mapel' => $mapel,
        ];

        return view($this->role . '/ujian/perkelas', $data);
    }


    public function startTest($idUjian)
    {
        $namaUjian = $this->ujian->getName($idUjian);
        $soal = $this->soal->getSoal($idUjian);
        $ujian = $this->ujian->getUjianById($idUjian);

        $data = [
            'title' => $namaUjian['judul'] . ' ' . $namaUjian['tahun_ajaran'],
            'role' => $this->role,
            'userid' => $this->uid,
            'user' => $this->uname,
            'idUjian' => $idUjian,
            'soal' => $soal,
            'ujian' => $ujian,
            'name' => $namaUjian,
            'durasi' => $ujian['durasi']
        ];

        return view($this->role . '/ujian/test', $data);
    }
    public function submitUjian($idUjian)
    {
        $siswaId = $this->request->getPost('siswaid');
        $data = $this->request->getPost();

        $jumlahBenar = 0;

        // Hitung jawaban yang benar (value == 1 artinya jawaban benar)
        foreach ($data as $key => $value) {
            if (strpos($key, 'jawaban_') !== false && $value == 1) {
                $jumlahBenar++;
            }
        }

        // Ambil total soal dari ujian (jangan getSoal() karena udah diacak)
        $totalSoal = $this->soal->countSoalByUjian($idUjian); // custom function di model

        // Hitung nilai akhir
        $nilai = ($jumlahBenar / $totalSoal) * 100;
        // Simpan ke database
        $this->nilai->insert([
            'siswa_id' => $siswaId,
            'ujian_id' => $idUjian,
            'nilai' => $nilai,
        ]);

        // Redirect ke halaman selesai atau tampilan hasil
        return redirect()->to(base_url($this->role . '/ujian/selesai/' . $idUjian));
    }
    public function selesaiUjian($idUjian)
    {
        $namaUjian = $this->ujian->getName($idUjian);
        $soal = $this->soal->getSoal($idUjian);
        $ujian = $this->ujian->getUjianById($idUjian);
        $siswaId = $this->uid;
        $nilai = $this->nilai->select('nilai')->where([
            'ujian_id' => $idUjian,
            'siswa_id' => $siswaId
        ])->first();

        $data = [
            'title' => $namaUjian['judul'] . ' ' . $namaUjian['tahun_ajaran'],
            'role' => $this->role,
            'userid' => $siswaId,
            'user' => $this->uname,
            'idUjian' => $idUjian,
            'soal' => $soal,
            'ujian' => $ujian,
            'name' => $namaUjian,
            'durasi' => $ujian['durasi'],
            'nilai' => $nilai,
        ];

        return view($this->role . '/ujian/selesai', $data);
    }
    public function exportNilai($kelasId)
    {
        $siswa = $this->user->select('id,nama')->where('kelas_id', $kelasId)->findAll();
        $ujian = $this->ujian->where('kelas_id', $kelasId)->findAll();
        $kelas = $this->kelas->find($kelasId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nama Siswa');
        $col = 'B';
        foreach ($ujian as $uji) {
            $mapel = $this->ujian->getMapel($uji['id']);
            $sheet->setCellValue($col . '1', $mapel);
            $col++;
        }

        // Data Nilai
        $row = 2;
        foreach ($siswa as $sis) {
            $sheet->setCellValue('A' . $row, $sis['nama']);
            $col = 'B';
            foreach ($ujian as $uji) {
                $nilai = $this->nilai->getNilai($uji['id'], $sis['id']);
                $sheet->setCellValue($col . $row, $nilai ?? '-');
                $col++;
            }
            $row++;
        }

        // Download response
        $fileName = 'Nilai_' . $kelas['nama'] . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $lastColumn = chr(ord('A') + count($ujian)); // Misalnya 'D' kalau ada 3 mapel
        $lastRow = $row - 1;

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
