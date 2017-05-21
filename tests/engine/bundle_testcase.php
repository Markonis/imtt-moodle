<?php

use local_imtt\engine;

/**
 * Unit tests for the IMTT bundle
 * @group local_imtt
 */
class local_imtt_bundle_testcase extends basic_testcase {
    public function test_constructor() {
        $bundle = new engine\bundle();
    }

    public function test_write() {
        $bundle = new engine\bundle();
        $result = $bundle->write('test', 'ABC');
        $this->assertEquals($result, 'ABC');
        $this->assertEquals($bundle->get_data()['test'], 'ABC');
    }

    public function test_read() {
        $bundle = new engine\bundle();
        $bundle->write('level_0', 'LEVEL_0');
        $bundle->write('level_1', array('a' => 'LEVEL_1'));
        $bundle->write('level_2', array('a' => array('b' => 'LEVEL_2')));

        $this->assertEquals($bundle->read('level_0'), 'LEVEL_0');
        $this->assertEquals($bundle->read('level_1.a'), 'LEVEL_1');
        $this->assertEquals($bundle->read('level_2.a.b'), 'LEVEL_2');
    }
}

?>