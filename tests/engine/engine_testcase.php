<?php

use local_imtt\model;
use local_imtt\engine;
use local_imtt\engine\triggers;
use local_imtt\engine\processors;

/**
 * Unit tests for the IMTT engine
 * @group local_imtt
 */
class local_imtt_engine_testcase extends basic_testcase {
    public function test_constructor() {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));
        $this->assertEquals($engine->DB, $DB);
    }

    public function test_create_trigger() {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));

        $trigger = $engine->create_trigger(array(
            'type' => 'moodle_event', 'event_name' => 'some\\event'));

        $this->assertInstanceOf(triggers\moodle_event::class, $trigger);
        $this->assertEquals($trigger->event_name, 'some\\event');
    }

    public function test_create_pipeline() {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));
        $pipeline = $engine->create_pipeline('ipd', 'td', 'par', 'proc');

        $this->assertInstanceOf(engine\pipeline::class, $pipeline);
        $this->assertEquals($pipeline->instance_pipeline_data, 'ipd');
        $this->assertEquals($pipeline->trigger_data, 'td');
        $this->assertEquals($pipeline->params, 'par');
        $this->assertEquals($pipeline->processors, 'proc');
    }

    public function test_create_processor() {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));

        $filter = $engine->create_processor(array(
            'type' => 'filter',
            'condition' => array('type' => 'equal')));

        $middleman_request = $engine->create_processor(array(
            'type' => 'middleman_request',
            'path' => '/api/some/path',
            'params' => 'params'));

        $this->assertInstanceOf(processors\filter::class, $filter);
        $this->assertInstanceOf(processors\middleman_request::class, $middleman_request);
    }

    public function test_create_processors() {
        global $DB;
        $engine = new engine\engine(array('DB' => $DB));

        $processors_config = array(
            array('type' => 'filter', 'condition' => array('type' => 'equal')),
            array('type' => 'filter', 'condition' => array('type' => 'equal'))
        );

        $processors = $engine->create_processors($processors_config);
        $this->assertEquals(2, count($processors));
    }

    public function test_on_moodle_event() {
        global $DB;

        $engine = $this->getMockBuilder(engine\engine::class)
            ->setConstructorArgs(array(array('DB' => $DB)))
            ->setMethods([
                'load_imtt_instances',
                'process_event'])
            ->getMock();

        $imtt_instance = new stdClass();

        $imtt_instance->pipeline_data = array(
            'provider_access_token' => 'pat');

        $imtt_instance->configuration = array(
            'pipelines' => array('pipeline-config'));

        $engine->method('load_imtt_instances')
            ->will($this->returnValue(array($imtt_instance)));

        $engine->expects($this->once())
            ->method('load_imtt_instances');

        $engine->expects($this->once())
            ->method('process_event');

        $engine->on_moodle_event('test-event');
    }

    public function test_process_event_with_wrong_trigger_type() {
        global $DB;

        $pipeline_config = array('trigger' => array('type' => 'trigger-type-1'));

        $engine = $this->getMockBuilder(engine\engine::class)
            ->setConstructorArgs(array(array('DB' => $DB)))
            ->setMethods(['create_trigger'])
            ->getMock();

        $engine->expects($this->never())->method('create_trigger');

        $engine->process_event(null, 'trigger-type-2', $pipeline_config, 'ipd');
    }

    public function test_process_event_with_correct_trigger_type_should_not_process() {
        global $DB;

        $pipeline_config = array('trigger' => array('type' => 'trigger-type-1'));

        $engine = $this->getMockBuilder(engine\engine::class)
            ->setConstructorArgs(array(array('DB' => $DB)))
            ->setMethods([
                'create_trigger',
                'should_process_pipeline',
                'process_pipeline'])
            ->getMock();

        $engine->expects($this->once())->method('create_trigger');
        $engine->expects($this->never())->method('process_pipeline');

        $engine->process_event(null, 'trigger-type-1', $pipeline_config, 'ipd');
    }

    public function test_process_event_with_correct_trigger_type_should_process_pipeline() {
        global $DB;

        $pipeline_config = array('trigger' => array('type' => 'trigger-type-1'));
        $trigger = $this->createMock(triggers\moodle_event::class);
        $trigger->method('extract_data')->willReturn(null);

        $engine = $this->getMockBuilder(engine\engine::class)
            ->setConstructorArgs(array(array('DB' => $DB)))
            ->setMethods([
                'create_trigger',
                'should_process_pipeline',
                'process_pipeline'])
            ->getMock();

        $engine->method('create_trigger')->willReturn($trigger);

        $engine->method('should_process_pipeline')->willReturn(true);

        $engine->expects($this->once())->method('create_trigger');
        $engine->expects($this->once())->method('process_pipeline');

        $engine->process_event(array(), 'trigger-type-1', $pipeline_config, 'ipd');
    }
}

?>