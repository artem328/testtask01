<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Short_code_generator {

    protected static $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int
     */
    private $length = 5;

    /**
     * @param int|null $length
     * @return string
     */
    public function generate($length = null)
    {

        if (null === $length) {
            $length = $this->length;
        }

        $short_code = array();
        $total = strlen(static::$characters);

        while (count($short_code) < $length) {
            array_push($short_code, static::$characters[mt_rand(0, $total - 1)]);
        }

        return implode('', $short_code);
    }

}