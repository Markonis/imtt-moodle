<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/imtt:save_plugin_configuration' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'clonepermissionsfrom' => 'moodle/site:config'
    ),

    'local/imtt:save_instance_configuration' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'clonepermissionsfrom' => 'moodle/course:update'
    )
);

?>
