<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropTipePertanyaan extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('soal', 'tipe');
    }

    public function down()
    {
        $this->forge->addColumn('soal', [
            'tipe' => [
                'type' => 'ENUM("pg", "esai")',
                'default' => 'pg'
            ]
        ]);
    }
}
