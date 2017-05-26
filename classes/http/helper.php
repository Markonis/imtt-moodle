<?php

namespace local_imtt\http;

class helper {
    public static function send_json_request($url, $request_data) {
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
