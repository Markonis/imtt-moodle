<?php

namespace local_imtt\engine\processors;

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
        $request_data_json = json_encode($request_data);
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data_json);

        $response_data_json = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $response_data = json_decode($response_data_json, true);
        return array('status' => $status, 'data' => $response_data);
    }
}

?>
