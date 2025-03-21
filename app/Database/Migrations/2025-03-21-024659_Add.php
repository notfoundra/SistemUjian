<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Add extends Migration
{
    public function up()
    {
        $this->forge->addColumn('mapel', [
            'kelas_id' => [
                'type' => 'int',
                'null' => true,
            ],
            'guru' => [
                'type' => 'int',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('mapel', 'kelas_id,guru');
    }
}
