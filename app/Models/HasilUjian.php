<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjian extends Model
{
    protected $table            = 'hasil_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['siswa_id', 'ujian_id', 'nilai'];

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

    public function ujianDone($idSiswa)
    {
        return $this->where('siswa_id', $idSiswa)->countAllResults();
    }
    public function getAllNilai($uid)
    {
        return $this->select('nilai')
            ->where('siswa_id', $uid)
            ->groupBy('ujian_id')
            ->findAll();
    }
    public function sumNilai($id)
    {
        return $this->selectSum('nilai')->where('siswa_id', $id)->first()['nilai'];
    }
    public function getNilai($ujianId, $siswaId)
    {
        return $this->where(['ujian_id' => $ujianId, 'siswa_id' => $siswaId])->first()['nilai'] ?? null;
    }
}
