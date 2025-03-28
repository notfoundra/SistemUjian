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
        return $groupedData;
    }
}
