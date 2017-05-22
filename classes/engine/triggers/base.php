<?php

namespace local_imtt\engine\triggers;

class base {
    public function __construct($params) {}

    public function should_process($event) {
        throw new Exception('Must override the should_trigger function.');
    }

    public function extract_data($event) {
        throw new Exception('Must override the trigger_data function.');
    }
}

?>
