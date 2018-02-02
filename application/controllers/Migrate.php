<?php

/**
 * Class Migrate
 *
 * Управляет миграциями
 *
 * @property CI_Migration $migration
 * @property  $input
 */
class Migrate extends CI_Controller {

    /**
     * Станадартный метод. Запускает миграцию до текущей версии,
     * указанной в конфиге application/config/migrations.php
     * @see $config['migration_version']
     */
    public function index()
    {
        // Only cli allowed
        if (!is_cli()) {
            show_404();
        }

        $this->load->library('migration');

        if (FALSE === $this->migration->current()) {
            show_error($this->migration->error_string());
        }
    }
}