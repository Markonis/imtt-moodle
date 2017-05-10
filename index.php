<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/google/lib.php');

require_login();
$error = optional_param('error', null, PARAM_TEXT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/imtt/index.php'));
$PAGE->set_heading('If Moodle Than That');
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('local_imtt');
$params = array('course' => $COURSE, 'error' => $error);
$page = new \local_imtt\output\imtt_main_page($DB, $params);

echo $output->header();
echo $output->render_page($page);
echo $output->footer();

?>