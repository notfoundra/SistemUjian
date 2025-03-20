<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('KelasSeeder');
        $this->call('MapelSeeder');
        $this->call('UsersSeeder');
        $this->call('UjianSeeder');
        $this->call('SoalSeeder');
    }
}
