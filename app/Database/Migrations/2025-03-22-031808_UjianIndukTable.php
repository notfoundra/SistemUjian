<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UjianIndukTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tahun_ajaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 9, // Contoh: 2024/2025
            ],
            'semester' => [
                'type'       => 'ENUM',
                'constraint' => ['1', '2'],
                'default'    => '1',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('ujian_induk');
        $this->forge->addColumn('ujian', [
            'id_ujian_induk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'id', // Supaya lebih teratur setelah kolom id
            ],
        ]);
    }


    public function down()
    {
        $this->forge->dropTable('ujian_induk');
        $this->forge->dropColumn('ujian', 'id_ujian_induk');
    }
}
