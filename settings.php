<?php

defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) {
    $page = new admin_settingpage('imttsettings', new lang_string('pluginname', 'local_imtt'),
        array('local/imtt:save_plugin_configuration'));

    $page->add(new admin_setting_configtext('local_imtt/middlemanurl',
            new lang_string('middlemanurl', 'local_imtt'),
            new lang_string('middlemanurl_desc', 'local_imtt'),
            null, PARAM_TEXT));

    $page->add(new admin_setting_configtext('local_imtt/middlemanport',
            new lang_string('middlemanport', 'local_imtt'),
            new lang_string('middlemanport_desc', 'local_imtt'),
            null, PARAM_TEXT));

    $ADMIN->add('localplugins', $page);
}

?>
