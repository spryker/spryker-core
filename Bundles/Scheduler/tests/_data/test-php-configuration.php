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
    'name' => 'check-product-validity',
    'command' => '$PHP_BIN vendor/bin/console product:check-validity',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'run_on_non_production' => true,
    'stores' => ['DE', 'AT', 'US'],
];
