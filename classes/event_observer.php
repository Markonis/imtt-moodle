<?php

namespace local_imtt;
use local_imtt\engine;

class event_observer {
    public static function observe_all($event) {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));
        $engine->on_moodle_event($event);
    }
}

?>
