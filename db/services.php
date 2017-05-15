<?php
    $services = array(
      'local_imtt_service' => array(
          'functions' => array ('local_imtt_save_configuration'),
          'requiredcapability' => '',
          'restrictedusers' =>0,
          'enabled'=>1
       )
    );

    $functions = array(
        'local_imtt_save_configuration' => array(
            'classname'   => 'local_imtt_external',
            'methodname'  => 'save_configuration',
            'classpath'   => 'local/imtt/externallib.php',
            'description' => 'Saves the configuration JSON of the IMTT instance',
            'type'        => 'write',
            'ajax'        => true
        )
    );
?>
