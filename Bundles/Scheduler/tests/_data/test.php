<?php

$jobs[] = [
    'name' => 'test',
    'command' => 'test',
    'schedule' => '*/10 * * * *',
    'enable' => false,
    'role' => 'admin',
    'customParameter1' => true,
    'customParameter2' => false,
    'stores' => ['AT'],
];

$jobs[] = [
    'name' => 'test1',
    'command' => 'test1',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'role' => 'empty',
    'customParameter1' => true,
    'customParameter2' => false,
    'stores' => ['DE', 'AT', 'US'],
];

$jobs[] = [
    'name' => 'test2',
    'command' => 'test2',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'role' => 'empty',
    'customParameter1' => true,
    'customParameter2' => false,
    'stores' => ['DE', 'AT', 'US'],
];

$jobs[] = [
    'name' => 'test3',
    'command' => 'test3',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'role' => 'admin',
    'customParameter1' => true,
    'customParameter2' => false,
    'stores' => [],
];

$jobs[] = [
    'name' => 'test4',
    'command' => 'test4',
    'schedule' => '0 6 * * *',
    'enable' => true,
    'role' => 'admin',
    'customParameter1' => true,
    'customParameter2' => false,
    'stores' => ['DE', 'AT', 'US'],
];
