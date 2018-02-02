<?php

/**
 * Class Home
 * @property CI_Loader $load
 */
class Home extends CI_Controller {

    public function index()
    {
        $this->load->view('home_screen');
    }
}