<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotifikasiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_notifikasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => false,
            ],
            'pesan' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_notifikasi', true);
        $this->forge->addForeignKey('id_user', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notifikasi', true);
    }

    public function down()
    {
        $this->forge->dropTable('notifikasi', true);
    }
}