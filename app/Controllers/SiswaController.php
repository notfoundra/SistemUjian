<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        $ipk = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel / 25 : 0;

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
    public function downloadtemplate()
    {
        $filePath = FCPATH . 'templates/templatesiswa.xlsx'; // Path file di folder public/templates/

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan!');
        }

        return $this->response->download($filePath, null)->setFileName('templatesiswa.xlsx');
    }
    public function importSiswa()
    {
        $file = $this->request->getFile('file');

        // Validasi file upload
        if (!$file->isValid() || $file->getClientExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'File tidak valid atau bukan format Excel (.xlsx).');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $data = [];

            foreach ($sheet->getRowIterator(5) as $row) { // Mulai dari A5
                $cells = [];
                foreach ($row->getCellIterator('A', 'D') as $cell) {
                    $cells[] = trim($cell->getValue());
                }

                // Skip baris kosong
                if (empty($cells[0]) && empty($cells[1]) && empty($cells[2]) && empty($cells[3])) {
                    continue;
                }

                // Ambil ID kelas
                $kelasID = $this->kelas->getIdbyName($cells[3]);
                if (!$kelasID) {
                    return redirect()->back()->with('error', 'Kelas "' . $cells[3] . '" tidak ditemukan di sistem.');
                }

                // Cek duplikat email (optional)
                if ($this->user->where('email', $cells[1])->countAllResults() > 0) {
                    continue; // Skip jika email sudah ada
                }

                $data[] = [
                    'nama'     => $cells[0],
                    'email'    => $cells[1],
                    'password' => password_hash($cells[2], PASSWORD_BCRYPT),
                    'kelas_id'    => $kelasID,
                ];
            }

            // Jika tidak ada data valid
            if (empty($data)) {
                return redirect()->back()->with('error', 'Tidak ada siswa yang berhasil diparsing dari file.');
            }

            // Simpan batch
            $this->saveSiswa($data);

            return redirect()->back()->with('success', count($data) . ' siswa berhasil diimport.');
        } catch (\Throwable $e) {
            // Log error kalau perlu
            log_message('error', 'Import siswa gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses file.');
        }
    }

    private function saveSiswa(array $data)
    {
        try {
            // Bisa diganti ke insertBatch jika kolom database support
            foreach ($data as $siswa) {
                $this->user->insert($siswa);
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal simpan siswa: ' . $e->getMessage());
            throw $e; // biar tetap ditangani oleh try-catch utama
        }
    }
}
