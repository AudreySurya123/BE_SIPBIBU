<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Artikel extends Migration
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
            'kategori' => [
                'type'=> 'VARCHAR',
                'constraint'=> 255,
            ],
            'judul' => [
                'type'=> 'VARCHAR',
                'constraint'=>150,
            ],
            'isi' => [
                'type'=> 'TEXT',
                'null'=> FALSE,
            ],
            'cover'=> [
                'type'=> 'VARCHAR', 
                'constraint'=> 255, 
                'null'=> FALSE,
            ]
        ]);
        $this->forge->addKey( 'id',TRUE );
        $this->forge->createTable('artikel');
    }

    public function down()
    {
        $this->forge->dropTable('artikel');
    }
}
