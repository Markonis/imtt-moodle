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
        return $event;
    }
}

?>
