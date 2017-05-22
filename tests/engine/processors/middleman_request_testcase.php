<?php

use local_imtt\engine;
use local_imtt\engine\processors;

/**
 * Unit tests for the IMTT middleman request processor
 * @group local_imtt
 */
class local_imtt_middleman_request_testcase extends basic_testcase {
    public function test_constructor() {
        $mr = new processors\middleman_request(array(
            'type' => 'middleman_request',
            'path' => '/some/api/path',
            'params' => 'params'));

        $this->assertEquals($mr->type, 'middleman_request');
        $this->assertEquals($mr->path, '/some/api/path');
        $this->assertEquals($mr->params, 'params');
    }

    public function test_request_data() {
        $mr = new processors\middleman_request(array(
            'type' => 'middleman_request',
            'path' => '/some/api/path',
            'params' => array(
                'param_a' => array('source' => 'p_a'),
                'param_b' => array('source' => 'p_b'))));

        $bundle = new engine\bundle();
        $bundle->write('p_a', 'P_A');
        $bundle->write('p_b', 'P_B');

        $result = $mr->request_data($bundle);

        $this->assertEquals($result, array(
            'param_a' => 'P_A', 'param_b' => 'P_B'));
    }

    public function test_url() {
        $mr = $this->getMockBuilder(processors\middleman_request::class)
            ->setConstructorArgs(array(array(
                'type' => 'middleman_request',
                'path' => '/some/api/path',
                'params' => 'params')))
            ->setMethods(['get_config'])
            ->getMock();

        $mr->method('get_config')->will($this->returnValueMap(array(
            array('middlemanurl', 'http://127.0.0.1'),
            array('middlemanport', '3000'))));

        $result = $mr->url();

        $this->assertEquals($result, 'http://127.0.0.1:3000/some/api/path');
    }
}

?>
