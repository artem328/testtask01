<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Add_short_urls
 * @property CI_DB_forge $dbforge
 */
class Migration_Add_short_urls extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'original_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'short_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => TRUE
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'visits' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
            )
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('short_urls');
    }

    public function down()
    {
        $this->dbforge->drop_table('short_urls');
    }
}