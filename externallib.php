<?php

require_once("$CFG->libdir/externallib.php");

class local_imtt_external extends external_api {

    public static function save_configuration_parameters() {
        return new external_function_parameters(
            array(
                'imtt_instance' => new external_single_structure(array(
                    'id' => new external_value(PARAM_INT, 'id of imtt instance'),
                    'configuration_json' => new external_value(PARAM_TEXT, 'the JSON of the configuration')
                ))
            )
        );
    }

    public static function save_configuration_returns() {
        return new external_single_structure(array(
            'imtt_instance' => new external_single_structure(array(
                'id' => new external_value(PARAM_INT, 'id of imtt instance')
            ))
        ));
    }

    /**
     * Save IMTT configuration JSON
     * @param integer id of the IMTT instance
     * @param string JSON of for the IMTT instance configuration
     * @return integer id of the IMTT instance
     */
    public static function save_configuration($imtt_instance) {
        global $CFG, $DB;

        $params = self::validate_parameters(
            self::save_configuration_parameters(),
            array('imtt_instance' => $imtt_instance));

        // $params = array('imtt_instance' => $imtt_instance);

        // If an exception is thrown in the below code, all DB queries in this
        // code will be rolled back.
        $transaction = $DB->start_delegated_transaction();

        // Load the db_imtt_instance
        $db_imtt_instance = \local_imtt\model\imtt_instance::find_by_id(
            $DB, $params['imtt_instance']['id']);

        // Security checks
        $context = get_context_instance(CONTEXT_COURSE, $db_imtt_instance->course_id);
        self::validate_context($context);
        require_capability('local/imtt:save_configuration', $context);

        if ($db_imtt_instance == false) {
            throw new invalid_parameter_exception('Invalid imtt instance id');
        }

        // Set the configuration_json of the db_imtt_instance
        $db_imtt_instance->update(array(
            'configuration_json' => $params['imtt_instance']['configuration_json']));

        $transaction->allow_commit();

        // Return the db_imtt_instance
        return array('imtt_instance' => array('id' => $db_imtt_instance->id));
    }
}

?>
