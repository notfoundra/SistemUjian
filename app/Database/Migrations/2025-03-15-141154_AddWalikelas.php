<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWalikelas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kelas', [
            'wali_kelas' => [
                'type' => 'int',
                'null' => true,

            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kelas', 'wali_kelas');
    }
}
