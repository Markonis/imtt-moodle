<?php

namespace local_imtt\model;
defined('MOODLE_INTERNAL') || die();

use local_imtt\auth;
use local_imtt\model;

require_once($CFG->libdir . '/google/lib.php');

/**
 * A class that models one row in the local_imtt table. It represents a single
 * configured link between Moodle and the external Middleman service.
 */
class imtt_instance {
    public function __construct($DB, $record) {
        $array = json_decode(json_encode($record), true);

        $this->DB                    = $DB;
        $this->id                    = $array['id'];
        $this->token_id              = $array['token_id'];
        $this->course_id             = $array['course_id'];
        $this->configuration_json    = $array['configuration_json'];
        $this->token                 = $this->load_token();

        if ($this->configuration_json != null) {
            $this->configuration = json_decode($this->configuration_json, true);
        }
    }

    public static function find_by_course_id($DB, $course_id) {
        $record = $DB->get_record('local_imtt',
            array('course_id' => $course_id), '*', IGNORE_MISSING);

        if ($record == false) {
            return false;
        } else{
            return new imtt_instance($DB, $record);
        }
    }

    public static function find_by_id($DB, $id) {
        $record = $DB->get_record('local_imtt',
            array('id' => $id), '*', IGNORE_MISSING);

        if ($record == false) {
            return false;
        } else{
            return new imtt_instance($DB, $record);
        }
    }

    public static function all($DB) {
        $records = $DB->get_records('local_imtt');
        $all = array();
        foreach ($records as $rec) {
            array_push($all, new imtt_instance($DB, $rec));
        }
        return $all;
    }

    public static function create($DB, $params) {
        $id = $DB->insert_record('local_imtt', $params);
        if ($id > 0) {
            return self::find_by_id($DB, $id);
        } else {
            return false;
        }
    }

    public function update($params) {
        $data = array_merge($params, array('id' => $this->id));
        $result = $this->DB->update_record('local_imtt', $data);
        return $result;
    }

    public function destroy() {
        $this->$DB->delete_records('local_imtt', array('id' => $this->id));
    }

    public function load_token() {
        return model\imtt_token::find_by_id($this->DB, $this->token_id);
    }

    public function refresh_token() {
        $this->token->refresh_token();
    }

    public function get_pipeline_data() {
        return array('provider_access_token' => $this->token->provider_access_token);
    }
}
?>
