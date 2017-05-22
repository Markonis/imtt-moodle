<?php

use local_imtt\engine;
use local_imtt\engine\processors;

/**
 * Unit tests for the IMTT filter processor
 * @group local_imtt
 */
class local_imtt_filter_testcase extends basic_testcase {
    public function test_constructor() {
        $filter = new processors\filter(array(
            'type' => 'filter', 'condition' => 'condition'));

        $this->assertEquals($filter->type, 'filter');
        $this->assertEquals($filter->condition, 'condition');
    }

    public function test_process_when_condition_passes() {
        $filter = $this->getMockBuilder(processors\filter::class)
            ->setConstructorArgs(array(array(
                'type' => 'filter',
                'condition' => 'condition')))
            ->setMethods(['check_condition'])
            ->getMock();

        $filter->method('check_condition')->willReturn(true);

        $result = $filter->process('bundle');

        $this->assertEquals($result['continue'], true);
        $this->assertEquals($result['bundle'], 'bundle');
    }

    public function test_process_when_condition_does_not_pass() {
        $filter = $this->getMockBuilder(processors\filter::class)
            ->setConstructorArgs(array(array(
                'type' => 'filter',
                'condition' => 'condition')))
            ->setMethods(['check_condition'])
            ->getMock();

        $filter->method('check_condition')->willReturn(false);

        $result = $filter->process('bundle');
        $this->assertEquals($result['continue'], false);
        $this->assertEquals($result['bundle'], 'bundle');
    }

    public function test_check_condition() {
        $filter = $this->getMockBuilder(processors\filter::class)
            ->setConstructorArgs(array(array(
                'type' => 'filter',
                'condition' => 'condition')))
            ->setMethods(['check_equal_condition'])
            ->getMock();

        $filter->expects($this->once())->method('check_equal_condition');

        $filter->check_condition('bundle', array('type' => 'equal'));
    }

    public function test_operand_value_data() {
        $filter = new processors\filter(array(
            'type' => 'filter', 'condition' => 'condition'));

        $bundle = new engine\bundle();
        $bundle->write('test', 'TEST');

        $result = $filter->operand_value($bundle, array(
            'type' => 'data', 'source' => 'test'));

        $this->assertEquals($result, 'TEST');
    }

    public function test_operand_value_filter() {
        $filter = $this->getMockBuilder(processors\filter::class)
            ->setConstructorArgs(array(array(
                'type' => 'filter',
                'condition' => 'condition')))
            ->setMethods(['check_condition'])
            ->getMock();

        $bundle = new engine\bundle();

        $filter->expects($this->once())->method('check_condition');

        $filter->operand_value($bundle, array(
            'type' => 'filter', 'condition' => 'condition'));
    }
}

?>
