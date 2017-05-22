<?php

use local_imtt\engine;

/**
 * Unit tests for the IMTT pipeline
 * @group local_imtt
 */
class local_imtt_pipeline_testcase extends basic_testcase {
    public function test_constructor() {
        $pipeline = new engine\pipeline(array(
            'trigger_data' => array(),
            'processors' => array(),
            'params' => array()));
    }

    public function test_create_bundle() {
        $pipeline = new engine\pipeline(array(
            'trigger_data' => 'trigger-data',
            'processors' => array(),
            'params' => 'params'));

        $result = $pipeline->create_bundle();
        $this->assertEquals($result->read('trigger_data'), 'trigger-data');
        $this->assertEquals($result->read('params'), 'params');
    }

    public function test_process_continue() {
        $pipeline = $this->getMockBuilder(engine\pipeline::class)
            ->setConstructorArgs(array(array(
                'trigger_data' => 'trigger-data',
                'processors' => array('p1', 'p2'),
                'params' => 'params')))
            ->setMethods(['invoke_processor'])
            ->getMock();

        $pipeline->method('invoke_processor')->willReturn(array(
            'continue' => true, 'bundle' => 'bundle'));

        $pipeline->expects($this->exactly(2))->method('invoke_processor');
        $pipeline->process();
    }

    public function test_process_stop() {
        $pipeline = $this->getMockBuilder(engine\pipeline::class)
            ->setConstructorArgs(array(array(
                'trigger_data' => 'trigger-data',
                'processors' => array('p1', 'p2'),
                'params' => 'params')))
            ->setMethods(['invoke_processor'])
            ->getMock();

        $pipeline->method('invoke_processor')->willReturn(array(
            'continue' => false, 'bundle' => 'bundle'));

        $pipeline->expects($this->once())->method('invoke_processor');
        $pipeline->process();
    }
}

?>
