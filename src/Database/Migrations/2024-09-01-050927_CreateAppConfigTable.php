<?php

namespace APPKITA\CI4_REST\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppConfigTable extends Migration
{
    public function up()
    {
        // Define the fields for the table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
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

        // Define the primary key
        $this->forge->addKey('id', true);

        // Ensure that 'name' is unique
        $this->forge->addUniqueKey('name');

        // Create the table
        $this->forge->createTable('app_config');
    }

    public function down()
    {
        $this->forge->dropTable('app_config');
    }
}
