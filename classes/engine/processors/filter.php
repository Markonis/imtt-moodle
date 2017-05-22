<?php

namespace local_imtt\engine\processors;

class filter extends base {

    public function __construct($params) {
        parent::__construct($params);
        $this->condition = $params['condition'];
    }

    public function process($bundle) {
        if ($this->check_condition($bundle, $this->condition)) {
            return $this->continue_processing($bundle);
        } else {
            return $this->stop_processing($bundle);
        }
    }

    public function check_condition($bundle, $condition) {
        switch ($condition['type']) {
            case 'equal':
                return $this->check_equal_condition($bundle, $condition);
        }
    }

    public function check_equal_condition($bundle, $condition) {
        $op1 = $this->operand_value($bundle, $condition['op1']);
        $op2 = $this->operand_value($bundle, $condition['op2']);
        return $op1 == $op2;
    }

    public function operand_value($bundle, $operand) {
        switch ($operand['type']) {
            case 'data':
                return $bundle->read($operand['source']);
            case 'filter':
                return $this->check_condition($bundle, $operand['condition']);
            default:
                return false;
        }
    }
}

?>
