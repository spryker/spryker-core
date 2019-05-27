<?php

/* -- MAIL QUEUE -- */
$jobs[] = [
    'name' => 'test',
    'command' => 'test',
    'schedule' => '*/10 * * * *',
    'enable' => false,
    'run_on_non_production' => true,
    'stores' => ['DE', 'AT'],
];

/* ProductValidity */
$jobs[] = [
    'name' => 'test1',
    'command' => 'test1',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'run_on_non_production' => true,
    'stores' => ['DE', 'AT', 'US'],
];
