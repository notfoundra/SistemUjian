<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table            = 'ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_ujian_induk', 'mapel_id', 'kelas_id', 'tanggal', 'durasi'];

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

    public function getListUjian($idInduk)
    {
        return $this->select('ujian.id, ujian.id_ujian_induk,ujian.mapel_id, ujian.kelas_id,ujian.tanggal, ujian.durasi, kelas.nama as kelas, mapel.nama as mapel')
            ->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->where('id_ujian_induk', $idInduk)
            ->groupBy('ujian.kelas_id, ujian.tanggal, ujian.mapel_id')
            ->findAll();
    }
    public function getUjianSiswa($id)
    {
        return $this->select('ujian.id, ujian.id_ujian_induk, ujian.mapel_id, ujian.kelas_id, ujian.tanggal, ujian.durasi, 
                              kelas.nama as kelas, mapel.nama as mapel, hasil_ujian.nilai as nilai')
            ->join('kelas', 'kelas.id = ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id = ujian.mapel_id', 'left')
            ->join('hasil_ujian', 'hasil_ujian.ujian_id = ujian.id AND hasil_ujian.siswa_id = ' . $id, 'left')
            ->where('ujian.kelas_id = (SELECT kelas_id FROM users WHERE id = ' . $id . ')')
            ->findAll();
    }

    public function getNilaiSiswa($id, $idUjian)
    {
        return $this->select('ujian.id, ujian.id_ujian_induk,ujian.mapel_id, ujian.kelas_id,ujian.tanggal, ujian.durasi, kelas.nama as kelas, mapel.nama as mapel, hasil_ujian.nilai as nilai')
            ->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->join('users', 'users.kelas_id=kelas.id', 'left')
            ->join('hasil_ujian', 'hasil_ujian.ujian_id=ujian.id', 'left')
            ->where('users.id', $id)
            ->where('ujian.id_ujian_induk', $idUjian)
            ->groupBy('ujian.kelas_id, ujian.tanggal, ujian.mapel_id')
            ->findAll();
    }
    public function getUjianGuru($id)
    {
        return $this->select('ujian.id, ujian.id_ujian_induk,ujian.mapel_id, ujian.kelas_id,ujian.tanggal, ujian.durasi, kelas.nama as kelas, mapel.nama as mapel')
            ->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->where('mapel.guru', $id)
            ->groupBy('ujian.kelas_id, ujian.tanggal, ujian.mapel_id')
            ->findAll();
    }
    public function getUjianById($id)
    {
        return $this->select('ujian.id, ujian.id_ujian_induk,ujian.mapel_id, ujian.kelas_id,ujian.tanggal, ujian.durasi, kelas.nama as kelas, mapel.nama as mapel')
            ->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->where('ujian.id', $id)
            ->groupBy('ujian.kelas_id, ujian.tanggal, ujian.mapel_id')
            ->first();
    }
    public function getNilaiPerkelas($kelas) {}
    public function getTotalSchedule()
    {
        return $this->where('status', 'scheduled')->countAllResults();
    }
    public function getScheduledPersiswa($id)
    {
        return $this->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->join('users', 'users.kelas_id=kelas.id', 'left')
            ->where('users.id', $id)
            ->where('status', 'scheduled')->countAllResults();
    }
    public function getExpiredPersiswa($id)
    {
        return $this->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->join('users', 'users.kelas_id=kelas.id', 'left')
            ->where('users.id', $id)
            ->where('status', 'expired')->countAllResults();
    }
    public function getTotalActive()
    {
        return $this->where('status', 'active')->countAllResults();
    }
    public function getName($id)
    {
        return $this->select('ujian_induk.judul, ujian_induk.tahun_ajaran, mapel.nama as mapel, kelas.nama as kelas')
            ->join('ujian_induk', 'ujian_induk.id=ujian.id_ujian_induk', 'left')
            ->join('mapel', 'mapel.id=ujian.mapel_id', 'left')
            ->join('kelas', 'kelas.id=ujian.kelas_id', 'left')
            ->where('ujian.id', $id)
            ->first();
    }
    public function getMapel($ujianId)
    {
        return $this->join('mapel', 'mapel.id = ujian.mapel_id', 'left')
            ->where('ujian.id', $ujianId)
            ->select('mapel.nama')
            ->first()['nama'] ?? null;
    }
}
