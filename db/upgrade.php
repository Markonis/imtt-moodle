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

    if ($oldversion < 2017060600) {
        //===============================
        // create local_imtt_token table
        //===============================

        // Define table local_imtt_token to be created.
        $table = new xmldb_table('local_imtt_token');

        // Adding fields to table local_imtt_token.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('provider_name', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, null);
        $table->add_field('provider_access_token', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_imtt_token.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_imtt_token.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        //=========================
        // update local_imtt table
        //=========================

        $table = new xmldb_table('local_imtt');

        // Define field provider_access_token to be dropped from local_imtt.
        $field = new xmldb_field('provider_access_token');

        // Conditionally launch drop field provider_access_token.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field provider_name to be dropped from local_imtt.
        $field = new xmldb_field('provider_name');

        // Conditionally launch drop field provider_name.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field event_names to be dropped from local_imtt.
        $field = new xmldb_field('event_names');

        // Conditionally launch drop field event_names.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field token_id to be added to local_imtt.
        $field = new xmldb_field('token_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'configuration_json');

        // Conditionally launch add field token_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2017060600, 'local', 'imtt');
    }

    if ($oldversion < 2017060601) {
        // Define key token_id (foreign) to be added to local_imtt.
        $table = new xmldb_table('local_imtt');
        $key = new xmldb_key('token_id', XMLDB_KEY_FOREIGN, array('token_id'), 'local_imtt_token', array('id'));

        // Launch add key token_id.
        $dbman->add_key($table, $key);

        // Imtt savepoint reached.
        upgrade_plugin_savepoint(true, 2017060601, 'local', 'imtt');
    }
}
?>
