<?php

namespace local_imtt\engine\processors;

class base {

    public function __construct($params) {
        $this->type = $params['type'];
    }

    public function process($bundle) {
        throw new Exception('Must override the process function.');
    }

    public function continue_processing($bundle) {
        return array('continue' => true, 'bundle' => $bundle);
    }

    public function stop_processing($bundle) {
        return array('continue' => false, 'bundle' => $bundle);
    }
}

?>