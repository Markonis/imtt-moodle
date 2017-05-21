<?php

namespace local_imtt\engine;

use local_imtt\model;
use local_imtt\engine\triggers;
use local_imtt\engine\processors;

class engine {
    public function __construct($params) {
        $this->DB = $params['DB'];
    }

    public function on_moodle_event($event) {
        $imtt_instances = $this->load_imtt_instances();
        foreach ($imtt_instances as $instance) {
            $config = $instance->configuration;
            foreach ($config['pipelines'] as $pipeline_config) {
                $this->process_event($event, 'moodle_event', $pipeline_config);
            }
        }
    }

    public function process_event($event, $trigger_type, $pipeline_config) {
        $trigger_config = $pipeline_config['trigger'];
        if ($trigger_config['type'] == $trigger_type) {
            $trigger = $this->create_trigger($trigger_config);
            if ($this->should_process_trigger($event, $trigger)) {
                $this->process_trigger($event, $trigger, $pipeline_config);
            }
        }
    }

    public function load_imtt_instances() {
        return model\imtt_instance::all($this->DB);
    }

    public function create_trigger($trigger_config) {
        switch ($trigger_config['type']) {
            case 'moodle_event':
                return new triggers\moodle_event($trigger_config);
        }
    }

    public function should_process_trigger($trigger, $event) {
        return $trigger->should_process_trigger($event);
    }

    public function process_trigger($event, $trigger, $pipeline_config) {
        $trigger_data = $trigger->extract_data($event);
        $processors = $this->create_processors($pipeline_config['processors']);
        $params = $pipeline_config['params'];
        $pipeline = $this->create_pipeline($trigger_data, $params, $processors);
        $pipeline->process();
    }

    public function create_pipeline($trigger_data, $params, $processors) {
        return new pipeline(array(
            'trigger_data' => $trigger_data,
            'params' => $params,
            'processors' => $processors
        ));
    }

    public function create_processors($processor_configs) {
        $processors = array();
        foreach ($processor_configs as $config) {
            array_push($processors, $this->create_processor($config));
        }
        return $processors;
    }

    public function create_processor($processor_config) {
        switch ($processor_config['type']) {
            case 'filter':
                return new processors\filter($processor_config);
            case 'middleman_request':
                return new processors\middleman_request($processor_config);
        }
    }
}

?>