<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'email', 'password', 'role', 'kelas_id', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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

    public function getGuru()
    {
        return $this->select('users.nama,users.email,users.id,users.password, kelas.nama as kelas')
            ->join('kelas', 'kelas.id=users.kelas_id', 'left')
            ->where('role', 'Guru')
            ->findAll();
    }
    public function getSiswaPerkelas($id)
    {
        return $this->select('users.nama,users.email,users.id, kelas.nama as kelas')
            ->join('kelas', 'kelas.id=users.kelas_id', 'left')
            ->where('kelas_id', $id)
            ->where('role', 'siswa')
            ->findAll();
    }
    public function getTotalSiswa()
    {
        return $this->where('role', 'siswa')->countAllResults();
    }
    public function getTotalGuru()
    {
        return $this->where('role', 'guru')->countAllResults();
    }
    public function idenSiswa($id)
    {
        return $this->select('users.*, kelas.nama as kelas')
            ->join('kelas', 'kelas.id=users.kelas_id', 'left')
            ->where('users.id', $id)
            ->first();
    }
    public function getSiswaSekelas($uid)
    {
        $result = $this->select('kelas_id')->where('id', $uid)->first();

        if (!$result) {
            return []; // kalo UID gak ketemu
        }

        $kelasId = $result['kelas_id'];
        $siswa = $this->select('id')->where('kelas_id', $kelasId)->findAll();
        return $siswa;
    }
}
