<?php

namespace local_imtt\auth;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/google/lib.php');

class token_refresher {
    public function __construct($params) {
        $this->provider_name = $params['provider_name'];
        $this->access_token = $params['access_token'];
    }

    public function refresh() {
        switch ($this->provider_name) {
            case 'google':
                return $this->refresh_google_token();
        }
    }

    public function refresh_google_token() {
        $client = get_google_client();
        $client->setAccessToken($this->access_token);
        if ($client->isAccessTokenExpired()) $client->refreshToken();
        return $client->getAccessToken();
    }
}

?>
