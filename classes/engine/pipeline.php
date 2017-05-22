<?php

namespace local_imtt\engine;

class pipeline {
    public function __construct($params) {
        $this->trigger_data = $params['trigger_data'];
        $this->processors = $params['processors'];
        $this->params = $params['params'];
    }

    public function process() {
        $bundle = $this->create_bundle();
        foreach ($this->processors as $proc) {
            $result = $this->invoke_processor($proc, $bundle);
            if ($result['continue']) {
                $bundle = $result['bundle'];
            } else {
                break;
            }
        }
    }

    public function invoke_processor($processor, $bundle) {
        return $processor->process($bundle);
    }

    public function create_bundle() {
        $bundle = new bundle();
        $bundle->write('trigger_data', $this->trigger_data);
        $bundle->write('params', $this->params);
        return $bundle;
    }
}

?>
