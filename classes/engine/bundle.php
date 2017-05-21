<?php

namespace local_imtt\engine;

class bundle {

    private $data;

    public function __construct() {
        $this->data = array();
    }

    public function write($key, $value) {
        $this->data[$key] = $value;
        return $value;
    }

    public function read($path) {
        $path_parts = explode('.', $path);
        $result = $this->data;

        foreach ($path_parts as $part) {
            if (array_key_exists($part, $result)) {
                $result = $result[$part];
            } else {
                return null;
            }
        }

        return $result;
    }

    public function get_data() {
        return $this->data;
    }
}

?>
