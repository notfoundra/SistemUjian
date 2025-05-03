<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Kelas 7A'],
            ['nama' => 'Kelas 7B'],
            ['nama' => 'Kelas 8A'],
            ['nama' => 'Kelas 8B'],
            ['nama' => 'Kelas 9A'],
            ['nama' => 'Kelas 9B'],
        ];

        $this->db->table('kelas')->insertBatch($data);
    }
}
