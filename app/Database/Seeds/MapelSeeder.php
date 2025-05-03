<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama' => 'Matematika'],
            ['nama' => 'Bahasa Indonesia'],
            ['nama' => 'IPA'],
            ['nama' => 'IPS'],
            ['nama' => 'Bahasa Inggris'],
        ];

        $this->db->table('mapel')->insertBatch($data);
    }
}
