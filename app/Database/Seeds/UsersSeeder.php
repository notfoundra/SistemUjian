<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Admin',
                'email' => 'admin@smp1karangkancana.com',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'operator',
                'kelas_id' => null
            ],
            [
                'nama' => 'Guru Matematika',
                'email' => 'guru.math@smp1karangkancana.com',
                'password' => password_hash('guru123', PASSWORD_BCRYPT),
                'role' => 'guru',
                'kelas_id' => null
            ],
            [
                'nama' => 'Budi',
                'email' => 'budi@smp1karangkancana.com',
                'password' => password_hash('siswa123', PASSWORD_BCRYPT),
                'role' => 'siswa',
                'kelas_id' => 1
            ],
            [
                'nama' => 'Ani',
                'email' => 'ani@smp1karangkancana.com',
                'password' => password_hash('siswa123', PASSWORD_BCRYPT),
                'role' => 'siswa',
                'kelas_id' => 2
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
