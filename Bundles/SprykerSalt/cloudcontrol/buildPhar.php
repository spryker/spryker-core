<?php

/**
 * NOT WORKING - SOMETHING WRONG WITH THE AUTOLOADER
 */
$phar = new Phar('project.phar', 0, 'project.phar');
$phar->buildFromDirectory(realpath('/Users/mike/projects/boss-salt/cloudcontrol'));
$phar->setStub($phar->createDefaultStub('src/clc'));
