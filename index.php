<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/google/lib.php');

require_login();
$course_id = required_param('course_id', PARAM_INT);
$error = optional_param('error', null, PARAM_TEXT);

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/imtt/index.php'));
$PAGE->set_heading('If Moodle Than That');
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('local_imtt');

echo $output->header();

$page = new \local_imtt\output\imtt_main_page(array(
    'PAGE' => $PAGE, 'DB' => $DB, 'USER' => $USER,
    'course_id' => $course_id, 'error' => $error));

echo $output->render_page($page);
echo $output->footer();
?>