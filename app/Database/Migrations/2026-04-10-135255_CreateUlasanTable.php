<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUlasanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ulasan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'rating' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
            ],
            'komentar' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_ulasan', true);
        $this->forge->addUniqueKey(['id_buku', 'id_user']);
        $this->forge->addForeignKey('id_buku', 'buku', 'id_buku', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ulasan', true);
    }

    public function down()
    {
        $this->forge->dropTable('ulasan', true);
    }
}