<?php

namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table            = 'soal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['ujian_id', 'pertanyaan'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getSoal($idUjian)
    {
        $soal = $this->select('soal.id, soal.pertanyaan, jawaban.id as jawabanId, jawaban.jawaban, jawaban.benar')
            ->join('jawaban', 'jawaban.soal_id = soal.id', 'left')
            ->where('soal.ujian_id', $idUjian)
            ->findAll();

        $groupedData = [];

        foreach ($soal as $sol) {
            $key = $sol['id']; // Gunakan ID soal sebagai kunci unik

            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    'id' => $sol['id'],
                    'pertanyaan' => $sol['pertanyaan'],
                    'jawaban' => [] // Buat array untuk menampung semua jawaban
                ];
            }

            // Tambahkan semua jawaban ke dalam array jawaban
            if ($sol['jawabanId'] !== null) {
                $groupedData[$key]['jawaban'][] = [
                    'idJawab' => $sol['jawabanId'],
                    'jawaban' => $sol['jawaban'],
                    'benar' => $sol['benar']
                ];
            }
        }

        // 1. Ambil array kunci soal
        $soalKeys = array_keys($groupedData);

        // 2. Acak urutan soal
        $this->fisherYatesShuffle($soalKeys);

        // 3. Buat array baru dengan urutan yang sudah diacak
        $shuffledSoal = [];
        foreach ($soalKeys as $key) {
            $shuffledSoal[] = $groupedData[$key];

            // 4. Acak jawaban di setiap soal
            $this->fisherYatesShuffle($shuffledSoal[array_key_last($shuffledSoal)]['jawaban']);
        }

        return $shuffledSoal;
    }
    /**
     * Fungsi untuk mengacak array menggunakan Fisher-Yates Shuffle secara eksplisit
     */
    private function fisherYatesShuffle(array &$array)
    {
        // 1. Buat array -> ini sudah tersedia dari parameter fungsi

        // 2. Periksa kondisi: Loop berjalan selama indeks i >= 1
        for ($i = count($array) - 1; $i >= 1; $i--) {
            // 3. Pilih angka acak x dalam rentang 0 hingga i
            $x = rand(0, $i);

            // 4. Tukar elemen array[x] dengan array[i]
            $temp = $array[$i];
            $array[$i] = $array[$x];
            $array[$x] = $temp;

            // 5. Dekrementasi nilai indeks -> Sudah otomatis dalam loop
        }
    }
}
