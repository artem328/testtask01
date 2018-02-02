<?php

/**
 * Class Redirect
 * @property Short_url $short_url
 * @property CI_Output $output
 */
class Redirect extends CI_Controller {

    /**
     * @param string $code
     */
    public function index($code)
    {
        $this->load->model('short_url');

        if (!($short_url = $this->short_url->find_by_code($code)) || !$short_url->original_url) {
            show_404();
        }

        $short_url->increment_visit();
        redirect($short_url->original_url, 'auto', 302);
    }

}