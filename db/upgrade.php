<?php
function xmldb_local_imtt_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2017050900) {
        // Define table local_imtt to be created.
        $table = new xmldb_table('local_imtt');

        // Adding fields to table local_imtt.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('provider_name', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, null);
        $table->add_field('provider_access_token', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('configuration_json', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('event_names', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table local_imtt.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_imtt.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Imtt savepoint reached.
        upgrade_plugin_savepoint(true, 2017050900, 'local', 'imtt');
    }

    if ($oldversion < 2017050901) {

        $table = new xmldb_table('local_imtt');
        $field = new xmldb_field('course_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'event_names');
        $index = new xmldb_index('course_id-index', XMLDB_INDEX_NOTUNIQUE, array('course_id'));

        // Conditionally launch add field course_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add index course_id-index.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Imtt savepoint reached.
        upgrade_plugin_savepoint(true, 2017050901, 'local', 'imtt');
    }

    if ($oldversion < 2017051000) {

        // Changing nullability of field configuration_json on table local_imtt to null.
        $table = new xmldb_table('local_imtt');
        $field = new xmldb_field('configuration_json', XMLDB_TYPE_TEXT, null, null, null, null, null, 'provider_access_token');

        // Launch change of nullability for field configuration_json.
        $dbman->change_field_notnull($table, $field);

        // Imtt savepoint reached.
        upgrade_plugin_savepoint(true, 2017051000, 'local', 'imtt');
    }

    if ($oldversion < 2017052700) {
        upgrade_plugin_savepoint(true, 2017052700, 'local', 'imtt');
    }
}
?>
