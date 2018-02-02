<?php

/**
 * Class Simplify
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 * @property Short_code_generator $short_code_generator
 * @property Short_url $short_url
 */
class Simplify extends CI_Controller {

    public function index()
    {

        // Allow only POST requests
        if ('post' !== $this->input->method()) {
            $this->json_response(array(
                'success' => FALSE,
                'code' => 400,
                'message' => 'Bad Request'
            ), 400);
        } else {

            $url = trim($this->input->post('url'));

            if ($url && null === parse_url($url, PHP_URL_SCHEME)) {
                $url = 'http://' . ltrim($url, '/');
            }

            if ($this->validate_url($url)) {

                $this->load->library('Short_code_generator');
                $this->load->model('short_url');

                $code_length = 5;
                $attempts = 0;

                do {
                    $code = $this->short_code_generator->generate($code_length);
                    $attempts++;

                    if ($attempts > 10) {
                        $code_length++;
                    }
                } while ($this->short_url->short_code_exists($code));

                $short_url = $this->short_url->insert(array(
                    'original_url' => $url,
                    'short_code' => $code
                ));

                $this->json_response(array(
                    'success' => TRUE,
                    'original_url' => $short_url->original_url,
                    'short_url' => base_url($short_url->short_code)
                ));
            } else {
                $this->json_response(array(
                    'success' => FALSE,
                    'code' => 400,
                    'message' => 'Bad Request'
                ), 400);
            }
        }
    }

    /**
     * @param $data
     * @param int $status
     */
    private function json_response($data, $status = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status)
            ->set_output(json_encode($data));
    }

    /**
     * @param string $url
     * @return bool
     */
    private function validate_url($url)
    {
        return $url && strlen($url) <= 255;
    }
}