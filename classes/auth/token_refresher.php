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
        $tokens = json_decode($this->access_token, true);
        $refresh_token = $tokens['refresh_token'];

        $client = get_google_client();
        $client->setClientId(get_config('googledocs', 'clientid'));
        $client->setClientSecret(get_config('googledocs', 'secret'));
        $client->setAccessToken($this->access_token);

        if ($client->isAccessTokenExpired())
            $client->refreshToken($refresh_token);

        $new_tokens = json_decode($client->getAccessToken(), true);
        $new_tokens['refresh_token'] = $client->getRefreshToken();
        return json_encode($new_tokens);
    }
}

?>
