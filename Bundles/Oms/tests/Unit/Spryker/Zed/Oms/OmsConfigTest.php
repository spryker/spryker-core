<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Oms;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Oms\OmsConfig;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Oms
 * @group OmsConfigTest
 */
class OmsConfigTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    private $configCache;

    /**
     * @return void
     */
    public function setUp()
    {
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $this->configCache = $reflectionProperty->getValue();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->configCache);
    }

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationReturnDefault()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(['foo' => 'bar']);

        $this->assertSame(OmsConfig::DEFAULT_PROCESS_LOCATION, $omsConfig->getProcessDefinitionLocation());
    }

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationReturnConfiguredPath()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([OmsConstants::PROCESS_LOCATION => APPLICATION_ROOT_DIR . '/configuredPaths']);

        $this->assertSame(APPLICATION_ROOT_DIR . '/configuredPaths', $omsConfig->getProcessDefinitionLocation());
    }

    /**
     * @return void
     */
    public function testGetProcessDefinitionLocationDefaultPathMustBeAbsolute()
    {
        $omsConfig = new OmsConfig();
        $reflectionClass = new ReflectionClass(Config::class);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(['foo' => 'bar']);

        $this->assertStringStartsWith(APPLICATION_ROOT_DIR, $omsConfig->getProcessDefinitionLocation());
    }

}
