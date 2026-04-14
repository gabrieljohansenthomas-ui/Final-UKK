<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peminjaman' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_anggota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_buku' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tanggal_pinjam' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tanggal_kembali_rencana' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tanggal_kembali_aktual' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'disetujui', 'ditolak', 'dikembalikan'],
                'default'    => 'pending',
                'null'       => false,
            ],
            'alasan_penolakan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'diproses_oleh' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tanggal_proses' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'denda' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_peminjaman', true);
        $this->forge->addForeignKey('id_anggota', 'anggota', 'id_anggota', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_buku', 'buku', 'id_buku', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('diproses_oleh', 'users', 'id_user', 'SET NULL', 'CASCADE');
        $this->forge->createTable('peminjaman', true);
    }

    public function down()
    {
        $this->forge->dropTable('peminjaman', true);
    }
}