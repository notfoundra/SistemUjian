<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropNameUjian extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('ujian', 'nama');
    }

    public function down()
    {
        $fields = [
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255, // Sesuaikan panjang sesuai kebutuhan
                'null'       => true,
                'after'      => 'kelas_id',
            ],
        ];
        $this->forge->addColumn('ujian', $fields);
    }
}
