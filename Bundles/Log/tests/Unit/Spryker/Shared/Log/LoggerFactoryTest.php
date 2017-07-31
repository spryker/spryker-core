<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Log;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\Config\DefaultLoggerConfig;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Log\LoggerFactory;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Log
 * @group LoggerFactoryTest
 */
class LoggerFactoryTest extends Unit
{

    /**
     * @return void
     */
    public function testGetInstanceShouldReturnConfiguredLogger()
    {
        $reflection = new ReflectionClass(Config::class);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);
        $config = $property->getValue();
        $config[LogConstants::LOGGER_CONFIG] = DefaultLoggerConfig::class;
        $property->setValue($config);

        $loggerFactory = new LoggerFactory();
        $logger = $loggerFactory->getInstance();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    /**
     * @return void
     */
    public function testGetInstanceWithoutConfiguredLoggerShouldReturnDefaultLogger()
    {
        $reflection = new ReflectionClass(Config::class);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);
        $config = $property->getValue();
        if (isset($config[LogConstants::LOGGER_CONFIG])) {
            unset($config[LogConstants::LOGGER_CONFIG]);
        }
        $loggerFactory = new LoggerFactory();
        $logger = $loggerFactory->getInstance();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

}
