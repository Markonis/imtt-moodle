<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/imtt:save_configuration' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'clonepermissionsfrom' => 'moodle/course:update'
    )
);

?>
