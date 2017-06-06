<?php

namespace local_imtt\model;
defined('MOODLE_INTERNAL') || die();

use local_imtt\auth;
require_once($CFG->libdir . '/google/lib.php');

/**
 * A class that models one row in the local_imtt table. It represents a single
 * configured link between Moodle and the external Middleman service.
 */
class imtt_token {
    public function __construct($DB, $record) {
        $array = json_decode(json_encode($record), true);

        $this->DB                    = $DB;
        $this->id                    = $array['id'];
        $this->provider_name         = $array['provider_name'];
        $this->provider_access_token = $array['provider_access_token'];

        if ($this->configuration_json != null) {
            $this->configuration = json_decode($this->configuration_json, true);
        }
    }

    public static function find_by_id($DB, $id) {
        $record = $DB->get_record('local_imtt_token',
            array('id' => $id), '*', IGNORE_MISSING);

        if ($record == false) {
            return false;
        } else{
            return new imtt_token($DB, $record);
        }
    }

    public static function create($DB, $params) {
        $id = $DB->insert_record('local_imtt_token', $params);
        if ($id > 0) {
            return self::find_by_id($DB, $id);
        } else {
            return false;
        }
    }

    public function update($params) {
        $data = array_merge($params, array('id' => $this->id));
        $result = $this->DB->update_record('local_imtt_token', $data);
        return $result;
    }

    public function refresh_provider_token() {
        $token_refresher = new auth\token_refresher(array(
            'provider_name' => $this->provider_name,
            'access_token' => $this->provider_access_token));

        $access_token = $token_refresher->refresh();
        $this->update(array('provider_access_token' => $access_token));
        $this->provider_access_token = $access_token;
    }
}
?>
