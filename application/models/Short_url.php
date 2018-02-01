<?php

/**
 * Class Short_url
 *
 * @property CI_DB_query_builder $db
 */
class Short_url extends CI_Model {

    /**
     * @var string
     */
    public $original_url;

    /**
     * @var string
     */
    public $short_code;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var int
     */
    public $visits;

    /**
     * @param array $data
     *
     * @return Short_url|null
     */
    public function insert($data)
    {
        $short_url = new Short_url();
        $short_url->original_url = $data['original_url'];
        $short_url->short_code = $data['short_code'];
        $short_url->created_at = date('Y-m-d H:i:s');
        $short_url->visits = 0;

        $this->db->insert('short_urls', $short_url, TRUE);

        return $this->find_by_id($this->db->insert_id());
    }

    /**
     * @param int $id
     * @return Short_url|null
     */
    public function find_by_id($id)
    {
        $short_url = $this->db
            ->where('id', (int)$id)
            ->get('short_urls', 1)
            ->result('Short_url');

        return !empty($short_url) ? $short_url[0] : null;
    }

    /**
     * @param string $code
     * @return Short_url|null
     */
    public function find_by_code($code)
    {
        $short_url = $this->db
            ->where('short_code', $code, TRUE)
            ->get('short_urls', 1)
            ->result('Short_url');

        return !empty($short_url) ? $short_url[0] : null;
    }

    /**
     * @param string $code
     * @return bool
     */
    public function short_code_exists($code)
    {
        return null !== $this->find_by_code($code);
    }

}