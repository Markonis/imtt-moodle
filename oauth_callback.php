<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/google/lib.php');

require_login();

$course_id = required_param('course_id', PARAM_INT); // Course ID
$code = optional_param('oauth2code', null, PARAM_RAW);

$url = null;

if ($code) {
    // Get Google access token
    $google_auth = new \local_imtt\auth\google_auth($course_id);
    $google_auth->client->authenticate($code);
    $access_token = $google_auth->client->getAccessToken();

    // Create an IMTT instance for this course with the Google access token
    $result = \local_imtt\model\imtt_instance::create($DB, array(
        'course_id' => $course_id,
        'provider_name' => 'google',
        'provider_access_token' => $access_token));

    redirect(new moodle_url('/local/imtt/index.php',
        array('course_id' => $course_id)));
} else {
    redirect(new moodle_url('/local/imtt/index.php',
        array('course_id' => $course_id, 'error' => 'OAuth failed.')));
}
?>