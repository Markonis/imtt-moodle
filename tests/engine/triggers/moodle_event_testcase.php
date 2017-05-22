<?php

use local_imtt\engine;
use local_imtt\engine\triggers;

/**
 * Unit tests for the IMTT moodle event trigger
 * @group local_imtt
 */
class local_imtt_moodle_event_testcase extends basic_testcase {
    public function test_constructor() {
        $trigger = new triggers\moodle_event(array(
            'event_name' => '\\test\\event'));

        $this->assertEquals($trigger->event_name, '\\test\\event');
    }

    public function test_should_process() {
        $trigger = new triggers\moodle_event(array(
            'event_name' => '\\test\\event'));

        $event = new stdClass();

        $event->eventname = '\\test\\event';
        $this->assertEquals($trigger->should_process($event), true);

        $event->eventname = '\\test\\event\\1';
        $this->assertEquals($trigger->should_process($event), false);
    }

    public function test_extract_data() {
        $trigger = new triggers\moodle_event(array(
            'event_name' => '\\test\\event'));

        $event = new stdClass();
        $event->eventname = '\\test\\event';

        $result = $trigger->extract_data($event);
        $this->assertEquals($result['eventname'], '\\test\\event');
    }
}

?>
