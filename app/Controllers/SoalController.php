<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SoalController extends BaseController
{
    public function index()
    {
        //
    }
    public function downloadtemplate()
    {
        $filePath = FCPATH . 'templates/templatesoal.xlsx'; // Path file di folder public/templates/

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan!');
        }

        return $this->response->download($filePath, null)->setFileName('templatesoal.xlsx');
    }
    public function tambahSoal()
    {
        $idUjian = $this->request->getPost('id');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $benar = $this->request->getPost('benar');
        $pilihan = $this->request->getPost('pilihan');

        // Pastikan semua input tidak kosong
        if (!$idUjian || !$pertanyaan || !$benar || empty($pilihan)) {
            return redirect()->back()->with('error', 'Data tidak boleh kosong.');
        }

        // Insert soal ke database
        $this->soal->insert([
            'ujian_id' => $idUjian,
            'pertanyaan' => $pertanyaan
        ]);

        // Dapatkan ID soal yang baru saja dimasukkan
        $idSoal = $this->soal->insertID();

        // Insert jawaban yang benar
        $this->jawaban->insert([
            'soal_id' => $idSoal,
            'jawaban' => $benar,
            'benar'   => '1'
        ]);

        // Insert pilihan jawaban lainnya
        foreach ($pilihan as $pil) {
            $this->jawaban->insert([
                'soal_id' => $idSoal,
                'jawaban' => $pil,
                'benar'   => '0' // Jawaban pilihan bukan yang benar
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Soal berhasil ditambahkan.');
    }
    public function deleteSoal()
    {
        $id = $this->request->getPost('id');

        // Validasi ID
        if (!$id || !$this->soal->find($id)) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan.');
        }

        // Gunakan transaksi untuk memastikan kedua operasi berhasil
        $this->db->transStart();

        // Hapus semua jawaban terkait soal ini
        $hapusJawaban = $this->jawaban->where('soal_id', $id)->delete();

        // Hapus soalnya sendiri
        $hapusSoal = $this->soal->delete($id);

        // Commit atau rollback transaksi
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menghapus soal.');
        }

        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }
    public function getSoal()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $soal = $this->soal->find($id);
        if (!$soal) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan.');
        }

        $jawaban = $this->jawaban->where('soal_id', $id)->findAll();
        $benar = null;
        $pilihan = [];

        foreach ($jawaban as $jwb) {
            if ($jwb['benar'] == '1') {
                $benar = $jwb['jawaban'];
            } else {
                $pilihan[] = $jwb['jawaban'];
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'pertanyaan' => $soal['pertanyaan'],
            'benar' => $benar,
            'pilihan' => $pilihan
        ]);
    }

    public function editSoal()
    {
        $id = $this->request->getPost('id');
        $pertanyaan = $this->request->getPost('pertanyaan');
        $benar = $this->request->getPost('benar');
        $pilihan = $this->request->getPost('pilihan');

        if (!$id || !$pertanyaan || !$benar || empty($pilihan)) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        // Update soal
        $this->soal->update($id, ['pertanyaan' => $pertanyaan]);

        // Hapus jawaban lama, lalu input ulang
        $this->jawaban->where('soal_id', $id)->delete();

        // Insert jawaban benar
        $this->jawaban->insert(['soal_id' => $id, 'jawaban' => $benar, 'benar' => '1']);

        // Insert pilihan lain
        foreach ($pilihan as $pil) {
            $this->jawaban->insert(['soal_id' => $id, 'jawaban' => $pil]);
        }

        return redirect()->back()->with('success', 'Soal berhasil diperbarui.');
    }
    public function importSoal()
    {
        $file = $this->request->getFile('file');

        if (!$file->isValid() || $file->getClientExtension() !== 'xlsx') {
            return redirect()->back()->with('error', 'File tidak valid atau bukan format Excel.');
        }

        $spreadsheet = IOFactory::load($file->getTempName());
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];

        foreach ($sheet->getRowIterator(5) as $row) { // Mulai dari A5
            $cells = [];
            foreach ($row->getCellIterator('A', 'E') as $cell) { // Kolom A - E
                $cells[] = trim($cell->getValue());
            }

            if (empty($cells[0])) {
                break; // Stop kalau pertanyaan kosong
            }

            $data[] = [
                'pertanyaan' => $cells[0],
                'benar'      => $cells[1],
                'pilihan'    => array_slice($cells, 2) // C, D, E jadi pilihan
            ];
        }

        if (empty($data)) {
            return redirect()->back()->with('error', 'Tidak ada soal yang diimport.');
        }

        // Simpan ke database
        $this->simpanSoal($data);

        return redirect()->back()->with('success', count($data) . ' soal berhasil diimport.');
    }

    private function simpanSoal($data)
    {
        foreach ($data as $soal) {
            $soalId = $this->soal->insert([
                'ujian_id'   => $this->request->getPost('id_ujian'),
                'pertanyaan' => $soal['pertanyaan']
            ], true); // true buat dapet insertId()

            // Simpan jawaban benar
            $this->jawaban->insert([
                'soal_id' => $soalId,
                'jawaban' => $soal['benar'],
                'benar'   => 1
            ]);

            // Simpan pilihan lain
            foreach ($soal['pilihan'] as $pil) {
                if (!empty($pil)) {
                    $this->jawaban->insert([
                        'soal_id' => $soalId,
                        'jawaban' => $pil,
                        'benar'   => 0
                    ]);
                }
            }
        }
    }
    public function deleteAll()
    {
        $idUjian = $this->request->getPost('id_ujian');

        if (!$idUjian) {
            return redirect()->back()->with('error', 'ID ujian tidak ditemukan.');
        }

        // Ambil semua soal ID dari ujian ini
        $soalIds = $this->soal->where('ujian_id', $idUjian)->findColumn('id');

        if (!empty($soalIds)) {
            // Hapus semua jawaban dari soal-soal tersebut
            $this->jawaban->whereIn('soal_id', $soalIds)->delete();
            // Hapus semua soal dari ujian
            $this->soal->where('ujian_id', $idUjian)->delete();
        }

        return redirect()->back()->with('success', 'Semua soal dan jawaban berhasil dihapus.');
    }
}
