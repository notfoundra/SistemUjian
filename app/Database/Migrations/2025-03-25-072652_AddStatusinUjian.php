<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusinUjian extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'ENUM("scheduled", "active", "expired")',
                'default' => 'scheduled',
                'after'      => 'durasi',
            ],
        ];
        $this->forge->addColumn('ujian', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('ujian', 'status');
    }
}
