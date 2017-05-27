<?php

namespace local_imtt\engine\triggers;

class moodle_event extends base {
    public function __construct($params) {
        $this->event_name = $params['event_name'];
    }

    public function should_process($event) {
        return $event->eventname == $this->event_name;
    }

    public function extract_data($event) {
        return array(
            'eventname' => $event->eventname,
            'other' => $event->other,
            'object' => $this->load_object($event->objectid, $event->objecttable),
            'user' => $this->load_user($event->userid));
    }

    public function load_user($id) {
        return $this->load_object($id, 'user');
    }

    public function load_object($id, $table) {
        global $DB;
        $record = $DB->get_record($table, array('id' => $id));
        return (array) $record;
    }
}

?>
