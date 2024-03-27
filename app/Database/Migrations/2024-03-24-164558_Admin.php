<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'nama' => [
                'type'=> 'VARCHAR',
                'constraint'=> 255,
            ],
            'email' => [
                'type'=> 'VARCHAR',
                'constraint'=>150,
            ],
            'password' => [
                'type'=> 'VARCHAR',
                'constraint'=> 255,
                'null'=> FALSE,
            ],   
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('data_admin');
    }

    public function down()
    {
        $this->forge->dropTable('data_admin');
    }
}
