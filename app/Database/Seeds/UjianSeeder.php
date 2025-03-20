<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UjianSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $data = [
            [
                'mapel_id' => 1,
                'kelas_id' => 1,
                'nama' => 'Ujian Matematika Kelas 7A',
                'durasi' => 90
            ],
            [
                'mapel_id' => 2,
                'kelas_id' => 2,
                'nama' => 'Ujian Bahasa Indonesia Kelas 7B',
                'durasi' => 90
            ],
        ];

        $ujianTable = $this->db->table('ujian');
        $ujianTable->insertBatch($data);

        // Ambil ID yang baru dimasukkan
        $ujianIDs = $db->query("SELECT id FROM ujian")->getResultArray();

        // Simpan ID ke dalam sesi sementara
        file_put_contents(WRITEPATH . 'ujian_ids.json', json_encode($ujianIDs));
    }
}
