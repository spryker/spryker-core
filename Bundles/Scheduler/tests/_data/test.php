<?php

/* -- MAIL QUEUE -- */
$jobs[] = [
    'name' => 'test',
    'command' => 'test',
    'schedule' => '*/10 * * * *',
    'enable' => false,
    'payload1' => true,
    'payload2' => false,
    'stores' => ['DE', 'AT'],
];

/* ProductValidity */
$jobs[] = [
    'name' => 'test1',
    'command' => 'test1',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'payload1' => true,
    'payload2' => false,
    'stores' => ['DE', 'AT', 'US'],
];
