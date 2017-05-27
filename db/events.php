<?php

$observers = array(
    array(
        'eventname' => '*',
        'callback'  => '\local_imtt\event_observer::observe_all'
    )
);

?>
