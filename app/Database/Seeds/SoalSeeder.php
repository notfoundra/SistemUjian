<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run()
    {
        $ujianIDs = json_decode(file_get_contents(WRITEPATH . 'ujian_ids.json'), true);

        if (!$ujianIDs) {
            throw new \Exception('Seeder UjianSeeder belum dijalankan dengan benar.');
        }

        $data = [
            [
                'ujian_id' => $ujianIDs[0]['id'], // ID ujian pertama
                'pertanyaan' => 'Berapa hasil dari 2 + 2?',
                'tipe' => 'pilihan_ganda'
            ],
            [
                'ujian_id' => $ujianIDs[0]['id'],
                'pertanyaan' => 'Jelaskan konsep pecahan!',
                'tipe' => 'esai'
            ],
            [
                'ujian_id' => $ujianIDs[1]['id'], // ID ujian kedua
                'pertanyaan' => 'Siapa penulis novel "Laskar Pelangi"?',
                'tipe' => 'pilihan_ganda'
            ],
        ];

        $this->db->table('soal')->insertBatch($data);
    }
}
