<?php

namespace local_imtt\model;
defined('MOODLE_INTERNAL') || die();

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
        $this->course_id             = $array['course_id'];
        $this->provider_name         = $array['provider_name'];
        $this->provider_access_token = $array['provider_access_token'];
        $this->configuration_json    = $array['configuration_json'];
        $this->event_names           = $array['event_names'];

        if ($this->configuration_json != null) {
            $this->configuration = json_decode($this->configuration_json, true);
        }

        $this->pipeline_data = array(
            'provider_access_token' => $this->provider_access_token);
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
}
?>