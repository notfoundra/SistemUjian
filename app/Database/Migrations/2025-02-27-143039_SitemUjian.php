<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SitemUjian extends Migration
{

    public function up()
    {
        // Tabel Kelas
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama'      => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kelas');

        // Tabel Users (Operator, Guru, Siswa)
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'        => ['type' => 'ENUM("operator", "guru", "siswa")', 'default' => 'siswa'],
            'kelas_id'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('users');

        // Tabel Mata Pelajaran
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nama'     => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('mapel');

        // Tabel Ujian
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'mapel_id'   => ['type' => 'INT', 'constraint' => 11],
            'kelas_id'   => ['type' => 'INT', 'constraint' => 11],
            'nama'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'tanggal'    => ['type' => 'DATETIME'],
            'durasi'     => ['type' => 'INT', 'constraint' => 3, 'default' => 90],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mapel_id', 'mapel', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kelas_id', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ujian');

        // Tabel Soal
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'ujian_id' => ['type' => 'INT', 'constraint' => 11],
            'tipe'     => ['type' => 'ENUM("pg", "esai")', 'default' => 'pg'],
            'pertanyaan' => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ujian_id', 'ujian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('soal');

        // Tabel Jawaban (Untuk Pilihan Ganda)
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'soal_id'  => ['type' => 'INT', 'constraint' => 11],
            'jawaban'  => ['type' => 'TEXT'],
            'benar'    => ['type' => 'BOOLEAN', 'default' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('soal_id', 'soal', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jawaban');

        // Tabel Hasil Ujian
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'siswa_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'ujian_id'   => ['type' => 'INT', 'constraint' => 11],
            'nilai'      => ['type' => 'FLOAT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('siswa_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ujian_id', 'ujian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hasil_ujian');

        // Tabel Log Aktivitas (Untuk Deteksi Kecurangan)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'siswa_id'   => ['type' => 'BIGINT', 'unsigned' => true],
            'ujian_id'   => ['type' => 'INT', 'constraint' => 11],
            'aktivitas'  => ['type' => 'TEXT'],
            'waktu'      => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('siswa_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ujian_id', 'ujian', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('log_aktivitas');
    }

    public function down()
    {
        $this->forge->dropTable('log_aktivitas');
        $this->forge->dropTable('hasil_ujian');
        $this->forge->dropTable('jawaban');
        $this->forge->dropTable('soal');
        $this->forge->dropTable('ujian');
        $this->forge->dropTable('mapel');
        $this->forge->dropTable('users');
        $this->forge->dropTable('kelas');
    }
}
