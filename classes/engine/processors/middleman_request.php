<?php

namespace local_imtt\engine\processors;
use local_imtt\http;

class middleman_request extends base {

    public function __construct($params) {
        parent::__construct($params);
        $this->path = $params['path'];
        $this->params = $params['params'];
    }

    public function process($bundle) {
        $url = $this->url();
        $request_data = $this->request_data($bundle);
        $this->send_request($url, $request_data);
        return $this->continue_processing($bundle);
    }

    public function url() {
        $path = $this->path;
        $middleman_url = $this->get_config('middlemanurl');
        $middleman_port = $this->get_config('middlemanport');
        return $middleman_url . ':' . $middleman_port . $path;
    }

    public function get_config($key) {
        return get_config('local_imtt', $key);
    }

    public function request_data($bundle) {
        $keys = array_keys($this->params);
        $params = $this->params;
        $processed_params = $this->params;
        foreach ($keys as $key) {
            $processed_params[$key] = $bundle->read($params[$key]['source']);
        }
        return $processed_params;
    }

    public function send_request($url, $request_data) {
        return http\helper::send_json_request($url, $request_data);
    }
}

?>
