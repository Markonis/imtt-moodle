<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/google/lib.php');

use local_imtt\auth;
use local_imtt\model;

require_login();

$course_id = required_param('course_id', PARAM_INT); // Course ID
$code = optional_param('oauth2code', null, PARAM_RAW);

$url = null;

if ($code) {
    // Get Google access token
    $google_auth = new auth\google_auth($course_id);
    $google_auth->client->authenticate($code);

    $tokens = json_decode($google_auth->client->getAccessToken(), true);
    $tokens['refresh_token'] = $google_auth->client->getRefreshToken();

    // Create an IMTT instance for this course with the Google access token
    $result = model\imtt_token::create($DB, array(
        'user_id' => $USER->id,
        'provider_name' => 'google',
        'provider_access_token' => json_encode($tokens)));

    redirect(new moodle_url('/local/imtt/index.php',
        array('course_id' => $course_id)));
} else {
    redirect(new moodle_url('/local/imtt/index.php',
        array('course_id' => $course_id, 'error' => 'OAuth failed.')));
}
?>