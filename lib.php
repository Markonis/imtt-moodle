<?php

defined('MOODLE_INTERNAL') || die();

/**
 * This function extends the navigation menu and adds a node in the course administration section.
 *
 * @param  navigation_node $navigation Navigation node to extend.
 * @param  stdClass $course Course object
 * @param  context_course context Course context
 * @return void.
 */
function local_imtt_extend_navigation_course($navigation, $course, $context) {
    $url = new moodle_url('/local/imtt/index.php');
    $imtt_node = navigation_node::create('If Moodle Than That', $url, navigation_node::TYPE_CUSTOM, 'IMTT', 'imtt');
    $navigation->add_node($imtt_node);
}
?>