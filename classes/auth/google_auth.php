<?php
namespace local_imtt\auth;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/google/lib.php');

class google_auth {
    /**
     * Google Client.
     * @var Google_Client
     */
    public $client = null;

    /**
     * URI to the callback file for OAuth.
     * @var string
     */
    const CALLBACK_URL = '/admin/oauth2callback.php';

    /**
     * Construct the Google auth wrapper
     */
    public function __construct($course_id) {
        $callback_url = new \moodle_url(self::CALLBACK_URL);
        $this->callback_url = $callback_url;

        $this->client = get_google_client();
        $this->client->setClientId(get_config('googledocs', 'clientid'));
        $this->client->setClientSecret(get_config('googledocs', 'secret'));
        $this->client->setScopes(array(\Google_Service_Drive::DRIVE));
        $this->client->setAccessType('offline');
        $this->client->setRedirectUri($callback_url->out(false));

        $return_url = new \moodle_url('/local/imtt/oauth_callback.php');
        $return_url->param('callback', 'yes');
        $return_url->param('sesskey', sesskey());
        $return_url->param('course_id', $course_id);

        $this->auth_url = new \moodle_url($this->client->createAuthUrl());
        $this->auth_url->param('state', $return_url->out_as_local_url(false));
    }
}
?>