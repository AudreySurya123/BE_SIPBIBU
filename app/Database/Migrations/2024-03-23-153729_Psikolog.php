<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Psikolog extends Migration
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
            'no_telepon'=> [
                'type'=> 'VARCHAR', // Ubah tipe data menjadi VARCHAR karena no_telepon biasanya string
                'constraint'=> 12, // Ubah panjang maksimal constraint
                'null'=> FALSE,
            ],
            'alamat'=> [
                'type'=> 'VARCHAR',
                'constraint'=> 255,
            ],
            'kelamin'=> [
                'type'=> 'VARCHAR', // Ubah tipe data menjadi ENUM untuk jenis kelamin
                'constraint'=> 255, // Menentukan pilihan ENUM
                'null'=> FALSE,
            ],
            'sertifikat'=> [
                'type'=> 'VARCHAR', 
                'constraint'=> 255, 
                'null'=> FALSE,
            ]
            
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->createTable('psikolog');
    }

    public function down()
    {
        $this->forge->dropTable('psikolog');
    }
}
