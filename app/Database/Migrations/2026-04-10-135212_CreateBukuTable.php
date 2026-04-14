<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBukuTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_buku' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'judul_buku' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'pengarang' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'penerbit' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'tahun_terbit' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'isbn' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'no-cover.png',
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'null'       => false,
            ],
            'dipinjam' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
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
        $this->forge->addKey('id_buku', true);
        $this->forge->addUniqueKey('isbn');
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'SET NULL', 'CASCADE');
        $this->forge->createTable('buku', true);
    }

    public function down()
    {
        $this->forge->dropTable('buku', true);
    }
}